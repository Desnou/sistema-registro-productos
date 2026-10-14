<?php
// ============================================================
// obtener_bodegas.php
// Devuelve JSON con la lista de bodegas disponibles.
// ============================================================

require_once __DIR__ . '/conexion.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $stmt = $pdo->query('SELECT id_bodega, nombre FROM bodega ORDER BY nombre');
    echo json_encode($stmt->fetchAll(), JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'exito'   => false,
        'mensaje' => 'Error al obtener las bodegas.',
    ], JSON_UNESCAPED_UNICODE);
    error_log('obtener_bodegas.php: ' . $e->getMessage());
}
