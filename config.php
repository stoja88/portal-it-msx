<?php
// Configuración de la base de datos
// Para Railway, usamos variables de entorno

// Inicializar variables por defecto
$db_host = 'localhost';
$db_name = 'portal_it';
$db_user = 'root';
$db_pass = '';
$db_port = 3306;

// Si estamos en Railway, usar la URL de la base de datos
if (isset($_ENV['DATABASE_URL']) && !empty($_ENV['DATABASE_URL'])) {
    $db_url = parse_url($_ENV['DATABASE_URL']);
    if ($db_url !== false) {
        $db_host = $db_url['host'] ?? $db_host;
        $db_name = isset($db_url['path']) ? ltrim($db_url['path'], '/') : $db_name;
        $db_user = $db_url['user'] ?? $db_user;
        $db_pass = $db_url['pass'] ?? $db_pass;
        $db_port = $db_url['port'] ?? 3306;
    }
} else {
    // Usar variables individuales como fallback
    $db_host = $_ENV['DB_HOST'] ?? $_ENV['MYSQL_HOST'] ?? $db_host;
    $db_name = $_ENV['DB_NAME'] ?? $_ENV['MYSQL_DATABASE'] ?? $db_name;
    $db_user = $_ENV['DB_USER'] ?? $_ENV['MYSQL_USER'] ?? $db_user;
    $db_pass = $_ENV['DB_PASS'] ?? $_ENV['MYSQL_PASSWORD'] ?? $db_pass;
    $db_port = $_ENV['DB_PORT'] ?? $_ENV['MYSQL_PORT'] ?? $db_port;
}

// Definir constantes una sola vez
define('DB_HOST', $db_host);
define('DB_NAME', $db_name);
define('DB_USER', $db_user);
define('DB_PASS', $db_pass);

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
    if (isset($_ENV['RAILWAY_ENVIRONMENT']) && $_ENV['RAILWAY_ENVIRONMENT']) {
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