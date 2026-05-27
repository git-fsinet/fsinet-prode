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

<style>
    .podium-container {
        display: flex;
        flex-direction: column;
        gap: 2rem;
        margin-top: 3rem;
    }
    .prize-card {
        background: var(--ios-card-bg);
        border-radius: 28px;
        padding: 3rem 2rem;
        text-align: center;
        box-shadow: 0 10px 40px rgba(0,0,0,0.06);
        position: relative;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 1px solid var(--ios-border);
        display: flex;
        flex-direction: column;
        align-items: center;
        z-index: 1;
    }
    .prize-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 50px rgba(0,0,0,0.12);
        z-index: 2;
    }
    .prize-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; height: 8px;
    }
    .prize-card-1::before { background: linear-gradient(90deg, #ffe066, #ffc400ff); }
    .prize-card-2::before { background: linear-gradient(90deg, #e2e2e2, #b0b0b0); }
    .prize-card-3::before { background: linear-gradient(90deg, #e8b387, #cd7f32); }

    .prize-icon-wrapper {
        width: 100px; height: 100px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 1.5rem;
        position: relative;
    }
    .prize-icon-wrapper::after {
        content: ''; position: absolute; top: -10px; left: -10px; right: -10px; bottom: -10px;
        border-radius: 50%; border: 2px dashed currentColor; opacity: 0.2;
        animation: spin 20s linear infinite;
    }
    @keyframes spin { 100% { transform: rotate(360deg); } }

    .prize-card-1 .prize-icon-wrapper {
        background: rgba(255, 196, 0, 0.1); color: #ffc400ff; box-shadow: 0 0 30px rgba(255, 196, 0, 0.25);
    }
    .prize-card-2 .prize-icon-wrapper {
        background: rgba(192, 192, 192, 0.1); color: #c0c0c0; box-shadow: 0 0 30px rgba(192, 192, 192, 0.25);
    }
    .prize-card-3 .prize-icon-wrapper {
        background: rgba(205, 127, 50, 0.1); color: #cd7f32; box-shadow: 0 0 30px rgba(205, 127, 50, 0.25);
    }

    .prize-pos { font-size: 1.2rem; font-weight: 800; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 0.5rem; }
    .prize-title { font-size: 1.8rem; font-weight: 700; margin-bottom: 1rem; color: var(--ios-text); }
    .prize-desc { font-size: 1.1rem; color: var(--ios-text-sec); line-height: 1.6; margin: 0; }
    
    @media (min-width: 992px) {
        .podium-container {
            flex-direction: row;
            align-items: flex-end;
            justify-content: center;
            padding: 2rem 0;
            gap: 1.5rem;
        }
        .prize-card { flex: 1; max-width: 350px; }
        .prize-card-1 { order: 2; transform: translateY(-40px); }
        .prize-card-1:hover { transform: translateY(-50px); }
        .prize-card-2 { order: 1; }
        .prize-card-3 { order: 3; }
    }
</style>

<div class="animate-fade-in" style="max-width: 1200px; margin: 0 auto; padding: 2rem;">
    <div style="text-align: center; margin-bottom: 1rem;">
        <div style="display:inline-flex; align-items:center; justify-content:center; width: 80px; height: 80px; background: linear-gradient(135deg, rgba(12, 74, 211, 0.1), rgba(94, 92, 230, 0.1)); color: var(--ios-blue); border-radius: 24px; margin-bottom: 1.5rem; transform: rotate(-5deg); box-shadow: 0 10px 20px rgba(0,0,0,0.05);">
            <i class="ph-fill ph-gift" style="font-size: 42px;"></i>
        </div>
        <h2 style="font-size: 2.5rem; font-weight: 800; margin-bottom: 0.5rem; letter-spacing: -1px;">Premios del Prode</h2>
        <p style="color: var(--ios-text-sec); font-size: 1.2rem;">¡Participa y gana estos increíbles premios!</p>
    </div>

    <div class="podium-container">
        <!-- 1er Puesto -->
        <div class="prize-card prize-card-1">
            <div class="prize-icon-wrapper">
                <i class="ph-fill ph-trophy" style="font-size: 56px;"></i>
            </div>
            <div class="prize-pos" style="color: #ffc400ff;">1º Lugar</div>
            <div class="prize-title" style="font-size: 2.2rem;">Campeón</div>
            <p class="prize-desc">Orden de Compra en <strong>Frávega</strong> por valor de<br><strong style="font-size: 1.3em; display: block; margin-top: 0.5rem; color: var(--ios-text);">$500.000</strong></p>
        </div>

        <!-- 2do Puesto -->
        <div class="prize-card prize-card-2">
            <div class="prize-icon-wrapper">
                <i class="ph-fill ph-medal" style="font-size: 48px;"></i>
            </div>
            <div class="prize-pos" style="color: #c0c0c0;">2º Lugar</div>
            <div class="prize-title">Subcampeón</div>
            <p class="prize-desc">Voucher en <strong>Ruggiero</strong> por almuerzo o cena para dos personas.</p>
        </div>

        <!-- 3er Puesto -->
        <div class="prize-card prize-card-3">
            <div class="prize-icon-wrapper">
                <i class="ph-fill ph-certificate" style="font-size: 48px;"></i>
            </div>
            <div class="prize-pos" style="color: #cd7f32;">3º Lugar</div>
            <div class="prize-title">Tercer Puesto</div>
            <p class="prize-desc">Voucher en <strong>Danke</strong> por almuerzo o cena para dos personas.</p>
        </div>
    </div>
</div>

<?php render_footer($user, "premios"); ?>
