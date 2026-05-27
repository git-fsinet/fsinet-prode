<?php
session_start();
require_once '../config/db.php';

$error = '';
$mode = isset($_GET['mode']) && $_GET['mode'] === 'onboarding' ? 'onboarding' : 'login';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'login') {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $pin = $_POST['pin'];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($pin, $user['pin'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['is_admin'] = $user['is_admin'];
            header('Location: resumen.php');
            exit;
        } else {
            $error = 'Email o PIN incorrecto.';
            $mode = 'login';
        }
    } elseif ($action === 'register') {
        $play_mode = $_POST['play_mode'] ?? 'solo';
        $is_fan = isset($_POST['is_fan']) ? (int)$_POST['is_fan'] : 0;
        $email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';
        $pin = $_POST['pin'] ?? '';

        if ($play_mode === 'team') {
            $capitan = trim($_POST['capitan_name'] ?? '');
            $ayudante = trim($_POST['ayudante_name'] ?? '');
            $full_name = $capitan . ' + ' . $ayudante;
        } else {
            $full_name = trim($_POST['full_name'] ?? '');
        }

        if (!preg_match('/@fsinet\.com\.ar$/i', $email)) {
            $error = 'Solo se permiten correos @fsinet.com.ar';
        } else {
            // Check if user exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = 'El correo ya está registrado. Por favor, inicia sesión.';
                $mode = 'login';
            } else {
                $hashedPin = password_hash($pin, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (email, pin, full_name, is_fan) VALUES (?, ?, ?, ?)");
                $stmt->execute([$email, $hashedPin, $full_name, $is_fan]);
                
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['full_name'] = $full_name;
                $_SESSION['is_admin'] = 0;
                
                // Show welcome screen logic will be handled by JS before submission, 
                // but after PHP save, we redirect to resumen.
                header('Location: resumen.php');
                exit;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prode Mundial 2026 | FSInet</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="login-page">
    <div class="container" style="display: flex; align-items: center; justify-content: center; min-height: 100vh;">
        
        <?php if ($mode === 'login'): ?>
            <!-- LOGIN VIEW -->
            <div class="glass-card animate-fade-in" style="width: 100%; max-width: 450px;">
                <div style="text-align: center; margin-bottom: 2rem;">
                    <a href="index.php" class="logo">
                        <img src="../assets/images/logo-mundial.png" alt="Logo FSInet Prode">
                    </a>
                    <h2 style="text-align: center;">ProdeMundial 2026</h2>
                </div>

                <?php if ($error): ?>
                    <div style="background: rgba(255, 59, 48, 0.1); border: 1px solid var(--ios-error); color: var(--ios-error); padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; font-size: 0.9rem;">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form action="index.php?mode=login" method="POST">
                    <input type="hidden" name="action" value="login">
                    <div class="input-group">
                        <label for="email">Correo FSInet</label>
                        <input type="email" id="email" name="email" placeholder="usuario@fsinet.com.ar" required>
                    </div>
                    <div class="input-group">
                        <label for="pin">PIN de 4 dígitos</label>
                        <input type="password" id="pin" name="pin" placeholder="••••" maxlength="4" pattern="\d{4}" inputmode="numeric" required>
                    </div>
                    <button type="submit" class="btn" style="margin-top: 1rem;">INGRESAR</button>
                </form>

                <div style="margin-top: 2rem; text-align: center;">
                    <a href="index.php?mode=onboarding" style="color: var(--ios-blue); text-decoration: none; font-weight: 500; font-size: 0.95rem;">¿Es tu primera vez? Crear cuenta</a>
                </div>
            </div>

        <?php else: ?>
            <!-- ONBOARDING VIEW -->
            <div class="glass-card animate-fade-in onboarding-container">
                <div style="text-align: center; margin-bottom: 1.5rem;">
                    <a href="index.php" class="logo">
                        <img src="../assets/images/logo-mundial.png" alt="Logo FSInet Prode">
                    </a>
                </div>

                <?php if ($error): ?>
                    <div style="background: rgba(255, 59, 48, 0.1); border: 1px solid var(--ios-error); color: var(--ios-error); padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; font-size: 0.9rem;">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <div class="step-indicator" id="stepIndicator">
                    <div class="dot active"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                </div>

                <form action="index.php?mode=onboarding" method="POST" id="onboardingForm">
                    <input type="hidden" name="action" value="register">
                    
                    <div class="onboarding-steps-wrapper" id="stepsWrapper">
                        <!-- Step 0: Fan -->
                        <div class="onboarding-step active">
                            <h2 style="text-align: center;">¿Te gusta el fútbol?</h2>
                            <p class="text-muted" style="text-align: center; margin-bottom: 2rem;">Para saber con quién competimos.</p>
                            
                            <div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
                                <label style="flex: 1; text-align: center; background: var(--ios-input-bg); padding: 1rem; border-radius: 16px; cursor: pointer; border: 2px solid transparent; transition: all 0.2s;" id="label_fan_1" onclick="selectFan(1)">
                                    <input type="radio" name="is_fan" value="1" style="display: none;">
                                    <div style="display: flex; justify-content: center; margin-bottom: 0.5rem; color: var(--ios-blue);"><i class="ph-duotone ph-heart" style="font-size:32px;"></i></div>
                                    <div style="font-weight: 600; font-size: 0.85rem;">Soy fan</div>
                                </label>
                                <label style="flex: 1; text-align: center; background: var(--ios-input-bg); padding: 1rem; border-radius: 16px; cursor: pointer; border: 2px solid transparent; transition: all 0.2s;" id="label_fan_0" onclick="selectFan(0)">
                                    <input type="radio" name="is_fan" value="0" style="display: none;" checked>
                                    <div style="display: flex; justify-content: center; margin-bottom: 0.5rem; color: var(--ios-text-sec);"><i class="ph-duotone ph-smiley-meh" style="font-size:32px;"></i></div>
                                    <div style="font-weight: 600; font-size: 0.85rem;">Sólo por el mundial</div>
                                </label>
                                <label style="flex: 1; text-align: center; background: var(--ios-input-bg); padding: 1rem; border-radius: 16px; cursor: pointer; border: 2px solid transparent; transition: all 0.2s;" id="label_fan_2" onclick="selectFan(2)">
                                    <input type="radio" name="is_fan" value="2" style="display: none;">
                                    <div style="display: flex; justify-content: center; margin-bottom: 0.5rem; color: var(--ios-error);"><i class="ph-duotone ph-x-circle" style="font-size:32px;"></i></div>
                                    <div style="font-weight: 600; font-size: 0.85rem;">No me gusta</div>
                                </label>
                            </div>
                            <button type="button" class="btn" onclick="nextStep(1)">Siguiente</button>
                        </div>

                        <!-- Step 1: Mode -->
                        <div class="onboarding-step">
                            <h2 style="text-align: center;">¿Querés jugar solo o con un compañero?</h2>
                            <p class="text-muted" style="text-align: center; margin-bottom: 2rem;">Selecciona tu modalidad de juego.</p>
                            
                            <div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
                                <label style="flex: 1; text-align: center; background: var(--ios-input-bg); padding: 1.5rem; border-radius: 16px; cursor: pointer; border: 2px solid transparent; transition: all 0.2s;" id="label_mode_solo" onclick="selectMode('solo')">
                                    <input type="radio" name="play_mode" value="solo" style="display: none;" checked>
                                    <div style="display: flex; justify-content: center; margin-bottom: 0.5rem; color: var(--ios-blue);"><i class="ph-duotone ph-user" style="font-size:40px;"></i></div>
                                    <div style="font-weight: 600;">Me la juego solo</div>
                                </label>
                                <label style="flex: 1; text-align: center; background: var(--ios-input-bg); padding: 1.5rem; border-radius: 16px; cursor: pointer; border: 2px solid transparent; transition: all 0.2s;" id="label_mode_team" onclick="selectMode('team')">
                                    <input type="radio" name="play_mode" value="team" style="display: none;">
                                    <div style="display: flex; justify-content: center; margin-bottom: 0.5rem; color: var(--ios-blue);"><i class="ph-duotone ph-users" style="font-size:40px;"></i></div>
                                    <div style="font-weight: 600;">Prefiero en equipo</div>
                                </label>
                            </div>
                            <button type="button" class="btn" onclick="nextStep(2)">Siguiente</button>
                        </div>

                        <!-- Step 2: Names -->
                        <div class="onboarding-step">
                            <div id="names_solo_view">
                                <h2 style="text-align: center;">¿Cómo es tu nombre?</h2>
                                <p class="text-muted" style="text-align: center; margin-bottom: 2rem;">Para saber quién está jugando.</p>
                                <div class="input-group">
                                    <input type="text" name="full_name" id="reg_name" placeholder="Tu nombre y apellido">
                                </div>
                            </div>
                            <div id="names_team_view" style="display: none;">
                                <h2 style="text-align: center;">Capitán y Ayudante</h2>
                                <p class="text-muted" style="text-align: center; margin-bottom: 2rem;">Ingresa los nombres del equipo.</p>
                                <div class="input-group">
                                    <input type="text" name="capitan_name" id="reg_capitan" placeholder="Nombre del Capitán">
                                </div>
                                <div class="input-group">
                                    <input type="text" name="ayudante_name" id="reg_ayudante" placeholder="Nombre del Ayudante de Campo">
                                </div>
                            </div>
                            <button type="button" class="btn" onclick="nextStep(3)">Siguiente</button>
                        </div>

                        <!-- Step 3: Email & PIN -->
                        <div class="onboarding-step">
                            <h2 style="text-align: center;" id="email_title">Tu correo corporativo</h2>
                            <p class="text-muted" style="text-align: center; margin-bottom: 2rem;" id="email_desc">Exclusivo para el equipo de FSInet.</p>
                            <div class="input-group">
                                <input type="email" name="email" id="reg_email" placeholder="usuario@fsinet.com.ar">
                            </div>
                            
                            <h2 style="text-align: center; margin-top: 1.5rem;">Crea tu PIN de acceso</h2>
                            <p class="text-muted" style="text-align: center; margin-bottom: 1rem;">Un código de 4 dígitos para ingresar luego.</p>
                            <div class="input-group">
                                <input type="password" name="pin" id="reg_pin" placeholder="••••" maxlength="4" pattern="\d{4}" inputmode="numeric" style="text-align: center; font-size: 2rem; letter-spacing: 0.5em;">
                            </div>
                            <button type="button" class="btn" onclick="finishOnboarding()">¡Comenzar a Jugar!</button>
                        </div>
                    </div>
                </form>

                <div style="margin-top: 2rem; text-align: center;">
                    <a href="index.php?mode=login" style="color: var(--ios-blue); text-decoration: none; font-weight: 500; font-size: 0.95rem;">Ya tengo una cuenta</a>
                </div>
            </div>

            <script>
                let currentStep = 0;
                const totalSteps = 4;
                const wrapper = document.getElementById('stepsWrapper');
                const dots = document.querySelectorAll('.dot');
                const steps = document.querySelectorAll('.onboarding-step');
                let playMode = 'solo';

                function updateView() {
                    wrapper.style.transform = `translateX(-${currentStep * 100}%)`;
                    
                    dots.forEach((dot, index) => {
                        if (index === currentStep) {
                            dot.classList.add('active');
                        } else {
                            dot.classList.remove('active');
                        }
                    });

                    steps.forEach((step, index) => {
                        if (index === currentStep) {
                            step.classList.add('active');
                        } else {
                            step.classList.remove('active');
                        }
                    });
                }

                function nextStep(stepIndex) {
                    if (stepIndex === 2) {
                        if (playMode === 'solo') {
                            document.getElementById('names_solo_view').style.display = 'block';
                            document.getElementById('names_team_view').style.display = 'none';
                            document.getElementById('email_title').innerText = 'Tu correo corporativo';
                        } else {
                            document.getElementById('names_solo_view').style.display = 'none';
                            document.getElementById('names_team_view').style.display = 'block';
                            document.getElementById('email_title').innerText = 'Mail de FSInet (Capitán)';
                        }
                    }
                    if (stepIndex === 3) {
                        if (playMode === 'solo') {
                            if (!document.getElementById('reg_name').value.trim()) { alert('Por favor, ingresa tu nombre.'); return; }
                        } else {
                            if (!document.getElementById('reg_capitan').value.trim() || !document.getElementById('reg_ayudante').value.trim()) {
                                alert('Por favor, ingresa ambos nombres.'); return;
                            }
                        }
                    }
                    
                    if (currentStep < totalSteps - 1) {
                        currentStep++;
                        updateView();
                    }
                }

                function selectFan(val) {
                    [0, 1, 2].forEach(v => {
                        const label = document.getElementById('label_fan_' + v);
                        if (v === val) {
                            label.style.borderColor = 'var(--ios-blue)';
                            label.style.background = 'rgba(12,74,211,0.05)';
                        } else {
                            label.style.borderColor = 'transparent';
                            label.style.background = 'var(--ios-input-bg)';
                        }
                    });
                }

                function selectMode(mode) {
                    playMode = mode;
                    const soloLabel = document.getElementById('label_mode_solo');
                    const teamLabel = document.getElementById('label_mode_team');
                    
                    if (mode === 'solo') {
                        soloLabel.style.borderColor = 'var(--ios-blue)';
                        soloLabel.style.background = 'rgba(12,74,211,0.05)';
                        teamLabel.style.borderColor = 'transparent';
                        teamLabel.style.background = 'var(--ios-input-bg)';
                    } else {
                        teamLabel.style.borderColor = 'var(--ios-blue)';
                        teamLabel.style.background = 'rgba(12,74,211,0.05)';
                        soloLabel.style.borderColor = 'transparent';
                        soloLabel.style.background = 'var(--ios-input-bg)';
                    }
                }

                function finishOnboarding() {
                    const email = document.getElementById('reg_email').value.trim();
                    if (!email || !email.endsWith('@fsinet.com.ar')) { alert('Por favor, ingresa un correo válido de @fsinet.com.ar.'); return; }
                    
                    const pin = document.getElementById('reg_pin').value.trim();
                    if (pin.length !== 4) { alert('El PIN debe tener 4 dígitos.'); return; }
                    
                    const welcomeMsg = playMode === 'solo' 
                        ? '¡Gracias por registrarte!<br>Se está cargando tu prode...' 
                        : '¡Gracias por registrarse!<br>Se está cargando el prode...';

                    const welcomeDiv = document.createElement('div');
                    welcomeDiv.className = 'onboarding-step active';
                    welcomeDiv.style.textAlign = 'center';
                    welcomeDiv.style.padding = '2rem 0';
                    welcomeDiv.innerHTML = `
                        <div style="display:flex;justify-content:center;margin-bottom:1rem;color:var(--ios-blue);"><svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg></div>
                        <h2 style="font-size: 1.5rem; line-height: 1.4;">${welcomeMsg}</h2>
                    `;
                    
                    wrapper.style.display = 'none';
                    document.getElementById('stepIndicator').style.display = 'none';
                    document.getElementById('onboardingForm').appendChild(welcomeDiv);
                    
                    setTimeout(() => {
                        document.getElementById('onboardingForm').submit();
                    }, 1500);
                }
                
                selectFan(0);
                selectMode('solo');
            </script>
        <?php endif; ?>
    </div>
</body>
</html>

