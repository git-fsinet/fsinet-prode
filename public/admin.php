<?php
session_start();
require_once '../config/db.php';
require_once '../includes/layout.php';

// Check if admin (for this demo, we can set is_admin = 1 in DB for a specific user)
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user || !$user['is_admin']) {
    die("Acceso denegado. Se requiere perfil administrador.");
}

// Current selection from GET
$current_stage = isset($_GET['stage']) ? (int)$_GET['stage'] : 1;
$current_group = isset($_GET['group']) ? $_GET['group'] : 'A';

// Get groups for stage 1
$available_groups = [];
if ($current_stage == 1) {
    $available_groups = $pdo->query("SELECT DISTINCT group_name FROM matches WHERE stage_id = 1 AND group_name IS NOT NULL ORDER BY group_name ASC")->fetchAll(PDO::FETCH_COLUMN);
}

// Get all teams for the dropdowns
$all_teams = $pdo->query("SELECT team FROM (SELECT team1 AS team FROM matches WHERE stage_id = 1 UNION SELECT team2 AS team FROM matches WHERE stage_id = 1) t WHERE team IS NOT NULL ORDER BY team ASC")->fetchAll(PDO::FETCH_COLUMN);

// Update Multiple Match Results
if (isset($_POST['update_all_matches']) && isset($_POST['results'])) {
    $saved = 0;
    foreach ($_POST['results'] as $match_id => $scores) {
        $r1 = $scores['result1'];
        $r2 = $scores['result2'];
        $t1 = isset($scores['team1']) ? trim($scores['team1']) : null;
        $t2 = isset($scores['team2']) ? trim($scores['team2']) : null;
        $pen1 = isset($scores['penalties1']) && $scores['penalties1'] !== '' ? $scores['penalties1'] : null;
        $pen2 = isset($scores['penalties2']) && $scores['penalties2'] !== '' ? $scores['penalties2'] : null;
        
        $match_date = isset($scores['match_date']) ? $scores['match_date'] : null;
        if ($match_date) {
            $match_date = str_replace('T', ' ', $match_date);
            if (strlen($match_date) == 16) $match_date .= ':00';
        }
        
        // Always update teams if provided
        if ($t1 !== null && $t2 !== null) {
            $stmt = $pdo->prepare("UPDATE matches SET team1 = ?, team2 = ? WHERE id = ?");
            $stmt->execute([$t1, $t2, $match_id]);
        }
        
        // Always update date if provided
        if ($match_date) {
            $stmt = $pdo->prepare("UPDATE matches SET match_date = ? WHERE id = ?");
            $stmt->execute([$match_date, $match_id]);
        }
        
        if ($r1 === '' || $r2 === '') {
            $stmt = $pdo->prepare("UPDATE matches SET result1 = NULL, result2 = NULL, penalties1 = NULL, penalties2 = NULL, status = 'pending' WHERE id = ?");
            $stmt->execute([$match_id]);
        } else {
            // Only save penalties if it was a tie
            if ($r1 != $r2) {
                $pen1 = null; $pen2 = null;
            }
            $stmt = $pdo->prepare("UPDATE matches SET result1 = ?, result2 = ?, penalties1 = ?, penalties2 = ?, status = 'finished' WHERE id = ?");
            $stmt->execute([$r1, $r2, $pen1, $pen2, $match_id]);
            $saved++;
        }
    }
    $success_msg = "Resultados actualizados correctamente.";
}

// Toggle Stage
if (isset($_POST['toggle_stage'])) {
    $stage_id = $_POST['stage_id'];
    $is_open = $_POST['is_open'] ? 0 : 1;
    
    $stmt = $pdo->prepare("UPDATE stages SET is_open = ? WHERE id = ?");
    $stmt->execute([$is_open, $stage_id]);
}

