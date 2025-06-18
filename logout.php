<?php
require_once 'config.php';

// Destruir toda la sesión
session_destroy();

// Redirigir a la página principal
redirect('index.php');
?> 