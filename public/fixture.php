<?php
session_start();
require_once '../config/db.php';
require_once '../includes/layout.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Get user info
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Current selection from GET
$current_stage = isset($_GET['stage']) ? (int)$_GET['stage'] : 1;
$current_group = isset($_GET['group']) ? $_GET['group'] : 'A';

// Save predictions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['predictions'])) {
    $saved = 0;
    foreach ($_POST['predictions'] as $match_id => $scores) {
        $score1 = $scores['score1'];
        $score2 = $scores['score2'];

        if ($score1 !== '' && $score2 !== '') {
            // Get match date and stage open status
            $stmt = $pdo->prepare("SELECT m.match_date, m.status, m.stage_id, s.is_open FROM matches m JOIN stages s ON m.stage_id = s.id WHERE m.id = ?");
            $stmt->execute([$match_id]);
            $match_info = $stmt->fetch();

            if ($match_info && $match_info['is_open'] && $match_info['status'] !== 'finished') {
                // Get the first match date of this stage
                $stmt_fm = $pdo->prepare("SELECT MIN(match_date) as first_match_date FROM matches WHERE stage_id = ?");
                $stmt_fm->execute([$match_info['stage_id']]);
                $fm_info = $stmt_fm->fetch();
                $first_match_date = ($fm_info && $fm_info['first_match_date']) ? $fm_info['first_match_date'] : $match_info['match_date'];

                // Check 2-hour deadline for the entire stage
                $deadline = strtotime($first_match_date) - 7200;
                if (time() <= $deadline) {
                    // UPSERT: insert or update
                    $stmt = $pdo->prepare("SELECT id FROM predictions WHERE user_id = ? AND match_id = ?");
                    $stmt->execute([$user_id, $match_id]);
                    $exists = $stmt->fetch();

                    if ($exists) {
                        $stmt = $pdo->prepare("UPDATE predictions SET score1 = ?, score2 = ? WHERE user_id = ? AND match_id = ?");
                        $stmt->execute([$score1, $score2, $user_id, $match_id]);
                    } else {
                        $stmt = $pdo->prepare("INSERT INTO predictions (user_id, match_id, score1, score2) VALUES (?, ?, ?, ?)");
                        $stmt->execute([$user_id, $match_id, $score1, $score2]);
                    }
                    $saved++;
                }
            }
        }
    }
    $success_msg = $saved > 0 ? "¡Pronósticos guardados correctamente!" : "No se pudieron guardar: el plazo ya cerró para esos partidos.";
}

// Get Stages for Tab Navigation
$stages = $pdo->query("SELECT * FROM stages ORDER BY display_order ASC")->fetchAll();

// Get first match date of each stage
$stage_first_matches = [];
$stmt = $pdo->query("SELECT stage_id, MIN(match_date) as first_match_date FROM matches GROUP BY stage_id");
foreach ($stmt->fetchAll() as $row) {
    if ($row['first_match_date']) {
        $stage_first_matches[$row['stage_id']] = $row['first_match_date'];
    }
}

// Get Available Groups for the selected stage (if it's Group Stage)
$available_groups = [];
if ($current_stage == 1) { // Assuming 1 is Group Stage
    $available_groups = $pdo->query("SELECT DISTINCT group_name FROM matches WHERE stage_id = 1 AND group_name IS NOT NULL ORDER BY group_name ASC")->fetchAll(PDO::FETCH_COLUMN);
}

// Get Predictions for this user
$stmt = $pdo->prepare("SELECT match_id, score1, score2 FROM predictions WHERE user_id = ?");
$stmt->execute([$user_id]);
$predictions = [];
foreach ($stmt->fetchAll() as $p) {
    $predictions[$p['match_id']] = $p;
}

// Build the query for matches
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



// Group matches by matchday
$grouped_matches = [];
foreach ($matches as $m) {
    $day = $m['matchday'] ? "Fecha " . $m['matchday'] : "Partidos";
    $grouped_matches[$day][] = $m;
}

