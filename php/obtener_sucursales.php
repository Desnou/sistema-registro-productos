<?php
// ============================================================
// obtener_sucursales.php
// Recibe id_bodega por GET y devuelve JSON con las sucursales
// asociadas a esa bodega.
// ============================================================

require_once __DIR__ . '/conexion.php';

header('Content-Type: application/json; charset=utf-8');

$idBodega = filter_input(INPUT_GET, 'id_bodega', FILTER_VALIDATE_INT);

if ($idBodega === false || $idBodega === null || $idBodega <= 0) {
    http_response_code(400);
    echo json_encode([
        'exito'   => false,
        'mensaje' => 'Debe indicar una bodega válida.',
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $stmt = $pdo->prepare('SELECT id_sucursal, nombre FROM sucursal WHERE id_bodega = :id_bodega ORDER BY nombre');
    $stmt->execute([':id_bodega' => $idBodega]);
    echo json_encode($stmt->fetchAll(), JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'exito'   => false,
        'mensaje' => 'Error al obtener las sucursales.',
    ], JSON_UNESCAPED_UNICODE);
    error_log('obtener_sucursales.php: ' . $e->getMessage());
}
