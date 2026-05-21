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

// Get user stats
$stmt = $pdo->prepare("SELECT COUNT(*) as total_preds FROM predictions WHERE user_id = ?");
$stmt->execute([$user_id]);
$stats = $stmt->fetch();

// Get Top 3 users for Podium (exclude admins)
$podium_stmt = $pdo->query("SELECT id, full_name, points FROM users WHERE is_admin = 0 ORDER BY points DESC, id ASC LIMIT 3");
$top_users = $podium_stmt->fetchAll();

// Get Upcoming Matches (Next 3 Days)
$upcoming_stmt = $pdo->query("SELECT * FROM matches WHERE status = 'pending' ORDER BY match_date ASC");
$all_upcoming = $upcoming_stmt->fetchAll();

$upcoming_by_date = [];
$distinct_days = 0;
foreach ($all_upcoming as $m) {
    $date = date('Y-m-d', strtotime($m['match_date']));
    if (!isset($upcoming_by_date[$date])) {
        if ($distinct_days >= 3) break;
        $upcoming_by_date[$date] = [];
        $distinct_days++;
    }
    $upcoming_by_date[$date][] = $m;
}

// Get Last Finished Matches
$last_day_stmt = $pdo->query("SELECT DISTINCT DATE(match_date) as mdate FROM matches WHERE status = 'finished' ORDER BY mdate DESC LIMIT 1");
$last_day = $last_day_stmt->fetch();

$recent_matches = [];
$recent_date_str = "";
if ($last_day) {
    $recent_date_str = $last_day['mdate'];
    $stmt = $pdo->prepare("SELECT * FROM matches WHERE DATE(match_date) = ? AND status = 'finished' ORDER BY match_date ASC");
    $stmt->execute([$recent_date_str]);
    $recent_matches = $stmt->fetchAll();
}

render_header("Resumen", $user, "resumen");
?>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
    <!-- Stats Cards -->
    <div class="glass-card">
        <h3 class="text-muted" style="font-size: 0.9rem; text-transform: uppercase; margin-bottom: 0.5rem;">Tus Puntos</h3>
        <div style="font-size: 2.5rem; font-weight: 700; color: var(--ios-blue);"><?php echo $user['points']; ?></div>
    </div>
    
    <div class="glass-card">
        <h3 class="text-muted" style="font-size: 0.9rem; text-transform: uppercase; margin-bottom: 0.5rem;">Pronósticos</h3>
        <div style="font-size: 2.5rem; font-weight: 700; color: var(--ios-text);"><?php echo $stats['total_preds']; ?></div>
    </div>
</div>

<?php if (!empty($recent_matches)): ?>
    <h2 style="margin-top: 3rem; margin-bottom: 1.5rem;">Últimos Resultados</h2>
    <h3 class="fecha-divider animate-fade-in" style="margin: 1.5rem 0 1rem; font-size: 1.1rem;"><?php echo date('d/m/Y', strtotime($recent_date_str)); ?></h3>
    <div class="matches-grid" style="margin-bottom: 2rem;">
        <?php foreach ($recent_matches as $match): ?>
            <div class="glass-card match-card animate-fade-in" style="opacity: 0.9; cursor: default;">
                <div class="match-card-content">
                    <div class="team-info">
                        <?php $code1 = getFlagCode($match['team1']); ?>
                        <?php if ($code1): ?>
                            <img src="https://flagcdn.com/w40/<?php echo $code1; ?>.png" alt="" class="flag">
                        <?php endif; ?>
                        <span style="font-size: 1.1rem; font-weight: <?php echo $match['result1'] > $match['result2'] ? '700' : '500'; ?>; color: var(--ios-text);"><?php echo htmlspecialchars($match['team1']); ?></span>
                    </div>
                    
                    <div style="display: flex; align-items: center; justify-content: center; background: var(--ios-text); color: white; padding: 0.5rem 1rem; border-radius: 12px; font-weight: 700; font-size: 1.2rem; min-width: 80px; text-align: center;">
                        <?php echo $match['result1']; ?> - <?php echo $match['result2']; ?>
                    </div>
                    
                    <div class="team-info right">
                        <span style="font-size: 1.1rem; font-weight: <?php echo $match['result2'] > $match['result1'] ? '700' : '500'; ?>; color: var(--ios-text);"><?php echo htmlspecialchars($match['team2']); ?></span>
                        <?php $code2 = getFlagCode($match['team2']); ?>
                        <?php if ($code2): ?>
                            <img src="https://flagcdn.com/w40/<?php echo $code2; ?>.png" alt="" class="flag">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<h2 style="margin-top: 3rem; margin-bottom: 1.5rem;">Próximos Partidos</h2>
