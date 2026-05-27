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

// Get all users for ranking (exclude admins)
$ranking_stmt = $pdo->query("SELECT * FROM users WHERE is_admin = 0 ORDER BY points DESC, id ASC");
$all_users = $ranking_stmt->fetchAll();

// Get Last Finished Matches and everyone's predictions
$last_day_stmt = $pdo->query("SELECT DISTINCT DATE(match_date) as mdate FROM matches WHERE status = 'finished' ORDER BY mdate DESC LIMIT 1");
$last_day = $last_day_stmt->fetch();

$recent_matches = [];
$recent_date_str = "";
$match_predictions = [];

if ($last_day) {
    $recent_date_str = $last_day['mdate'];
    $stmt = $pdo->prepare("SELECT * FROM matches WHERE DATE(match_date) = ? AND status = 'finished' ORDER BY match_date ASC");
    $stmt->execute([$recent_date_str]);
    $recent_matches = $stmt->fetchAll();
    
    if (!empty($recent_matches)) {
        $match_ids = array_map(function($m) { return $m['id']; }, $recent_matches);
        $placeholders = implode(',', array_fill(0, count($match_ids), '?'));
        
        $stmt = $pdo->prepare("
            SELECT p.*, u.full_name 
            FROM predictions p 
            JOIN users u ON p.user_id = u.id 
            WHERE p.match_id IN ($placeholders) AND u.is_admin = 0
        ");
        $stmt->execute($match_ids);
        foreach ($stmt->fetchAll() as $p) {
            $match_predictions[$p['match_id']][$p['user_id']] = $p;
        }
    }
}

render_header("Ranking Global", $user, "ranking");
?>

<div class="glass-card animate-fade-in" style="padding: 0; overflow: hidden;">
    <div style="padding: 2rem; border-bottom: 1px solid var(--ios-border);">
        <h2 style="margin-bottom: 0;">Tabla de Posiciones</h2>
        <p class="text-muted" style="margin-top: 0.5rem;">Competencia interna de FSInet</p>
    </div>
    
    <div style="padding: 1rem 2rem 2rem; overflow-x: auto;">
        <table class="ranking-table">
            <thead>
                <tr>
                    <th style="width: 50px;">Pos</th>
                    <th>Participante</th>
                    <th>Tipo</th>
                    <th style="text-align: right;">Puntos</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $rank = 1;
                foreach ($all_users as $u): 
                    $is_current_user = ($u['id'] == $user_id);
                ?>
                <tr style="<?php echo $is_current_user ? 'background: rgba(0,122,255,0.05);' : ''; ?>">
                    <td>
                        <span class="rank-number">#<?php echo $rank; ?></span>
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
                    <td>
                        <?php if (!empty($u['is_fan'])): ?>
                            <span title="Futbolero" style="color: var(--ios-blue); display:inline-flex;"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg></span>
                        <?php else: ?>
                            <span title="Solo por el Mundial" style="opacity: 0.5; display:inline-flex;"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="8" y1="15" x2="16" y2="15"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg></span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $u['points']; ?></td>
                </tr>
                <?php 
                $rank++;
                endforeach; 
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php if (!empty($recent_matches)): ?>
    <h2 style="margin-top: 3rem; margin-bottom: 1.5rem; text-align: center;">Resultados del Último Día</h2>
    <div class="matches-grid" style="margin-bottom: 2rem;">
        <?php foreach ($recent_matches as $match): ?>
            <div class="glass-card match-card animate-fade-in" style="opacity: 0.9; cursor: default; display: flex; flex-direction: column;">
                <div class="match-card-content" style="width: 100%;">
                    <div style="display:flex; align-items:center; justify-content:space-between; width:100%;">
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

                <div style="width: 100%; border-top: 1px solid var(--ios-border); margin-top: 1rem; padding-top: 1rem;">
                    <h4 class="text-muted" style="margin-bottom: 0.75rem; font-size: 0.85rem; text-transform: uppercase;">Pronósticos de los Participantes</h4>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <?php 
                        foreach ($all_users as $u): 
                            $pred = isset($match_predictions[$match['id']][$u['id']]) ? $match_predictions[$match['id']][$u['id']] : null;
                            $has_pred = ($pred && $pred['score1'] !== null && $pred['score2'] !== null && $pred['score1'] !== '');
                            $points = 0;
                            $pred_text = "No cargó";
                            
                            if ($has_pred) {
                                $pred_text = $pred['score1'] . ' - ' . $pred['score2'];
                                if ($pred['score1'] === $pred['score2'] && (isset($pred['penalty_winner_team1']) && $pred['penalty_winner_team1'] || isset($pred['penalty_winner_team2']) && $pred['penalty_winner_team2'])) {
                                    $pred_text .= ' (P)';
                                }
                                
                                $exact_match = ($pred['score1'] == $match['result1'] && $pred['score2'] == $match['result2']);
                                $pred_diff = $pred['score1'] - $pred['score2'];
                                $actual_diff = $match['result1'] - $match['result2'];
                                $pred_sign = $pred_diff > 0 ? 1 : ($pred_diff < 0 ? -1 : 0);
                                $actual_sign = $actual_diff > 0 ? 1 : ($actual_diff < 0 ? -1 : 0);
                                $tendency_match = ($pred_sign === $actual_sign);

                                $went_to_penalties = ($match['result1'] === $match['result2'] && $match['penalties1'] !== null && $match['penalties2'] !== null);
                                
                                $pts_exact = 3; $pts_tendency = 1; $pts_penalty = 3;
                                if ($match['stage_id'] == 5) { $pts_exact = 6; $pts_tendency = 2; $pts_penalty = 6; }
                                elseif ($match['stage_id'] == 7) { $pts_exact = 10; $pts_tendency = 3; $pts_penalty = 5; }

                                if ($went_to_penalties) {
                                    if ($tendency_match) {
                                        $real_penalty_winner = ($match['penalties1'] > $match['penalties2']) ? 1 : 2;
                                        $predicted_penalty_winner = 0;
                                        if (isset($pred['penalty_winner_team1']) && $pred['penalty_winner_team1']) $predicted_penalty_winner = 1;
                                        elseif (isset($pred['penalty_winner_team2']) && $pred['penalty_winner_team2']) $predicted_penalty_winner = 2;
                                        
                                        if ($predicted_penalty_winner == $real_penalty_winner) {
                                            $points = $pts_tendency + $pts_penalty;
                                        } else {
                                            $points = $pts_tendency;
                                        }
                                    }
                                } else {
                                    if ($exact_match) {
                                        $points = $pts_exact;
                                    } elseif ($tendency_match) {
                                        $points = $pts_tendency;
                                    }
                                }
                            }
                        ?>
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem; background: var(--ios-bg); border-radius: 8px;">
                            <span style="font-size: 0.9rem; font-weight: 600; color: var(--ios-text); display: flex; align-items: center; gap: 0.5rem;">
                                <?php echo htmlspecialchars($u['full_name']); ?>
                            </span>
                            <div style="display: flex; align-items: center; gap: 1rem; font-size: 0.9rem;">
                                <span style="color: var(--ios-text-sec); opacity: <?php echo $has_pred ? '1' : '0.5'; ?>;"><?php echo $pred_text; ?></span>
                                <span style="font-weight: 700; width: 60px; text-align: right; color: <?php echo $points > 0 ? 'var(--ios-success)' : 'var(--ios-text-sec)'; ?>; opacity: <?php echo $has_pred ? '1' : '0.5'; ?>;">+<?php echo $points; ?> pts</span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php render_footer($user, "ranking"); ?>
