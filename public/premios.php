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

render_header("Premios", $user, "premios");
?>

<div class="glass-card animate-fade-in" style="margin: 0 auto; padding: 3rem 2rem;">
    <div style="text-align: center; margin-bottom: 3rem;">
        <div style="display:inline-flex; align-items:center; justify-content:center; width: 72px; height: 72px; background: linear-gradient(135deg, rgba(12, 74, 211, 0.1), rgba(94, 92, 230, 0.1)); color: var(--ios-blue); border-radius: 20px; margin-bottom: 1.5rem;">
            <i class="ph-fill ph-gift" style="font-size: 36px;"></i>
        </div>
        <h2 style="font-size: 2rem; margin-bottom: 0.5rem;">Premios</h2>

    </div>

    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <!-- 1er Puesto -->
        <div style="background: var(--ios-card-bg); border-left: 5px solid #ffc400ff; padding: 2rem; border-radius: 0 16px 16px 0; display: flex; align-items: center; gap: 2rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03); transition: transform 0.2s ease; cursor: default;" onmouseover="this.style.transform='translateX(5px)'" onmouseout="this.style.transform='translateX(0)'">
            <div style="font-size: 3rem; font-weight: 800; color: #ffc400ff; text-shadow: 0 2px 10px rgba(255, 196, 0, 0.3);">1º</div>
            <div>
                <h3 style="margin: 0 0 0.5rem 0; color: #ffc400ff; font-size: 1.3rem;">Primer Puesto</h3>
                <p style="margin: 0; font-size: 1.1rem; color: var(--text-color);">Orden de Compra en <strong>Frávega</strong> por valor de <strong>$500.000</strong></p>
            </div>
            <div style="margin-left: auto; color: #ffc400ff; opacity: 0.5;">
                <i class="ph-fill ph-trophy" style="font-size: 48px;"></i>
            </div>
        </div>

        <!-- 2do Puesto -->
        <div style="background: var(--ios-card-bg); border-left: 5px solid #c0c0c0; padding: 2rem; border-radius: 0 16px 16px 0; display: flex; align-items: center; gap: 2rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03); transition: transform 0.2s ease; cursor: default;" onmouseover="this.style.transform='translateX(5px)'" onmouseout="this.style.transform='translateX(0)'">
            <div style="font-size: 3rem; font-weight: 800; color: #c0c0c0; text-shadow: 0 2px 10px rgba(192, 192, 192, 0.3);">2º</div>
            <div>
                <h3 style="margin: 0 0 0.5rem 0; color: #c0c0c0; font-size: 1.3rem;">Segundo Puesto</h3>
                <p style="margin: 0; font-size: 1.1rem; color: var(--text-color);">Voucher en <strong>Ruggiero</strong> por almuerzo o cena para dos personas.</p>
            </div>
            <div style="margin-left: auto; color: #c0c0c0; opacity: 0.5;">
                <i class="ph-fill ph-medal" style="font-size: 48px;"></i>
            </div>
        </div>

        <!-- 3er Puesto -->
        <div style="background: var(--ios-card-bg); border-left: 5px solid #cd7f32; padding: 2rem; border-radius: 0 16px 16px 0; display: flex; align-items: center; gap: 2rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03); transition: transform 0.2s ease; cursor: default;" onmouseover="this.style.transform='translateX(5px)'" onmouseout="this.style.transform='translateX(0)'">
            <div style="font-size: 3rem; font-weight: 800; color: #cd7f32; text-shadow: 0 2px 10px rgba(205, 127, 50, 0.3);">3º</div>
            <div>
                <h3 style="margin: 0 0 0.5rem 0; color: #cd7f32; font-size: 1.3rem;">Tercer Puesto</h3>
                <p style="margin: 0; font-size: 1.1rem; color: var(--text-color);">Voucher en <strong>Danke</strong> por almuerzo o cena para dos personas.</p>
            </div>
            <div style="margin-left: auto; color: #cd7f32; opacity: 0.5;">
                <i class="ph-fill ph-certificate" style="font-size: 48px;"></i>
            </div>
        </div>
    </div>
</div>

<?php render_footer($user, "premios"); ?>