// Recalculate Points
if (isset($_POST['recalculate'])) {
    // Reset admins to 0
    $pdo->query("UPDATE users SET points = 0 WHERE is_admin = 1");
    // Basic logic: 3 pts exact, 1 pt winner
    $users = $pdo->query("SELECT id FROM users WHERE is_admin = 0")->fetchAll();
    foreach ($users as $u) {
        $total_points = 0;
        $stmt = $pdo->prepare("SELECT p.*, m.result1, m.result2, m.penalties1, m.penalties2, m.stage_id FROM predictions p JOIN matches m ON p.match_id = m.id WHERE p.user_id = ? AND m.status = 'finished'");
        $stmt->execute([$u['id']]);
        $preds = $stmt->fetchAll();
        
        foreach ($preds as $p) {
            $exact_match = ($p['score1'] == $p['result1'] && $p['score2'] == $p['result2']);
            $tendency_match = (($p['score1'] > $p['score2'] && $p['result1'] > $p['result2']) || 
                               ($p['score1'] < $p['score2'] && $p['result1'] < $p['result2']) || 
                               ($p['score1'] == $p['score2'] && $p['result1'] == $p['result2']));
            
            $went_to_penalties = ($p['result1'] == $p['result2'] && $p['penalties1'] !== null && $p['penalties2'] !== null);
            
            $pts_exact = 3;
            $pts_tendency = 1;
            $pts_penalty = 3;
            
            if ($p['stage_id'] == 5) { // Semifinales
                $pts_exact = 6;
                $pts_tendency = 2;
                $pts_penalty = 6;
            } elseif ($p['stage_id'] == 7) { // Final
                $pts_exact = 10;
                $pts_tendency = 3;
                $pts_penalty = 5;
            }
            
            if ($went_to_penalties) {
                if ($tendency_match) {
                    $total_points += $pts_tendency;
                    
                    $real_penalty_winner = ($p['penalties1'] > $p['penalties2']) ? 1 : 2;
                    $predicted_penalty_winner = 0;
                    if ($p['penalty_winner_team1']) $predicted_penalty_winner = 1;
                    elseif ($p['penalty_winner_team2']) $predicted_penalty_winner = 2;
                    
                    if ($predicted_penalty_winner == $real_penalty_winner) {
                        $total_points += $pts_penalty;
                    }
                }
            } else {
                if ($exact_match) {
                    $total_points += $pts_exact;
                } elseif ($tendency_match) {
                    $total_points += $pts_tendency;
                }
            }
        }
        
        $stmt = $pdo->prepare("UPDATE users SET points = ? WHERE id = ?");
        $stmt->execute([$total_points, $u['id']]);
    }
    $success_msg = "¡Puntos recalculados para todos los usuarios!";
}

$stages = $pdo->query("SELECT * FROM stages ORDER BY display_order ASC")->fetchAll();

