<?php
// Configuración de la base de datos
// Para Railway, usamos variables de entorno
define('DB_HOST', $_ENV['DB_HOST'] ?? $_ENV['MYSQL_HOST'] ?? 'localhost');
define('DB_NAME', $_ENV['DB_NAME'] ?? $_ENV['MYSQL_DATABASE'] ?? 'portal_it');
define('DB_USER', $_ENV['DB_USER'] ?? $_ENV['MYSQL_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? $_ENV['MYSQL_PASSWORD'] ?? '');

// Si estamos en Railway, usar la URL de la base de datos
if (isset($_ENV['DATABASE_URL'])) {
    $db_url = parse_url($_ENV['DATABASE_URL']);
    define('DB_HOST', $db_url['host']);
    define('DB_NAME', ltrim($db_url['path'], '/'));
    define('DB_USER', $db_url['user']);
    define('DB_PASS', $db_url['pass']);
    $db_port = $db_url['port'] ?? 3306;
} else {
    $db_port = $_ENV['DB_PORT'] ?? $_ENV['MYSQL_PORT'] ?? 3306;
}

// Configuración del sitio
define('SITE_NAME', 'MSX International - Portal IT');
define('SITE_URL', $_ENV['RAILWAY_STATIC_URL'] ?? $_ENV['SITE_URL'] ?? 'http://localhost');

// Conectar a la base de datos
try {
    $dsn = "mysql:host=" . DB_HOST . ";port=" . $db_port . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
    ]);
} catch(PDOException $e) {
    // En desarrollo, mostrar error. En producción, log silencioso
    if ($_ENV['RAILWAY_ENVIRONMENT'] ?? false) {
        error_log("Error de conexión: " . $e->getMessage());
        die("Error de conexión a la base de datos. Por favor contacta al administrador.");
    } else {
        die("Error de conexión: " . $e->getMessage());
    }
}

// Iniciar sesión
session_start();

// Función para verificar si el usuario está logueado
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Función para verificar si el usuario es admin
function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

// Función para redirigir
function redirect($url) {
    header("Location: $url");
    exit();
}

// Función para mostrar errores en desarrollo
function showError($message, $isProduction = null) {
    if ($isProduction === null) {
        $isProduction = isset($_ENV['RAILWAY_ENVIRONMENT']);
    }
    
    if ($isProduction) {
        error_log($message);
        return "Ha ocurrido un error. Por favor contacta al administrador.";
    } else {
        return $message;
    }
}
?> 