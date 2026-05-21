<?php
if (!function_exists('getFlagCode')) {
    function getFlagCode($country) {
        $map = [
            'México' => 'mx', 'Sudáfrica' => 'za', 'Corea del Sur' => 'kr', 'Chequia' => 'cz',
            'Canadá' => 'ca', 'Bosnia y Herzegovina' => 'ba', 'Catar' => 'qa', 'Suiza' => 'ch',
            'Brasil' => 'br', 'Marruecos' => 'ma', 'Haití' => 'ht', 'Escocia' => 'gb-sct',
            'Estados Unidos' => 'us', 'Paraguay' => 'py', 'Australia' => 'au', 'Turquía' => 'tr',
            'Alemania' => 'de', 'Curazao' => 'cw', 'Costa de Marfil' => 'ci', 'Ecuador' => 'ec',
            'Países Bajos' => 'nl', 'Japón' => 'jp', 'Suecia' => 'se', 'Túnez' => 'tn',
            'Bélgica' => 'be', 'Egipto' => 'eg', 'Irán' => 'ir', 'Nueva Zelanda' => 'nz',
            'España' => 'es', 'Cabo Verde' => 'cv', 'Arabia Saudita' => 'sa', 'Uruguay' => 'uy',
            'Francia' => 'fr', 'Senegal' => 'sn', 'Irak' => 'iq', 'Noruega' => 'no',
            'Argentina' => 'ar', 'Argelia' => 'dz', 'Austria' => 'at', 'Jordania' => 'jo',
            'Portugal' => 'pt', 'RD Congo' => 'cd', 'Uzbekistán' => 'uz', 'Colombia' => 'co',
            'Inglaterra' => 'gb-eng', 'Croacia' => 'hr', 'Ghana' => 'gh', 'Panamá' => 'pa'
        ];
        return isset($map[$country]) ? $map[$country] : null;
    }
}

function render_header($title, $user, $current_page) {
    ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?> | FSInet Prode</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
</head>
<body>
    <div class="app-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                    <a href="resumen.php" class="logo-side">
                        <img src="../assets/images/logo-mundial.svg" alt="Logo FSInet Prode">
                    </a>
            </div>
            
            <?php if (isset($user) && $user): ?>
            <div class="mobile-user-actions">
                <div class="user-avatar" style="width: 32px; height: 32px; font-size: 0.9rem;">
                    <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                </div>
                <a href="logout.php" class="logout-btn-mobile" title="Cerrar sesión">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                </a>
            </div>
            <?php endif; ?>
            
            <nav class="sidebar-nav">
                <a href="resumen.php" class="nav-item <?php echo $current_page == 'resumen' ? 'active' : ''; ?>">
                    <span class="nav-icon"><i data-lucide="layout-dashboard"></i></span> Resumen
                </a>
                <a href="fixture.php" class="nav-item <?php echo $current_page == 'fixture' ? 'active' : ''; ?>">
                    <span class="nav-icon"><i data-lucide="calendar-days"></i></span> Fixture
                </a>
                <a href="ranking.php" class="nav-item <?php echo $current_page == 'ranking' ? 'active' : ''; ?>">
                    <span class="nav-icon"><i data-lucide="trophy"></i></span> Ranking
                </a>
                <a href="reglas.php" class="nav-item <?php echo $current_page == 'reglas' ? 'active' : ''; ?>">
                    <span class="nav-icon"><i data-lucide="book-open"></i></span> Reglas
                </a>
                <?php if ($user['is_admin']): ?>
                    <a href="admin.php" class="nav-item <?php echo $current_page == 'admin' ? 'active' : ''; ?>">
                        <span class="nav-icon"><i data-lucide="settings"></i></span> Admin
                    </a>
                <?php endif; ?>
            </nav>

            <div class="sidebar-footer">
                <div class="user-profile">
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                    </div>
                    <div class="user-info">
                        <div class="user-name"><?php echo htmlspecialchars($user['full_name']); ?></div>
                        <div class="user-points"><?php echo $user['points']; ?> Pts</div>
                    </div>
                </div>
                <a href="logout.php" class="logout-btn">Cerrar Sesión</a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="top-bar">
                <h1><?php echo htmlspecialchars($title); ?></h1>
            </div>
            <div class="content-wrapper">
    <?php
}

function render_footer($user = null, $current_page = '') {
    ?>
            </div>
        </main>

        <!-- Mobile Bottom Navigation -->
        <nav class="mobile-nav">
            <a href="resumen.php" class="mobile-nav-item <?php echo $current_page == 'resumen' ? 'active' : ''; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                Resumen
            </a>
            <a href="fixture.php" class="mobile-nav-item <?php echo $current_page == 'fixture' ? 'active' : ''; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/><line x1="8" x2="8" y1="14" y2="14"/><line x1="12" x2="12" y1="14" y2="14"/><line x1="16" x2="16" y1="14" y2="14"/></svg>
                Fixture
            </a>
            <a href="ranking.php" class="mobile-nav-item <?php echo $current_page == 'ranking' ? 'active' : ''; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                Ranking
            </a>
            <a href="reglas.php" class="mobile-nav-item <?php echo $current_page == 'reglas' ? 'active' : ''; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                Reglas
            </a>
            <?php if ($user && $user['is_admin']): ?>
            <a href="admin.php" class="mobile-nav-item <?php echo $current_page == 'admin' ? 'active' : ''; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                Admin
            </a>
            <?php endif; ?>
        </nav>

    </div>
    <script>lucide.createIcons();</script>
</body>
</html>
    <?php
}
?>
