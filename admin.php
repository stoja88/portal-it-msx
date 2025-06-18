<?php 
require_once 'config.php';

// Verificar si el usuario está logueado y es admin
/*if (!isLoggedIn() || !isAdmin()) {
    redirect('login.php');
}*/

// Para demo, simular que el usuario está logueado
$_SESSION['user_id'] = 1;
$_SESSION['full_name'] = 'Ivan - Super Admin';
$_SESSION['user_role'] = 'admin';

// Obtener estadísticas del dashboard
try {
    $stats = [
        'total_tickets' => $pdo->query("SELECT COUNT(*) FROM tickets")->fetchColumn(),
        'open_tickets' => $pdo->query("SELECT COUNT(*) FROM tickets WHERE status = 'open'")->fetchColumn(),
        'closed_tickets' => $pdo->query("SELECT COUNT(*) FROM tickets WHERE status = 'closed'")->fetchColumn(),
        'admin_users' => $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'admin' AND active = 1")->fetchColumn(),
        'total_users' => $pdo->query("SELECT COUNT(*) FROM users WHERE active = 1")->fetchColumn(),
        'active_users' => $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'admin' AND active = 1")->fetchColumn(),
        'inventory_items' => $pdo->query("SELECT COUNT(*) FROM inventory")->fetchColumn(),
        'available_inventory' => $pdo->query("SELECT COUNT(*) FROM inventory WHERE status = 'available'")->fetchColumn(),
        'pending_requests' => $pdo->query("SELECT COUNT(*) FROM access_requests WHERE status = 'pending'")->fetchColumn()
    ];

    // Obtener actualizaciones recientes
    $recent_updates = $pdo->query("
        SELECT 
            t.id,
            t.title,
            t.status,
            t.priority,
            t.created_at,
            t.updated_at,
            u.full_name as user_name,
            'ticket' as type
        FROM tickets t 
        JOIN users u ON t.user_id = u.id 
        ORDER BY t.updated_at DESC 
        LIMIT 5
    ")->fetchAll();
} catch (Exception $e) {
    // Valores por defecto si no hay conexión a BD
    $stats = [
        'total_tickets' => 11,
        'open_tickets' => 11,
        'closed_tickets' => 0,
        'admin_users' => 7,
        'total_users' => 7,
        'active_users' => 7,
        'inventory_items' => 3,
        'available_inventory' => 0,
        'pending_requests' => 0
    ];
    
    $recent_updates = [
        [
            'id' => 1,
            'title' => 'Nuevo Ticket - Debug Panel',
            'status' => 'open',
            'priority' => 'medium',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'user_name' => 'John Smith',
            'type' => 'ticket'
        ],
        [
            'id' => 2,
            'title' => 'No funciona Teams / Email y GTAC',
            'status' => 'in_progress',
            'priority' => 'medium',
            'created_at' => date('Y-m-d H:i:s', strtotime('-1 hour')),
            'updated_at' => date('Y-m-d H:i:s', strtotime('-1 hour')),
            'user_name' => 'María García',
            'type' => 'ticket'
        ],
        [
            'id' => 3,
            'title' => 'Test - Actualizaciones Recientes',
            'status' => 'open',
            'priority' => 'high',
            'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours')),
            'updated_at' => date('Y-m-d H:i:s', strtotime('-2 hours')),
            'user_name' => 'Carlos López',
            'type' => 'ticket'
        ]
    ];
}

