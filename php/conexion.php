<?php
// ============================================================
// conexion.php
// Conexión PDO a PostgreSQL para el Sistema de Registro de Productos.
// Configuración por defecto; puede sobrescribirse con credenciales
// locales creando el archivo php/config.local.php (no versionado).
// ============================================================

$config = [
    'host'    => 'localhost',
    'puerto'  => '5432',
    'bd'      => 'registro_productos',
    'usuario' => 'postgres',
    'clave'   => '',
];

$archivoLocal = __DIR__ . '/config.local.php';
if (is_file($archivoLocal)) {
    $configLocal = require $archivoLocal;
    if (is_array($configLocal)) {
        $config = array_merge($config, $configLocal);
    }
}

$dsn = 'pgsql:host=' . $config['host'] . ';port=' . $config['puerto'] . ';dbname=' . $config['bd'];

try {
    $pdo = new PDO($dsn, $config['usuario'], $config['clave'], [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
    $pdo->exec("SET NAMES 'UTF8'");
} catch (PDOException $e) {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'exito'   => false,
        'mensaje' => 'Error de conexión con la base de datos.',
    ], JSON_UNESCAPED_UNICODE);
    error_log('conexion.php: ' . $e->getMessage());
    exit;
}
