<?php
require_once 'config.php';

$success = '';
$error = '';

if ($_POST) {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $category = $_POST['category'] ?? 'other';
    $priority = $_POST['priority'] ?? 'medium';
    $user_name = $_POST['user_name'] ?? '';
    $user_email = $_POST['user_email'] ?? '';
    
    if ($title && $description && $user_name && $user_email) {
        try {
            // Crear o buscar usuario
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$user_email]);
            $user = $stmt->fetch();
            
            if (!$user) {
                // Crear nuevo usuario
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password, full_name, role) VALUES (?, ?, ?, ?, 'user')");
                $username = strtolower(str_replace(' ', '.', $user_name));
                $stmt->execute([$username, $user_email, password_hash('password123', PASSWORD_DEFAULT), $user_name]);
                $user_id = $pdo->lastInsertId();
            } else {
                $user_id = $user['id'];
            }
            
            // Crear ticket
            $stmt = $pdo->prepare("INSERT INTO tickets (user_id, title, description, category, priority, status) VALUES (?, ?, ?, ?, ?, 'open')");
            $stmt->execute([$user_id, $title, $description, $category, $priority]);
            
            $success = 'Ticket creado exitosamente. Te contactaremos pronto.';
            $_POST = []; // Limpiar formulario
        } catch (Exception $e) {
            $error = 'Error al crear el ticket. Por favor intenta nuevamente.';
        }
    } else {
        $error = 'Por favor complete todos los campos obligatorios.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Ticket de Soporte - MSX International</title>
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
                <a href="index.php" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left"></i> Volver al Portal
                </a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="service-card">
                    <div class="text-center mb-4">
                        <div class="service-icon support-icon mx-auto mb-3">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h2>Crear Ticket de Soporte</h2>
                        <p class="text-muted">Reporta tu incidencia técnica y te ayudaremos a resolverla</p>
                    </div>

                    <?php if ($success): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="user_name" class="form-label">
                                        <i class="fas fa-user text-primary"></i> Nombre Completo *
                                    </label>
                                    <input type="text" class="form-control" id="user_name" name="user_name" 
                                           value="<?php echo htmlspecialchars($_POST['user_name'] ?? ''); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="user_email" class="form-label">
                                        <i class="fas fa-envelope text-primary"></i> Email Corporativo *
                                    </label>
                                    <input type="email" class="form-control" id="user_email" name="user_email" 
                                           value="<?php echo htmlspecialchars($_POST['user_email'] ?? ''); ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category" class="form-label">
                                        <i class="fas fa-tags text-primary"></i> Categoría
                                    </label>
                                    <select class="form-select" id="category" name="category">
                                        <option value="hardware" <?php echo ($_POST['category'] ?? '') == 'hardware' ? 'selected' : ''; ?>>Hardware</option>
                                        <option value="software" <?php echo ($_POST['category'] ?? '') == 'software' ? 'selected' : ''; ?>>Software</option>
                                        <option value="network" <?php echo ($_POST['category'] ?? '') == 'network' ? 'selected' : ''; ?>>Red/Conectividad</option>
                                        <option value="access" <?php echo ($_POST['category'] ?? '') == 'access' ? 'selected' : ''; ?>>Accesos</option>
                                        <option value="other" <?php echo ($_POST['category'] ?? 'other') == 'other' ? 'selected' : ''; ?>>Otro</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="priority" class="form-label">
                                        <i class="fas fa-exclamation-triangle text-primary"></i> Prioridad
                                    </label>
                                    <select class="form-select" id="priority" name="priority">
                                        <option value="low" <?php echo ($_POST['priority'] ?? '') == 'low' ? 'selected' : ''; ?>>Baja</option>
                                        <option value="medium" <?php echo ($_POST['priority'] ?? 'medium') == 'medium' ? 'selected' : ''; ?>>Media</option>
                                        <option value="high" <?php echo ($_POST['priority'] ?? '') == 'high' ? 'selected' : ''; ?>>Alta</option>
                                        <option value="critical" <?php echo ($_POST['priority'] ?? '') == 'critical' ? 'selected' : ''; ?>>Crítica</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label">
                                <i class="fas fa-file-alt text-primary"></i> Título del Problema *
                            </label>
                            <input type="text" class="form-control" id="title" name="title" 
                                   placeholder="Ej: No funciona el correo electrónico"
                                   value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>" required>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left text-primary"></i> Descripción Detallada *
                            </label>
                            <textarea class="form-control" id="description" name="description" rows="6" 
                                      placeholder="Describe el problema con el mayor detalle posible. Incluye pasos para reproducir el error, mensajes de error, etc."
                                      required><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                            <a href="index.php" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-paper-plane"></i> Crear Ticket
                            </button>
                        </div>
                    </form>
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
</body>
</html> 