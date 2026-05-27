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

// Actions
$msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'reset') {
            // Reset dates to future (Open Stage)
            $pdo->query("UPDATE matches SET match_date = DATE_ADD(NOW(), INTERVAL 5 DAY), status = 'pending', result1 = NULL, result2 = NULL");
            $pdo->query("UPDATE stages SET is_open = 1");
            $pdo->query("UPDATE users SET points = 0");
            $msg = "Sistema reiniciado. Todos los partidos fueron movidos a 5 días en el futuro y sus resultados limpiados. Etapa abierta.";
        }

        if ($action === 'bots') {
            // Create bots
            $pdo->query("INSERT IGNORE INTO users (email, pin, full_name, is_admin, is_fan, points) VALUES 
                ('pedro@test.com', '123', 'Pedro Prueba', 0, 1, 0),
                ('maria@test.com', '123', 'María Simulacro', 0, 0, 0)");
            
            // Get bots
            $bots = $pdo->query("SELECT id FROM users WHERE email IN ('pedro@test.com', 'maria@test.com')")->fetchAll();
            // Get matches from OPEN stages only
            $matches = $pdo->query("SELECT m.id FROM matches m JOIN stages s ON m.stage_id = s.id WHERE s.is_open = 1")->fetchAll();
            
            foreach ($bots as $bot) {
                foreach ($matches as $m) {
                    $s1 = rand(0, 3);
                    $s2 = rand(0, 3);
                    $pdo->query("INSERT INTO predictions (user_id, match_id, score1, score2) VALUES ({$bot['id']}, {$m['id']}, $s1, $s2) ON DUPLICATE KEY UPDATE score1=$s1, score2=$s2");
                }
            }
            $msg = "Pronósticos cargados para Pedro y María en todos los partidos de las etapas que están actualmente ABIERTAS.";
        }

        if ($action === 'fastforward') {
            // Make the first match of stage 1 start in 1 hour (so deadline passed 1 hr ago since deadline is 2 hrs before)
            $pdo->query("UPDATE matches SET match_date = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE stage_id = 1");
            $msg = "¡Máquina del tiempo activada! ⏳ Los partidos de la Etapa 1 fueron adelantados. La etapa acaba de cerrarse porque ya pasó el tiempo límite de 2 horas antes del primer partido.";
        }
    }
}

render_header("Simulador", $user, "simulador");
?>

<div class="animate-fade-in" style="max-width: 600px; margin: 2rem auto; padding: 2rem;">
    <div style="text-align: center; margin-bottom: 2rem;">
        <div style="display:inline-flex; align-items:center; justify-content:center; width: 80px; height: 80px; background: linear-gradient(135deg, rgba(255, 149, 0, 0.1), rgba(255, 59, 48, 0.1)); color: var(--ios-orange); border-radius: 24px; margin-bottom: 1rem; box-shadow: 0 10px 20px rgba(0,0,0,0.05);">
            <i class="ph-duotone ph-rocket" style="font-size: 42px;"></i>
        </div>
        <h2 style="margin-bottom: 0.5rem; font-size: 2rem;">Panel de Simulación</h2>
        <p class="text-muted">Utiliza estos controles para probar el ciclo completo de vida del Prode.</p>
    </div>
    
    <?php if ($msg): ?>
        <div style="background: rgba(34, 197, 94, 0.1); border: 1px solid var(--success); color: #6cbc2aff; padding: 1rem; border-radius: 12px; margin-bottom: 2rem; font-weight: 500; text-align: center;">
            <i class="ph-fill ph-check-circle" style="font-size: 20px; vertical-align: middle; margin-right: 0.5rem;"></i>
            <?php echo $msg; ?>
        </div>
    <?php endif; ?>

    <div class="glass-card" style="padding: 2rem;">
        <form method="POST" style="display: flex; flex-direction: column; gap: 1rem;">
            
            <div style="padding-bottom: 1rem; border-bottom: 1px solid var(--ios-border);">
                <h3 style="font-size: 1.1rem; margin-top: 0; margin-bottom: 1rem;">1. Preparación</h3>
                <button type="submit" name="action" value="reset" class="btn" style="width: 100%; margin-bottom: 0.5rem; background: var(--ios-blue);">
                    🔄 Limpiar DB y Mover a futuro
                </button>
                <button type="submit" name="action" value="bots" class="btn" style="width: 100%; background: var(--ios-blue);">
                    🤖 Generar Bots con Pronósticos
                </button>
            </div>
            
            <div style="padding: 1rem 0; border-bottom: 1px solid var(--ios-border); text-align: center;">
                <h3 style="font-size: 1.1rem; margin-top: 0; margin-bottom: 0.5rem;">2. Tu Turno</h3>
                <p style="font-size: 0.95rem; color: var(--ios-text-sec); margin: 0;">Ve al <strong><a href="fixture.php" style="color: var(--ios-blue); text-decoration: none; font-weight: 600;">Fixture</a></strong> y guarda tus propios pronósticos como si fueras un jugador normal.</p>
            </div>

            <div style="padding-top: 1rem;">
                <h3 style="font-size: 1.1rem; margin-top: 0; margin-bottom: 1rem;">3. Cierre de Etapa</h3>
                <button type="submit" name="action" value="fastforward" class="btn" style="width: 100%; background: var(--ios-error); border-color: var(--ios-error);">
                    ⏳ Adelantar Tiempo (Cerrar Etapa)
                </button>
                <p style="font-size: 0.85rem; color: var(--ios-text-sec); text-align: center; margin-top: 0.5rem;">Simula que llegó la hora límite. Luego de esto, entra como <strong>Admin</strong> y carga los resultados reales.</p>
            </div>

        </form>
    </div>
</div>

<?php render_footer($user, "simulador"); ?>
