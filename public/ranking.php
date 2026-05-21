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

<?php render_footer($user, "ranking"); ?>
