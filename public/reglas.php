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

render_header("Reglas y Puntos", $user, "reglas");
?>

<div class="glass-card animate-fade-in" style="padding: 2.5rem; margin: 0 auto;">
    
    <div style="text-align: center; margin-bottom: 3rem;">
        <div style="display:flex;justify-content:center;margin-bottom:1rem;color:var(--ios-blue);"><svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg></div>
        <h2 style="margin-bottom: 0.5rem;">Reglas del Juego</h2>
        <p class="text-muted">Conoce cómo sumar puntos en el Prode Mundial FSInet</p>
    </div>

    <div style="margin-bottom: 3rem;">
        <h3 style="color: var(--ios-blue); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
            <span><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg></span> Sistema de Puntuación
        </h3>
        <p style="margin-bottom: 1.5rem; line-height: 1.6;">El sistema de puntos premia tanto la exactitud como la tendencia del resultado. Solo puedes sumar puntos de una de las siguientes maneras por partido (no son acumulables):</p>
        
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <div style="background: rgba(0,122,255,0.05); border: 1px solid rgba(0,122,255,0.2); padding: 1.5rem; border-radius: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                    <strong style="font-size: 1.1rem; color: var(--ios-text);">Resultado Exacto</strong>
                    <span style="background: var(--ios-blue); color: white; padding: 0.2rem 0.8rem; border-radius: 20px; font-weight: 700;">+3 Puntos</span>
                </div>
                <p class="text-muted" style="margin: 0; font-size: 0.95rem;">Acertaste tanto al ganador (o empate) como a los goles exactos que metió cada equipo.</p>
            </div>
            
            <div style="background: rgba(255,149,0,0.05); border: 1px solid rgba(255,149,0,0.2); padding: 1.5rem; border-radius: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                    <strong style="font-size: 1.1rem; color: var(--ios-text);">Tendencia (Ganador o Empate)</strong>
                    <span style="background: var(--ios-orange, #FF9500); color: white; padding: 0.2rem 0.8rem; border-radius: 20px; font-weight: 700;">+1 Punto</span>
                </div>
                <p class="text-muted" style="margin: 0; font-size: 0.95rem;">Acertaste qué equipo ganó el partido, o si fue un empate, pero no los goles exactos.</p>
            </div>

            <div style="background: rgba(0,122,255,0.05); border: 1px solid rgba(0,122,255,0.2); padding: 1.5rem; border-radius: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                    <strong style="font-size: 1.1rem; color: var(--ios-text);">Acierto en Penales (Rondas Eliminatorias)</strong>
                    <span style="background: var(--ios-blue); color: white; padding: 0.2rem 0.8rem; border-radius: 20px; font-weight: 700;">+3 Puntos Extra</span>
                </div>
                <p class="text-muted" style="margin: 0; font-size: 0.95rem;">Si un partido termina en empate y adivinas esa tendencia, sumas 1 pt. Si además marcas "(P)" en el equipo que termina ganando la tanda de penales, sumas estos puntos extra (Ej. 1 pt empate + 3 pts penales = 4 puntos en total).</p>
            </div>

            <div style="background: rgba(255,59,48,0.05); border: 1px solid rgba(255,59,48,0.2); padding: 1.5rem; border-radius: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                    <strong style="font-size: 1.1rem; color: var(--ios-text);">Ningún Acierto</strong>
                    <span style="background: var(--ios-error, #FF3B30); color: white; padding: 0.2rem 0.8rem; border-radius: 20px; font-weight: 700;">0 Puntos</span>
                </div>
                <p class="text-muted" style="margin: 0; font-size: 0.95rem;">No acertaste ni el ganador ni el resultado.</p>
            </div>
        </div>
    </div>

    <div style="margin-bottom: 3rem;">
        <h3 style="color: var(--ios-blue); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
            <span><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg></span> Puntajes Especiales por Etapas
        </h3>
        <p style="margin-bottom: 1.5rem; line-height: 1.6;">A medida que el torneo avanza y se vuelve más difícil, ¡los aciertos valen mucho más!</p>
        
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <div style="background: rgba(255,149,0,0.05); border: 1px solid rgba(255,149,0,0.2); padding: 1.5rem; border-radius: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                    <strong style="font-size: 1.1rem; color: var(--ios-text);">Semifinales</strong>
                    <span style="background: var(--ios-orange, #FF9500); color: white; padding: 0.2rem 0.8rem; border-radius: 20px; font-weight: 700;">Puntos Dobles (x2)</span>
                </div>
                <p class="text-muted" style="margin: 0; font-size: 0.95rem;">Todos los puntos valen el doble. (Resultado Exacto = 6 pts, Tendencia = 2 pts, Acierto en Penales = +6 pts extra).</p>
            </div>

            <div style="background: rgba(88,86,214,0.05); border: 1px solid rgba(88,86,214,0.2); padding: 1.5rem; border-radius: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                    <strong style="font-size: 1.1rem; color: var(--ios-text);">La Gran Final</strong>
                    <span style="background: #5856D6; color: white; padding: 0.2rem 0.8rem; border-radius: 20px; font-weight: 700;">Puntos Épicos</span>
                </div>
                <ul class="text-muted" style="margin: 0; font-size: 0.95rem; padding-left: 1.2rem; line-height: 1.6;">
                    <li><strong>Resultado Exacto:</strong> 10 Puntos.</li>
                    <li><strong>Tendencia (Ganador/Empate):</strong> 3 Puntos.</li>
                    <li><strong>Acierto en Penales:</strong> +5 Puntos extra si el partido va a penales y aciertas el ganador.</li>
                </ul>
            </div>
        </div>
    </div>

    <div>
        <h3 style="color: var(--ios-blue); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
            <span><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></span> Tiempos Límite
        </h3>
        <div style="background: rgba(118,118,128,0.05); border: 1px solid rgba(118,118,128,0.2); padding: 1.5rem; border-radius: 16px;">
            <ul style="line-height: 1.8; color: var(--ios-text-sec); padding-left: 1.5rem; margin: 0;">
                <li>Puedes cargar o modificar tus pronósticos hasta <strong>2 horas antes del comienzo del primer partido de cada etapa</strong> (fase).</li>
                <li>Por ejemplo, los pronósticos de los Octavos de Final se bloquearán enteros 2 horas antes de que comience el primer partido de esos Octavos.</li>
                <li>Una vez pasado ese tiempo límite de la etapa, los casilleros de todos los partidos de esa fase se bloquearán y no podrás alterar tu predicción.</li>
                <li>Los puntos se actualizarán automáticamente una vez que cada partido vaya finalizando y el resultado oficial sea cargado por los administradores.</li>
            </ul>
        </div>
    </div>

</div>

<?php render_footer($user, "reglas"); ?>
