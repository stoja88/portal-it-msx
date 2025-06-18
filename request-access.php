<?php
require_once 'config.php';

$success = '';
$error = '';

if ($_POST) {
    $system_name = $_POST['system_name'] ?? '';
    $access_type = $_POST['access_type'] ?? 'read';
    $justification = $_POST['justification'] ?? '';
    $user_name = $_POST['user_name'] ?? '';
    $user_email = $_POST['user_email'] ?? '';
    
    if ($system_name && $justification && $user_name && $user_email) {
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
            
            // Crear solicitud de acceso
            $stmt = $pdo->prepare("INSERT INTO access_requests (user_id, system_name, access_type, justification, status) VALUES (?, ?, ?, ?, 'pending')");
            $stmt->execute([$user_id, $system_name, $access_type, $justification]);
            
            $success = 'Solicitud de acceso enviada exitosamente. Será revisada por el equipo de IT.';
            $_POST = []; // Limpiar formulario
        } catch (Exception $e) {
            $error = 'Error al crear la solicitud. Por favor intenta nuevamente.';
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
    <title>Solicitar Acceso - MSX International</title>
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
                        <div class="service-icon access-icon mx-auto mb-3">
                            <i class="fas fa-lock"></i>
                        </div>
                        <h2>Solicitar Acceso al Sistema</h2>
                        <p class="text-muted">Solicita permisos de acceso a sistemas y aplicaciones corporativas</p>
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
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="system_name" class="form-label">
                                        <i class="fas fa-server text-primary"></i> Sistema/Aplicación *
                                    </label>
                                    <input type="text" class="form-control" id="system_name" name="system_name" 
                                           placeholder="Ej: SAP, CRM, Base de datos, etc."
                                           value="<?php echo htmlspecialchars($_POST['system_name'] ?? ''); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="access_type" class="form-label">
                                        <i class="fas fa-key text-primary"></i> Tipo de Acceso
                                    </label>
                                    <select class="form-select" id="access_type" name="access_type">
                                        <option value="read" <?php echo ($_POST['access_type'] ?? 'read') == 'read' ? 'selected' : ''; ?>>Solo Lectura</option>
                                        <option value="write" <?php echo ($_POST['access_type'] ?? '') == 'write' ? 'selected' : ''; ?>>Lectura y Escritura</option>
                                        <option value="admin" <?php echo ($_POST['access_type'] ?? '') == 'admin' ? 'selected' : ''; ?>>Administrador</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="justification" class="form-label">
                                <i class="fas fa-clipboard-list text-primary"></i> Justificación del Acceso *
                            </label>
                            <textarea class="form-control" id="justification" name="justification" rows="6" 
                                      placeholder="Explica por qué necesitas acceso a este sistema, qué actividades realizarás, el período de tiempo necesario, etc."
                                      required><?php echo htmlspecialchars($_POST['justification'] ?? ''); ?></textarea>
                            <div class="form-text">
                                <i class="fas fa-info-circle"></i> 
                                Incluye detalles sobre tu rol, el proyecto para el que necesitas acceso, y cualquier aprobación previa.
                            </div>
                        </div>

                        <div class="alert alert-warning">
                            <h6><i class="fas fa-exclamation-triangle"></i> Importante:</h6>
                            <ul class="mb-0">
                                <li>Todas las solicitudes serán revisadas por el equipo de seguridad IT</li>
                                <li>El acceso será otorgado según las políticas de seguridad de MSX International</li>
                                <li>Recibirás una notificación por email sobre el estado de tu solicitud</li>
                                <li>Los accesos temporales tienen fecha de expiración automática</li>
                            </ul>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                            <a href="index.php" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane"></i> Enviar Solicitud
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