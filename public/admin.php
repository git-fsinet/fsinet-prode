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

// Update Match Result
if (isset($_POST['update_match'])) {
    $match_id = $_POST['match_id'];
    $r1 = $_POST['result1'];
    $r2 = $_POST['result2'];
    
    if ($r1 === '' || $r2 === '') {
        $stmt = $pdo->prepare("UPDATE matches SET result1 = NULL, result2 = NULL, status = 'pending' WHERE id = ?");
        $stmt->execute([$match_id]);
        $success_msg = "Resultado borrado y partido devuelto a pendiente.";
    } else {
        $stmt = $pdo->prepare("UPDATE matches SET result1 = ?, result2 = ?, status = 'finished' WHERE id = ?");
        $stmt->execute([$r1, $r2, $match_id]);
        $success_msg = "Resultado guardado y partido finalizado.";
    }
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
    // Basic logic: 3 pts exact, 1 pt winner
    $users = $pdo->query("SELECT id FROM users WHERE is_admin = 0")->fetchAll();
    foreach ($users as $u) {
        $total_points = 0;
        $stmt = $pdo->prepare("SELECT p.*, m.result1, m.result2 FROM predictions p JOIN matches m ON p.match_id = m.id WHERE p.user_id = ? AND m.status = 'finished'");
        $stmt->execute([$u['id']]);
        $preds = $stmt->fetchAll();
        
        foreach ($preds as $p) {
            if ($p['score1'] == $p['result1'] && $p['score2'] == $p['result2']) {
                $total_points += 3;
            } elseif (($p['score1'] > $p['score2'] && $p['result1'] > $p['result2']) || 
                      ($p['score1'] < $p['score2'] && $p['result1'] < $p['result2']) || 
                      ($p['score1'] == $p['score2'] && $p['result1'] == $p['result2'])) {
                $total_points += 1;
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
            <h2>Acciones Globales</h2>
            <form action="" method="POST">
                <button type="submit" name="recalculate" class="btn" style="width: auto;">RECALCULAR PUNTOS</button>
            </form>
        </section>

        <?php foreach ($stages as $stage): ?>
            <section class="glass-card no-hover" style="margin-bottom: 2rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <h3><?php echo htmlspecialchars($stage['name']); ?></h3>
                    <form action="" method="POST">
                        <input type="hidden" name="stage_id" value="<?php echo $stage['id']; ?>">
                        <input type="hidden" name="is_open" value="<?php echo $stage['is_open']; ?>">
                        <button type="submit" name="toggle_stage" class="btn" style="width: auto; padding: 0.5rem 1rem; font-size: 0.75rem; background: <?php echo $stage['is_open'] ? 'var(--error)' : 'var(--success)'; ?>">
                            <?php echo $stage['is_open'] ? 'CERRAR ETAPA' : 'ABRIR ETAPA'; ?>
                        </button>
                    </form>
                </div>

                <div style="display: grid; gap: 1rem;">
                    <?php
                    $stmt = $pdo->prepare("SELECT * FROM matches WHERE stage_id = ? ORDER BY match_date ASC");
                    $stmt->execute([$stage['id']]);
                    $matches = $stmt->fetchAll();
                    
                    $grouped_matches = [];
                    foreach ($matches as $m) {
                        $date = date('Y-m-d', strtotime($m['match_date']));
                        $grouped_matches[$date][] = $m;
                    }
                    
                    foreach ($grouped_matches as $date => $day_matches): 
                    ?>
                        <div style="font-weight: 600; color: var(--ios-blue); border-bottom: 1px solid var(--ios-border); padding-bottom: 0.5rem; margin-top: 1rem;">
                            <?php echo date('d/m/Y', strtotime($date)); ?>
                        </div>
                        
                        <?php foreach ($day_matches as $match): ?>
                            <form action="" method="POST" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: var(--ios-input-bg); border: 1px solid var(--ios-border); border-radius: 12px;">
                                <input type="hidden" name="match_id" value="<?php echo $match['id']; ?>">
                                <div style="width: 40px; font-size: 0.85rem; color: var(--ios-text-sec); font-weight: 600;"><?php echo date('H:i', strtotime($match['match_date'])); ?></div>
                                
                                <?php if ($match['group_name']): ?>
                                    <div style="width: 25px; text-align: center; font-size: 0.75rem; font-weight: 700; color: white; background: var(--ios-blue); padding: 0.15rem 0.3rem; border-radius: 6px;">
                                        <?php echo htmlspecialchars($match['group_name']); ?>
                                    </div>
                                <?php else: ?>
                                    <div style="width: 25px;"></div>
                                <?php endif; ?>

                                <div style="flex: 1; display: flex; align-items: center; gap: 0.5rem; justify-content: flex-end;">
                                    <span style="font-weight: 600;"><?php echo htmlspecialchars($match['team1']); ?></span>
                                    <?php $c1 = getFlagCode($match['team1']); if($c1): ?><img src="https://flagcdn.com/w20/<?php echo $c1; ?>.png" style="width: 20px; border-radius: 2px;"><?php endif; ?>
                                </div>
                                <input type="number" name="result1" class="score-input" value="<?php echo $match['result1']; ?>" style="width: 50px; padding: 0.5rem; text-align: center;" min="0">
                                <span style="color: var(--ios-text-sec);">-</span>
                                <input type="number" name="result2" class="score-input" value="<?php echo $match['result2']; ?>" style="width: 50px; padding: 0.5rem; text-align: center;" min="0">
                                <div style="flex: 1; display: flex; align-items: center; gap: 0.5rem; justify-content: flex-start;">
                                    <?php $c2 = getFlagCode($match['team2']); if($c2): ?><img src="https://flagcdn.com/w20/<?php echo $c2; ?>.png" style="width: 20px; border-radius: 2px;"><?php endif; ?>
                                    <span style="font-weight: 600;"><?php echo htmlspecialchars($match['team2']); ?></span>
                                </div>
                                <button type="submit" name="update_match" class="btn" style="width: auto; padding: 0.5rem 1rem; font-size: 0.75rem;">OK</button>
                            </form>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>
        </div>

<?php render_footer($user, "admin"); ?>