<?php if (empty($upcoming_by_date)): ?>
    <div class="glass-card text-muted" style="text-align: center; padding: 2rem;">
        No hay partidos programados.
    </div>
<?php else: ?>
    <?php foreach ($upcoming_by_date as $date => $matches): ?>
        <h3 class="fecha-divider animate-fade-in" style="margin: 1.5rem 0 1rem; font-size: 1.1rem;"><?php echo date('d/m/Y', strtotime($date)); ?></h3>
        <div class="matches-grid" style="margin-bottom: 2rem;">
            <?php foreach ($matches as $match): ?>
                <div class="glass-card match-card animate-fade-in" style="opacity: 0.8; cursor: default;">
                    <div class="match-card-content">
                        <div class="team-info">
                            <?php $code1 = getFlagCode($match['team1']); ?>
                            <?php if ($code1): ?>
                                <img src="https://flagcdn.com/w40/<?php echo $code1; ?>.png" alt="" class="flag">
                            <?php endif; ?>
                            <span><?php echo htmlspecialchars($match['team1']); ?></span>
                        </div>
                        <div class="score-inputs" style="font-weight: 600; color: var(--ios-text-sec);">
                            <?php echo date('H:i', strtotime($match['match_date'])); ?>hs
                        </div>
                        <div class="team-info right">
                            <span><?php echo htmlspecialchars($match['team2']); ?></span>
                            <?php $code2 = getFlagCode($match['team2']); ?>
                            <?php if ($code2): ?>
                                <img src="https://flagcdn.com/w40/<?php echo $code2; ?>.png" alt="" class="flag">
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<h2 style="text-align: center; margin-top: 3rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">Podio del Día <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#0C4AD3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg></h2>
<p class="text-muted" style="text-align: center; margin-bottom: 2rem;">Los 3 empleados con más puntos en la general.</p>

<div class="glass-card animate-fade-in" style="padding: 0; overflow: hidden; max-width: 600px; margin: 0 auto;">
    <div style="padding: 1.5rem 2rem; overflow-x: auto;">
        <table class="ranking-table" style="margin-bottom: 0;">
            <thead>
                <tr>
                    <th style="width: 50px;">Pos</th>
                    <th>Participante</th>
                    <th style="text-align: right;">Puntos</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $rank = 1;
                foreach ($top_users as $u): 
                    $is_current_user = ($u['id'] == $user_id);
                ?>
                <tr style="<?php echo $is_current_user ? 'background: rgba(0,122,255,0.05);' : ''; ?>">
                    <td>
                        <span class="rank-number" style="font-size: 1.2rem; color: <?php echo $rank == 1 ? '#FFD700' : ($rank == 2 ? '#C0C0C0' : '#CD7F32'); ?>;">#<?php echo $rank; ?></span>
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div class="user-avatar" style="width: 32px; height: 32px; font-size: 0.9rem; <?php echo $is_current_user ? 'background: var(--ios-blue);' : 'background: var(--ios-text-sec);'; ?>">
                                <?php echo strtoupper(substr($u['full_name'], 0, 1)); ?>
                            </div>
                            <span style="font-weight: <?php echo $is_current_user ? '700' : '600'; ?>; color: var(--ios-text);">
                                <?php echo htmlspecialchars($u['full_name']); ?>
                                <?php if ($is_current_user) echo ' <span class="badge badge-open" style="margin-left:0.5rem; padding: 0.15rem 0.5rem;">Tú</span>'; ?>
                            </span>
                        </div>
                    </td>
                    <td style="font-weight: 700; color: var(--ios-text); font-size: 1.1rem;"><?php echo $u['points']; ?></td>
                </tr>
                <?php 
                $rank++;
                endforeach; 
                ?>
            </tbody>
        </table>
    </div>
</div>

<div style="text-align: center; margin-top: 3rem;">
    <a href="fixture.php" class="btn" style="width: auto; padding: 1rem 3rem;">Completar Fixture</a>
</div>

<?php render_footer($user, "resumen"); ?>
