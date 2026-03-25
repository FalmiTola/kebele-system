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
    <!-- Navbar - Full Width Top Bar -->
    <nav class="navbar navbar-expand-lg sticky-top border-bottom bg-white shadow-sm flex-md-nowrap p-0">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center gap-3 py-2">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-outline-secondary btn-sm" id="menu-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="d-flex align-items-center gap-3 ps-3 ms-2 border-start">
                        <i class="fas fa-landmark text-primary-dark fs-4"></i>
                        <div>
                            <h5 class="mb-0 fw-bold text-dark" style="letter-spacing: 0.5px; font-size: 1.1rem;">HIRMAT MENTINA KEBELE</h5>
                            <p class="text-muted mb-0 fw-semibold uppercase tracking-wider" style="font-size: 0.65rem;">Official Administrative Portal</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="ms-auto d-flex align-items-center gap-4">
                <!-- Language Switcher -->
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-primary dropdown-toggle border-0 font-bold" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-globe me-1"></i> <?php echo strtoupper($current_lang); ?>
                    </button>
                    <ul class="dropdown-menu shadow-lg border-0">
                        <li><a class="dropdown-item" href="?lang=en">English</a></li>
                        <li><a class="dropdown-item" href="?lang=om">Afaan Oromoo</a></li>
                        <li><a class="dropdown-item" href="?lang=am">አማርኛ</a></li>
                    </ul>
                </div>

                <div class="d-flex align-items-center gap-3 border-start ps-4 py-2">
                    <div class="text-end d-none d-md-block">
                        <p class="mb-0 small text-muted leading-tight">Welcome,</p>
                        <p class="mb-0 fw-bold text-dark leading-tight"><?php echo $_SESSION['username'] ?? 'User'; ?></p>
                    </div>
                    <?php 
                        $role_key = $_SESSION['role'] ?? 'staff';
                        if ($role_key === 'admin') $role_key = 'administrator';
                    ?>
                    <div class="dropdown">
                        <a class="nav-link p-0" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fas fa-user-shield"></i>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-3 p-2" style="border-radius: 12px;">
                            <li><h6 class="dropdown-header small text-muted uppercase tracking-wider"><?php echo __($role_key); ?></h6></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item rounded-3" href="/kebele-system/auth/logout.php">
                                <i class="fas fa-sign-out-alt me-2 text-danger"></i><?php echo __('logout'); ?>
                            </a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="d-flex" id="wrapper">
<?php include_once 'sidebar.php'; ?>
        <div id="page-content-wrapper">
            <div class="container-fluid p-4">