render_header("Panel Administrativo", $user, "admin");
?>

        <?php if (isset($success_msg)): ?>
            <div style="background: rgba(34, 197, 94, 0.1); border: 1px solid var(--ios-success); color: var(--ios-success); padding: 1rem; border-radius: 12px; margin-bottom: 2rem;" class="animate-fade-in">
                <?php echo $success_msg; ?>
            </div>
        <?php endif; ?>

        <div style="display: grid; gap: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; background: var(--ios-card-bg); padding: 1.5rem; border-radius: 16px; border: 1px solid var(--ios-border);">
                <div>
                    
                    <p class="text-muted" style="margin: 0.5rem 0 0 0;">Gestión de resultados y puntajes.</p>
                </div>
                <form action="" method="POST" style="margin: 0;">
                    <button type="submit" name="recalculate" class="btn" style="width: auto;">RECALCULAR PUNTOS</button>
                </form>
            </div>
        </div>

        <!-- Stage Navigation -->
        <div class="tabs-container" style="margin-top: 2rem;">
            <?php foreach ($stages as $s): ?>
                <a href="?stage=<?php echo $s['id']; ?>" class="tab-link <?php echo $current_stage == $s['id'] ? 'active' : ''; ?>">
                    <?php echo htmlspecialchars($s['name']); ?>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Group Navigation (Sub-tabs) -->
        <?php if ($current_stage == 1 && !empty($available_groups)): ?>
            <div class="sub-tabs animate-fade-in">
                <?php foreach ($available_groups as $g): ?>
                    <a href="?stage=1&group=<?php echo $g; ?>" class="sub-tab-link <?php echo $current_group == $g ? 'active' : ''; ?>">
                        GRUPO <?php echo $g; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php 
        $current_stage_data = null;
        foreach ($stages as $s) if ($s['id'] == $current_stage) $current_stage_data = $s;
        ?>

        <section class="glass-card no-hover" style="margin-bottom: 2rem; margin-top: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid var(--ios-border);">
                <h3 style="margin: 0;">Partidos - <?php echo htmlspecialchars($current_stage_data['name']); ?> <?php if ($current_stage == 1 && $current_group) echo "Grupo $current_group"; ?></h3>
                <form action="" method="POST" style="margin: 0;">
                    <input type="hidden" name="stage_id" value="<?php echo $current_stage_data['id']; ?>">
                    <input type="hidden" name="is_open" value="<?php echo $current_stage_data['is_open']; ?>">
                    <button type="submit" name="toggle_stage" class="btn" style="width: auto; padding: 0.5rem 1rem; font-size: 0.8rem; background: <?php echo $current_stage_data['is_open'] ? 'var(--ios-error)' : 'var(--ios-success)'; ?>;">
                        <?php echo $current_stage_data['is_open'] ? 'CERRAR ETAPA' : 'ABRIR ETAPA'; ?>
                    </button>
                </form>
            </div>

            <form method="POST" style="display: grid; gap: 1rem;">
                <?php
                $query = "SELECT * FROM matches WHERE stage_id = ?";
                $params = [$current_stage];
                if ($current_stage == 1 && $current_group) {
                    $query .= " AND group_name = ?";
                    $params[] = $current_group;
                }
                $query .= " ORDER BY matchday ASC, match_date ASC";
                $stmt = $pdo->prepare($query);
                $stmt->execute($params);
                $matches = $stmt->fetchAll();
                
                $grouped_matches = [];
                foreach ($matches as $m) {
                    $day = $m['matchday'] ? "Fecha " . $m['matchday'] : "Partidos";
                    $grouped_matches[$day][] = $m;
                }
                
                if (empty($grouped_matches)):
                ?>
                    <div style="text-align: center; padding: 3rem; color: var(--ios-text-sec);">No hay partidos en esta etapa.</div>
                <?php else: ?>
                    <?php foreach ($grouped_matches as $day => $day_matches): ?>
                        <div style="font-weight: 600; color: var(--ios-blue); border-bottom: 1px solid var(--ios-border); padding-bottom: 0.5rem; margin-top: 1rem;">
                            <?php echo $day; ?>
                        </div>
                                               <?php foreach ($day_matches as $match): ?>
                            <div class="admin-match-wrapper" style="background: var(--ios-card-bg); border-radius: 12px; margin-bottom: 0.5rem;">
                                <div class="admin-match-row">
                                    <div class="admin-match-time" style="display:flex; flex-direction:column; justify-content:center; align-items:flex-start; min-width: 170px; padding-left: 0.5rem;">
                                        <input type="datetime-local" name="results[<?php echo $match['id']; ?>][match_date]" 
                                            value="<?php echo date('Y-m-d\TH:i', strtotime($match['match_date'])); ?>" 
                                            style="background: transparent; border: 1px dashed var(--ios-border); border-radius: 8px; padding: 0.4rem; font-size: 0.95rem; font-weight: 500; color: var(--ios-text); width: 100%; outline: none; font-family: inherit; cursor: pointer;">
                                    </div>
                                    
                                    <?php if ($match['group_name']): ?>
                                        <div class="admin-match-group">
                                            <?php echo htmlspecialchars($match['group_name']); ?>
                                        </div>
                                    <?php else: ?>
                                        <div style="width: 25px; display: none;" class="desktop-only-group"></div>
                                    <?php endif; ?>

                                    <div class="admin-team team-left" style="display:flex; align-items:center;">
                                        <?php if ($current_stage > 1): ?>
                                            <select name="results[<?php echo $match['id']; ?>][team1]" style="width:100%; border:1px solid transparent; border-bottom: 1px dashed var(--ios-border); background:transparent; font-weight:500; font-family:inherit; color:var(--text-color); outline:none; appearance:none;">
                                                <?php if (!in_array($match['team1'], $all_teams)): ?>
                                                    <option value="<?php echo htmlspecialchars($match['team1']); ?>"><?php echo htmlspecialchars($match['team1']); ?></option>
                                                <?php endif; ?>
                                                <optgroup label="Países del Torneo">
                                                    <?php foreach ($all_teams as $t): ?>
                                                        <option value="<?php echo htmlspecialchars($t); ?>" <?php echo $match['team1'] === $t ? 'selected' : ''; ?>><?php echo htmlspecialchars($t); ?></option>
                                                    <?php endforeach; ?>
                                                </optgroup>
                                            </select>
                                        <?php else: ?>
                                            <span style="width:100%; text-align:right; font-weight:500; padding-right:5px;"><?php echo htmlspecialchars($match['team1']); ?></span>
                                        <?php endif; ?>
                                        <?php $c1 = getFlagCode($match['team1']); if($c1): ?><img src="https://flagcdn.com/w20/<?php echo $c1; ?>.png" class="admin-flag" style="margin-left: 5px; flex-shrink: 0;"><?php endif; ?>
                                    </div>
                                    <input type="number" name="results[<?php echo $match['id']; ?>][result1]" class="score-input admin-score" value="<?php echo $match['result1']; ?>" min="0">
                                    <span class="admin-vs">-</span>
                                    <input type="number" name="results[<?php echo $match['id']; ?>][result2]" class="score-input admin-score" value="<?php echo $match['result2']; ?>" min="0">
                                    <div class="admin-team team-right" style="display:flex; align-items:center;">
                                        <?php $c2 = getFlagCode($match['team2']); if($c2): ?><img src="https://flagcdn.com/w20/<?php echo $c2; ?>.png" class="admin-flag" style="margin-right: 5px; flex-shrink: 0;"><?php endif; ?>
                                        <?php if ($current_stage > 1): ?>
                                            <select name="results[<?php echo $match['id']; ?>][team2]" style="width:100%; border:1px solid transparent; border-bottom: 1px dashed var(--ios-border); background:transparent; font-weight:500; font-family:inherit; color:var(--text-color); outline:none; appearance:none;">
                                                <?php if (!in_array($match['team2'], $all_teams)): ?>
                                                    <option value="<?php echo htmlspecialchars($match['team2']); ?>"><?php echo htmlspecialchars($match['team2']); ?></option>
                                                <?php endif; ?>
                                                <optgroup label="Países del Torneo">
                                                    <?php foreach ($all_teams as $t): ?>
                                                        <option value="<?php echo htmlspecialchars($t); ?>" <?php echo $match['team2'] === $t ? 'selected' : ''; ?>><?php echo htmlspecialchars($t); ?></option>
                                                    <?php endforeach; ?>
                                                </optgroup>
                                            </select>
                                        <?php else: ?>
                                            <span style="width:100%; text-align:left; font-weight:500; padding-left:5px;"><?php echo htmlspecialchars($match['team2']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php if ($current_stage > 1): ?>
                                <div class="penalties-container" style="display: <?php echo ($match['result1'] !== null && $match['result1'] === $match['result2']) ? 'flex' : 'none'; ?>; justify-content:center; align-items:center; gap:0.5rem; padding: 0.5rem; border-top:1px dashed var(--ios-border); background: rgba(0,0,0,0.02);">
                                    <span style="font-size:0.8rem; color:var(--ios-text-sec); font-weight: 600;">Definición por Penales:</span>
                                    <input type="number" name="results[<?php echo $match['id']; ?>][penalties1]" class="score-input" style="width:45px; height:35px; font-size:1rem;" value="<?php echo $match['penalties1']; ?>" min="0">
                                    <span class="admin-vs" style="font-size:0.9rem;">-</span>
                                    <input type="number" name="results[<?php echo $match['id']; ?>][penalties2]" class="score-input" style="width:45px; height:35px; font-size:1rem;" value="<?php echo $match['penalties2']; ?>" min="0">
                                </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                    
                    <div style="position: sticky; bottom: 2rem; text-align: right; margin-top: 1rem;">
                        <button type="submit" name="update_all_matches" class="btn" style="width: auto; padding: 1rem 3rem; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">GUARDAR RESULTADOS</button>
                    </div>
                <?php endif; ?>
            </form>
        </section>

<?php render_footer($user, "admin"); ?>

<script>
document.querySelectorAll('.admin-match-wrapper').forEach(wrapper => {
    const r1 = wrapper.querySelector('input[name$="[result1]"]');
    const r2 = wrapper.querySelector('input[name$="[result2]"]');
    const penContainer = wrapper.querySelector('.penalties-container');
    
    if (r1 && r2 && penContainer) {
        const updatePenalties = () => {
            if (r1.value !== '' && r2.value !== '' && r1.value === r2.value) {
                penContainer.style.display = 'flex';
            } else {
                penContainer.style.display = 'none';
            }
        };
        r1.addEventListener('input', updatePenalties);
        r2.addEventListener('input', updatePenalties);
    }
});
</script>