// Render Header
render_header("Fixture", $user, "fixture");
?>

        <!-- Stage Navigation -->
        <div class="tabs-container">
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

        <?php if (isset($success_msg)): ?>
            <div style="background: rgba(34, 197, 94, 0.1); border: 1px solid var(--success); color: #4ade80; padding: 1rem; border-radius: 12px; margin-bottom: 2rem;" class="animate-fade-in">
                <?php echo $success_msg; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
        
        <?php 
        $current_stage_open = false;
        foreach ($stages as $s) if ($s['id'] == $current_stage) $current_stage_open = $s['is_open'];
        $current_stage_first_match = isset($stage_first_matches[$current_stage]) ? $stage_first_matches[$current_stage] : null;
        $stage_deadline = $current_stage_first_match ? strtotime($current_stage_first_match) - 7200 : 0;
        $stage_deadline_passed = time() >= $stage_deadline;
        ?>
        <?php if ($current_stage_open && !$stage_deadline_passed && $stage_deadline > 0): ?>
            <div class="glass-card animate-fade-in" style="margin-bottom: 2rem; text-align: center; background: linear-gradient(135deg, var(--ios-blue), #5e5ce6); color: white; padding: 1.5rem; border: none; box-shadow: 0 10px 30px rgba(12, 74, 211, 0.3);">
                <h3 style="margin-top: 0; font-size: 1rem; font-weight: 600; opacity: 0.9;">Tiempo límite para cargar esta etapa:</h3>
                <div id="countdown-banner" data-deadline="<?php echo $stage_deadline; ?>" style="font-size: 1.5rem; font-weight: 600; font-variant-numeric: tabular-nums; letter-spacing: 2px; margin-top: 0.5rem; text-shadow: 0 2px 10px rgba(0,0,0,0.2);">
                    --:--:--
                </div>
            </div>
            <script>
                const serverTimeAtLoad = <?php echo time(); ?>;
                const clientTimeAtLoad = performance.now();

                function updateCountdown() {
                    const banner = document.getElementById('countdown-banner');
                    if (!banner) return;
                    const deadline = parseInt(banner.getAttribute('data-deadline'), 10);
                    
                    // Calculate current server time without relying on the system clock
                    const elapsedSecs = Math.floor((performance.now() - clientTimeAtLoad) / 1000);
                    const currentServerTime = serverTimeAtLoad + elapsedSecs;
                    const diff = deadline - currentServerTime;
                    
                    if (diff <= 0) {
                        banner.innerHTML = "00d 00h 00m 00s";
                        location.reload();
                        return;
                    }
                    
                    const d = Math.floor(diff / 86400);
                    const h = Math.floor((diff % 86400) / 3600);
                    const m = Math.floor((diff % 3600) / 60);
                    const s = diff % 60;
                    
                    banner.innerHTML = 
                        (d < 10 ? '0' + d : d) + 'd ' +
                        (h < 10 ? '0' + h : h) + 'h ' + 
                        (m < 10 ? '0' + m : m) + 'm ' + 
                        (s < 10 ? '0' + s : s) + 's';
                }
                setInterval(updateCountdown, 1000);
                updateCountdown();
            </script>
        <?php endif; ?>
            <?php if (empty($grouped_matches)): ?>
                <div class="glass-card" style="text-align: center; padding: 4rem;">
                    <p style="color: var(--text-muted);">Próximamente... Los partidos de esta etapa aún no han sido definidos.</p>
                </div>
            <?php else: ?>
                <?php foreach ($grouped_matches as $fecha => $match_list): ?>
                    <div class="fecha-divider animate-fade-in"><?php echo $fecha; ?></div>
                    
                    <div class="matches-grid" style="margin-bottom: 2rem;">
                        <?php foreach ($match_list as $match): 
                            $pred = isset($predictions[$match['id']]) ? $predictions[$match['id']] : null;
                            $is_open = false;
                            foreach ($stages as $s) if ($s['id'] == $match['stage_id']) $is_open = $s['is_open'];
                            // Lock if: stage closed, match finished, OR less than 2 hours to stage kickoff
                            $first_match_date = isset($stage_first_matches[$match['stage_id']]) ? $stage_first_matches[$match['stage_id']] : $match['match_date'];
                            $deadline_timestamp = strtotime($first_match_date) - 7200;
                            $deadline_passed = time() > $deadline_timestamp;
                            $is_locked = !$is_open || $match['status'] === 'finished' || $deadline_passed;
                            // Time remaining label
                            $secs_remaining = $deadline_timestamp - time();
                            $show_countdown = (!$is_locked || $deadline_passed) && $match['status'] !== 'finished';
                        ?>
                            <div class="glass-card match-card <?php echo $is_locked ? 'locked' : ''; ?> animate-fade-in">
                                <div class="match-card-content">
                                        <div class="team-info">
                                            <?php $code1 = getFlagCode($match['team1']); ?>
                                            <?php if ($code1): ?>
                                                <img src="https://flagcdn.com/w40/<?php echo $code1; ?>.png" alt="" class="flag">
                                            <?php endif; ?>
                                            <span><?php echo htmlspecialchars($match['team1']); ?></span>
                                        </div>

                                        <div class="score-inputs">
                                            <?php if ($is_locked && (!$pred || $pred['score1'] === null || $pred['score1'] === '')): ?>
                                                <input type="text" class="score-input" value="-" readonly style="text-align:center; color:var(--text-muted);">
                                            <?php else: ?>
                                                <input type="number" 
                                                       name="predictions[<?php echo $match['id']; ?>][score1]" 
                                                       class="score-input" 
                                                       value="<?php echo $pred ? $pred['score1'] : ''; ?>"
                                                       <?php echo $is_locked ? 'readonly' : ''; ?>
                                                       min="0" max="20">
                                            <?php endif; ?>
                                            <span class="vs">VS</span>
                                            <?php if ($is_locked && (!$pred || $pred['score2'] === null || $pred['score2'] === '')): ?>
                                                <input type="text" class="score-input" value="-" readonly style="text-align:center; color:var(--text-muted);">
                                            <?php else: ?>
                                                <input type="number" 
                                                       name="predictions[<?php echo $match['id']; ?>][score2]" 
                                                       class="score-input" 
                                                       value="<?php echo $pred ? $pred['score2'] : ''; ?>"
                                                       <?php echo $is_locked ? 'readonly' : ''; ?>
                                                       min="0" max="20">
                                            <?php endif; ?>
                                        </div>

                                        <div class="team-info right">
                                            <span><?php echo htmlspecialchars($match['team2']); ?></span>
                                            <?php $code2 = getFlagCode($match['team2']); ?>
                                            <?php if ($code2): ?>
                                                <img src="https://flagcdn.com/w40/<?php echo $code2; ?>.png" alt="" class="flag">
                                            <?php endif; ?>
                                        </div>
                                </div>
                                
                                <div class="match-meta">
                                    <span style="display:inline-flex;align-items:center;gap:0.3rem;"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg> <?php echo date('d/m H:i', strtotime($match['match_date'])); ?>hs</span>
                                    <span style="display:inline-flex;align-items:center;gap:0.3rem;"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg> <?php echo htmlspecialchars($match['stadium']); ?></span>
                                    <?php if ($deadline_passed && $match['status'] !== 'finished'): ?>
                                        <span style="display:inline-flex;align-items:center;gap:0.3rem;color:var(--ios-error);font-weight:600;"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg> Plazo cerrado</span>
                                    <?php elseif (!$is_locked && $secs_remaining > 0 && $secs_remaining < 86400): ?>
                                        <?php 
                                            $hrs = floor($secs_remaining / 3600);
                                            $mins = floor(($secs_remaining % 3600) / 60);
                                            $time_label = $hrs > 0 ? "{$hrs}h {$mins}m" : "{$mins}m";
                                        ?>
                                        <span style="display:inline-flex;align-items:center;gap:0.3rem;color:var(--ios-success);font-weight:600;"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg> Cierra en <?php echo $time_label; ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>

                <?php 
                // Show save button if any match in this view is still editable (stage open + deadline not passed + not finished)
                $any_open = false;
                foreach ($matches as $m) {
                    $m_open = false;
                    foreach ($stages as $s) if ($s['id'] == $m['stage_id']) $m_open = $s['is_open'];
                    $m_first_match_date = isset($stage_first_matches[$m['stage_id']]) ? $stage_first_matches[$m['stage_id']] : $m['match_date'];
                    $m_deadline_passed = time() > (strtotime($m_first_match_date) - 7200);
                    if ($m_open && !$m_deadline_passed && $m['status'] !== 'finished') { $any_open = true; break; }
                }
                if ($any_open): ?>
                    <div style="position: sticky; bottom: 2rem; text-align: right;">
                        <button type="submit" class="btn" style="width: auto; padding: 1rem 3rem; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">GUARDAR PRONÓSTICOS</button>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </form>

<?php render_footer($user, "fixture"); ?>
