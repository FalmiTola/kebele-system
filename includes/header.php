<?php
// includes/header.php
require_once __DIR__ . '/lang.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hirmata Mentina Kebele | RMS</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/kebele-system/assets/css/style.css">
</head>
<body class="bg-light">
    <div class="d-flex" id="wrapper">
<?php include_once 'sidebar.php'; ?>
        <div id="page-content-wrapper">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg sticky-top border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <button class="btn btn-outline-secondary btn-sm" id="menu-toggle">
                            <i class="fas fa-bars"></i>
                        </button>
                        <div class="d-none d-lg-flex align-items-center gap-2 border-start ps-3 ms-2">
                            <img src="/kebele-system/assets/img/ethiopia_flag.png" alt="Ethiopia" height="20" class="rounded-1 shadow-sm">
                            <img src="/kebele-system/assets/img/oromia_flag.png" alt="Oromia" height="20" class="rounded-1 shadow-sm">
                            <div class="ms-2">
                                <h6 class="mb-0 fw-bold text-dark small" style="letter-spacing: 0.5px;">HIRMAT MENTINA KEBELE</h6>
                                <p class="text-muted mb-0" style="font-size: 0.65rem;">Official Administrative Portal</p>
                            </div>
                        </div>
                    </div>
                    <div class="ms-auto d-flex align-items-center">
                        <!-- Language Switcher -->
                        <div class="dropdown me-4">
                            <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-globe me-1"></i> <?php echo strtoupper($current_lang); ?>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="?lang=en">English</a></li>
                                <li><a class="dropdown-item" href="?lang=om">Afaan Oromoo</a></li>
                                <li><a class="dropdown-item" href="?lang=am">አማርኛ</a></li>
                            </ul>
                        </div>

                        <?php 
                            $role_key = $_SESSION['role'] ?? 'staff';
                            if ($role_key === 'admin') $role_key = 'administrator';
                            if ($role_key === 'security') $role_key = 'security_committee';
                            if ($role_key === 'clerk') $role_key = 'data_clerk';
                        ?>
                        <span class="me-3 d-none d-md-inline">
                            <?php echo __('welcome'); ?>, 
                            <strong><?php echo $_SESSION['username'] ?? 'User'; ?></strong> 
                            <span class="small text-muted">(<?php echo __($role_key); ?>)</span>
                        </span>
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle fa-lg"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="/kebele-system/auth/logout.php"><i class="fas fa-sign-out-alt me-2"></i><?php echo __('logout'); ?></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
            <div class="container-fluid p-4">