$current_page = $_GET['page'] ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - MSX International</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Admin Header -->
    <div class="admin-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <div class="logo-container me-3">
                            <div class="logo-circle">
                                <span class="logo-text">msx</span>
                            </div>
                        </div>
                        <div>
                            <h4 class="mb-0">Panel de Administración</h4>
                            <small>MSX International - Valencia HUB</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <div class="d-flex align-items-center justify-content-end">
                        <div class="me-3">
                            <strong><?php echo $_SESSION['full_name'] ?? 'Ivan - Super Admin'; ?></strong>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-success me-2">Super Admin</span>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="index.php"><i class="fas fa-home"></i> Portal IT</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Navigation -->
    <div class="admin-nav">
        <div class="container">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'dashboard' ? 'active' : ''; ?>" href="admin.php">
                        <i class="fas fa-tachometer-alt"></i> Resumen
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'tickets' ? 'active' : ''; ?>" href="admin.php?page=tickets">
                        <i class="fas fa-ticket-alt"></i> Tickets
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'inventory' ? 'active' : ''; ?>" href="admin.php?page=inventory">
                        <i class="fas fa-box"></i> Inventario
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'users' ? 'active' : ''; ?>" href="admin.php?page=users">
                        <i class="fas fa-users"></i> Usuarios
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'notifications' ? 'active' : ''; ?>" href="admin.php?page=notifications">
                        <i class="fas fa-bell"></i> Notificaciones
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'reports' ? 'active' : ''; ?>" href="admin.php?page=reports">
                        <i class="fas fa-chart-bar"></i> Reportes
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'requests' ? 'active' : ''; ?>" href="admin.php?page=requests">
                        <i class="fas fa-user-plus"></i> Solicitudes
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="container mt-4">
        <?php if ($current_page == 'dashboard'): ?>
            <!-- Dashboard Content -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2><i class="fas fa-tachometer-alt text-primary"></i> Resumen del Sistema</h2>
                    <p class="text-muted mb-0">Debug: loading = false, recentUpdates.length = <?php echo count($recent_updates); ?></p>
                </div>
                <button class="btn btn-outline-danger" onclick="clearAll()">
                    <i class="fas fa-trash"></i> Limpiar Todo
                </button>
            </div>

            <!-- Stats Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="dashboard-card">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                        <h3><?php echo $stats['total_tickets']; ?></h3>
                        <p>Total Tickets</p>
                        <small><?php echo $stats['open_tickets']; ?> abiertos • <?php echo $stats['closed_tickets']; ?> cerrados</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dashboard-card">
                        <div class="card-icon bg-success">
                            <i class="fas fa-users-cog"></i>
                        </div>
                        <h3><?php echo $stats['admin_users']; ?></h3>
                        <p>Usuarios Admin</p>
                        <small><i class="fas fa-check-circle text-success"></i> <?php echo $stats['active_users']; ?> activos</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dashboard-card">
                        <div class="card-icon bg-purple">
                            <i class="fas fa-box"></i>
                        </div>
                        <h3><?php echo $stats['available_inventory']; ?></h3>
                        <p>Inventario</p>
                        <small><i class="fas fa-magic text-purple"></i> Activos gestionados</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dashboard-card">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <h3><?php echo $stats['pending_requests']; ?></h3>
                        <p>Solicitudes</p>
                        <small><i class="fas fa-clock text-warning"></i> Pendientes</small>
                    </div>
                </div>
            </div>

            <!-- Recent Updates -->
            <div class="recent-updates">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5><i class="fas fa-sync-alt text-primary"></i> Actualizaciones Recientes</h5>
                    <span class="badge bg-secondary"><?php echo count($recent_updates); ?></span>
                </div>

                <?php foreach ($recent_updates as $update): ?>
                <div class="update-item">
                    <div class="update-icon bg-primary">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <div class="update-content">
                        <h6><?php echo htmlspecialchars($update['title']); ?></h6>
                        <p><i class="fas fa-user"></i> <?php echo htmlspecialchars($update['user_name']); ?></p>
                    </div>
                    <div class="update-meta">
                        <div class="mb-2">
                            <span class="priority-badge priority-<?php echo $update['priority']; ?>">
                                <?php echo $update['priority']; ?>
                            </span>
                            <span class="status-badge status-<?php echo str_replace('_', '-', $update['status']); ?>">
                                <?php echo ucfirst(str_replace('_', ' ', $update['status'])); ?>
                            </span>
                        </div>
                        <small class="text-muted">
                            <?php echo date('d/m, H:i', strtotime($update['updated_at'])); ?>
                            <i class="fas fa-clock ms-1"></i>
                        </small>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

        <?php elseif ($current_page == 'tickets'): ?>
            <!-- Tickets Management -->
            <h2><i class="fas fa-ticket-alt text-primary"></i> Gestión de Tickets</h2>
            <p class="text-muted">Administra todos los tickets de soporte del sistema</p>
            
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> 
                Funcionalidad en desarrollo. Aquí se mostrará la lista completa de tickets con opciones de filtrado y gestión.
            </div>

        <?php elseif ($current_page == 'inventory'): ?>
            <!-- Inventory Management -->
            <h2><i class="fas fa-box text-primary"></i> Gestión de Inventario</h2>
            <p class="text-muted">Administra el inventario de equipos y recursos IT</p>
            
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> 
                Funcionalidad en desarrollo. Aquí se mostrará el inventario completo con opciones de gestión.
            </div>

        <?php elseif ($current_page == 'users'): ?>
            <!-- Users Management -->
            <h2><i class="fas fa-users text-primary"></i> Gestión de Usuarios</h2>
            <p class="text-muted">Administra usuarios y permisos del sistema</p>
            
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> 
                Funcionalidad en desarrollo. Aquí se mostrará la gestión completa de usuarios.
            </div>

        <?php elseif ($current_page == 'notifications'): ?>
            <!-- Notifications Management -->
            <h2><i class="fas fa-bell text-primary"></i> Gestión de Notificaciones</h2>
            <p class="text-muted">Administra las notificaciones del sistema</p>
            
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> 
                Funcionalidad en desarrollo. Aquí se mostrará la gestión de notificaciones.
            </div>

        <?php elseif ($current_page == 'reports'): ?>
            <!-- Reports -->
            <h2><i class="fas fa-chart-bar text-primary"></i> Reportes</h2>
            <p class="text-muted">Visualiza estadísticas y reportes del sistema</p>
            
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> 
                Funcionalidad en desarrollo. Aquí se mostrarán reportes detallados y estadísticas.
            </div>

        <?php elseif ($current_page == 'requests'): ?>
            <!-- Access Requests -->
            <h2><i class="fas fa-user-plus text-primary"></i> Solicitudes de Acceso</h2>
            <p class="text-muted">Gestiona las solicitudes de acceso al sistema</p>
            
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> 
                Funcionalidad en desarrollo. Aquí se mostrarán las solicitudes de acceso pendientes.
            </div>

        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
    <script>
        function clearAll() {
            if (confirm('¿Estás seguro de que quieres limpiar todos los datos? Esta acción no se puede deshacer.')) {
                // Aquí iría la lógica para limpiar datos
                alert('Funcionalidad de limpieza en desarrollo');
            }
        }

        // Actualizar estadísticas en tiempo real
        function updateStats() {
            // Aquí iría la lógica AJAX para actualizar estadísticas
            console.log('Actualizando estadísticas...');
        }

        // Actualizar cada 30 segundos
        setInterval(updateStats, 30000);
    </script>

    <style>
        .bg-purple {
            background: linear-gradient(135deg, #667eea, #764ba2) !important;
        }
        .text-purple {
            color: #764ba2 !important;
        }
    </style>
</body>
</html> 