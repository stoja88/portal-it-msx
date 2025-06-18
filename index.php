<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal de Servicios IT - MSX International</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <div class="logo-container me-3">
                    <div class="logo-circle">
                        <span class="logo-text">msx</span>
                    </div>
                </div>
                <div>
                    <strong>MSX International</strong><br>
                    <small class="text-muted">Valencia HUB</small>
                </div>
            </a>
            
            <div class="navbar-nav ms-auto">
                <?php if (isLoggedIn()): ?>
                    <a href="admin.php" class="btn btn-primary me-2">
                        <i class="fas fa-cog"></i> Panel Admin
                    </a>
                    <a href="logout.php" class="btn btn-outline-secondary">
                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                    </a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt"></i> Administrador
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <div class="hero-logo mb-4">
                        <div class="logo-circle-large">
                            <span class="logo-text-large">msx</span>
                            <small class="d-block">International</small>
                        </div>
                    </div>
                    <h1 class="display-4 mb-4 gradient-text">Portal de Servicios IT</h1>
                    <p class="lead mb-5">
                        Gestión de accesos y soporte técnico para 
                        <strong class="text-primary">MSX International Valencia HUB</strong> - 
                        Tu centro de servicios IT
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Services Section -->
    <div class="container my-5">
        <div class="row g-4">
            <!-- Soporte Técnico -->
            <div class="col-md-6">
                <div class="service-card">
                    <div class="service-icon support-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3>Soporte Técnico</h3>
                    <p class="service-subtitle">Reportar Incidencias</p>
                    <p class="service-description">
                        Crea tickets para reportar problemas técnicos, 
                        solicitar asistencia especializada o realizar consultas 
                        al equipo de IT de <strong>MSX International</strong>.
                    </p>
                    <a href="create-ticket.php" class="btn btn-success btn-lg">
                        <i class="fas fa-headset"></i> Crear Ticket de Soporte
                        <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>

            <!-- Control de Accesos -->
            <div class="col-md-6">
                <div class="service-card">
                    <div class="service-icon access-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h3>Control de Accesos</h3>
                    <p class="service-subtitle">Solicitar Permisos</p>
                    <p class="service-description">
                        Solicita acceso a sistemas, aplicaciones y recursos 
                        corporativos de <strong>MSX International</strong> de manera 
                        segura y controlada.
                    </p>
                    <a href="request-access.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-lock"></i> Solicitar Acceso
                        <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="stats-section">
        <div class="container">
            <h2 class="text-center mb-5">Estado del Sistema</h2>
            <div class="row g-4">
                <?php
                // Obtener estadísticas
                $stats = [
                    'tickets' => $pdo->query("SELECT COUNT(*) FROM tickets WHERE status != 'closed'")->fetchColumn(),
                    'users' => $pdo->query("SELECT COUNT(*) FROM users WHERE active = 1")->fetchColumn(),
                    'pending_requests' => $pdo->query("SELECT COUNT(*) FROM access_requests WHERE status = 'pending'")->fetchColumn(),
                    'inventory' => $pdo->query("SELECT COUNT(*) FROM inventory WHERE status = 'available'")->fetchColumn()
                ];
                ?>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon text-primary">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                        <h4><?php echo $stats['tickets']; ?></h4>
                        <p>Tickets Activos</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon text-success">
                            <i class="fas fa-users"></i>
                        </div>
                        <h4><?php echo $stats['users']; ?></h4>
                        <p>Usuarios Activos</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon text-warning">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h4><?php echo $stats['pending_requests']; ?></h4>
                        <p>Solicitudes Pendientes</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon text-info">
                            <i class="fas fa-box"></i>
                        </div>
                        <h4><?php echo $stats['inventory']; ?></h4>
                        <p>Equipos Disponibles</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p>&copy; 2024 MSX International - Valencia HUB. Todos los derechos reservados.</p>
                </div>
                <div class="col-md-6 text-end">
                    <p>Portal de Servicios IT v1.0</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html> 