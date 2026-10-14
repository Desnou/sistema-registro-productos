<?php
// ============================================================
// guardar_producto.php
// Endpoint POST que recibe los datos del formulario, los revalida
// en el servidor, verifica la unicidad del código en la base de
// datos e inserta el producto y sus materiales en una transacción.
// Responde JSON: { "exito": true|false, "mensaje": "..." }
// ============================================================

require_once __DIR__ . '/conexion.php';

header('Content-Type: application/json; charset=utf-8');

function responder(bool $exito, string $mensaje, int $codigoHttp = 200): void
{
    http_response_code($codigoHttp);
    echo json_encode(['exito' => $exito, 'mensaje' => $mensaje], JSON_UNESCAPED_UNICODE);
    exit;
}

function limpiar_texto($valor): string
{
    if (!is_scalar($valor)) {
        return '';
    }
    return trim((string) $valor);
}

function entero_positivo($valor): ?int
{
    $n = filter_var($valor, FILTER_VALIDATE_INT);
    return ($n === false || $n === null || $n <= 0) ? null : (int) $n;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    responder(false, 'Método no permitido.', 405);
}

// ---------- Lectura de datos ----------
$codigo      = limpiar_texto($_POST['codigo'] ?? '');
$nombre      = limpiar_texto($_POST['nombre'] ?? '');
$idBodega    = entero_positivo($_POST['bodega'] ?? null);
$idSucursal  = entero_positivo($_POST['sucursal'] ?? null);
$idMoneda    = entero_positivo($_POST['moneda'] ?? null);
$precio      = limpiar_texto($_POST['precio'] ?? '');
$descripcion = limpiar_texto($_POST['descripcion'] ?? '');

$materiales = $_POST['materiales'] ?? [];
if (!is_array($materiales)) {
    $materiales = [$materiales];
}
$idsMateriales = [];
foreach ($materiales as $m) {
    $id = entero_positivo($m);
    if ($id !== null) {
        $idsMateriales[] = $id;
    }
}
$idsMateriales = array_values(array_unique($idsMateriales));

// ---------- Validaciones (mismo orden y mensajes que en JavaScript) ----------

// Código del producto
if ($codigo === '') {
    responder(false, 'El código del producto no puede estar en blanco.', 400);
}
if (mb_strlen($codigo, 'UTF-8') < 5 || mb_strlen($codigo, 'UTF-8') > 15) {
    responder(false, 'El código del producto debe tener entre 5 y 15 caracteres.', 400);
}
if (preg_match('/^(?=.*[A-Za-z])(?=.*[0-9])[A-Za-z0-9]{5,15}$/', $codigo) !== 1) {
    responder(false, 'El código del producto debe contener letras y números', 400);
}

// Nombre del producto
if ($nombre === '') {
    responder(false, 'El nombre del producto no puede estar en blanco.', 400);
}
if (mb_strlen($nombre, 'UTF-8') < 2 || mb_strlen($nombre, 'UTF-8') > 50) {
    responder(false, 'El nombre del producto debe tener entre 2 y 50 caracteres.', 400);
}

// Bodega
if ($idBodega === null) {
    responder(false, 'Debe seleccionar una bodega.', 400);
}

// Sucursal
if ($idSucursal === null) {
    responder(false, 'Debe seleccionar una sucursal para la bodega seleccionada.', 400);
}

// Moneda
if ($idMoneda === null) {
    responder(false, 'Debe seleccionar una moneda para el producto.', 400);
}

// Precio
if ($precio === '') {
    responder(false, 'El precio del producto no puede estar en blanco.', 400);
}
if (preg_match('/^\d+(\.\d{1,2})?$/', $precio) !== 1 || (float) $precio <= 0) {
    responder(false, 'El precio del producto debe ser un número positivo con hasta dos decimales.', 400);
}

// Materiales (mínimo dos)
if (count($idsMateriales) < 2) {
    responder(false, 'Debe seleccionar al menos dos materiales para el producto.', 400);
}

// Descripción
if ($descripcion === '') {
    responder(false, 'La descripción del producto no puede estar en blanco.', 400);
}
if (mb_strlen($descripcion, 'UTF-8') < 10 || mb_strlen($descripcion, 'UTF-8') > 1000) {
    responder(false, 'La descripción del producto debe tener entre 10 y 1000 caracteres.', 400);
}

// ---------- Verificación de existencia en base de datos ----------
try {
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM bodega WHERE id_bodega = :id');
    $stmt->execute([':id' => $idBodega]);
    if ((int) $stmt->fetchColumn() === 0) {
        responder(false, 'Debe seleccionar una bodega.', 400);
    }

    $stmt = $pdo->prepare('SELECT COUNT(*) FROM moneda WHERE id_moneda = :id');
    $stmt->execute([':id' => $idMoneda]);
    if ((int) $stmt->fetchColumn() === 0) {
        responder(false, 'Debe seleccionar una moneda para el producto.', 400);
    }

    $stmt = $pdo->prepare('SELECT COUNT(*) FROM sucursal WHERE id_sucursal = :sucursal AND id_bodega = :bodega');
    $stmt->execute([':sucursal' => $idSucursal, ':bodega' => $idBodega]);
    if ((int) $stmt->fetchColumn() === 0) {
        responder(false, 'Debe seleccionar una sucursal para la bodega seleccionada.', 400);
    }

    $marcadores = implode(',', array_fill(0, count($idsMateriales), '?'));
    $stmt = $pdo->prepare("SELECT id_material FROM material WHERE id_material IN ($marcadores)");
    $stmt->execute($idsMateriales);
    $materialesValidos = array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN, 0));
    $materialesValidos = array_values(array_intersect($idsMateriales, $materialesValidos));
    if (count($materialesValidos) < 2) {
        responder(false, 'Debe seleccionar al menos dos materiales para el producto.', 400);
    }

    // ---------- Verificación de unicidad del código ----------
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM producto WHERE codigo = :codigo');
    $stmt->execute([':codigo' => $codigo]);
    if ((int) $stmt->fetchColumn() > 0) {
        responder(false, 'El código del producto ya está registrado.', 409);
    }

    // ---------- Inserción en transacción ----------
    $pdo->beginTransaction();

    $stmtProducto = $pdo->prepare(
        'INSERT INTO producto (codigo, nombre, id_bodega, id_sucursal, id_moneda, precio, descripcion)
         VALUES (:codigo, :nombre, :bodega, :sucursal, :moneda, :precio, :descripcion)
         RETURNING id_producto'
    );
    $stmtProducto->execute([
        ':codigo'      => $codigo,
        ':nombre'      => $nombre,
        ':bodega'      => $idBodega,
        ':sucursal'    => $idSucursal,
        ':moneda'      => $idMoneda,
        ':precio'      => $precio,
        ':descripcion' => $descripcion,
    ]);
    $idProducto = (int) $stmtProducto->fetchColumn();

    $stmtProductoMaterial = $pdo->prepare(
        'INSERT INTO producto_material (id_producto, id_material) VALUES (:producto, :material)'
    );
    foreach ($materialesValidos as $idMaterial) {
        $stmtProductoMaterial->execute([
            ':producto' => $idProducto,
            ':material' => $idMaterial,
        ]);
    }

    $pdo->commit();

    responder(true, 'Producto guardado correctamente.');
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    // 23505 = unique_violation en PostgreSQL
    if ((string) $e->getCode() === '23505') {
        responder(false, 'El código del producto ya está registrado.', 409);
    }

    error_log('guardar_producto.php: ' . $e->getMessage());
    responder(false, 'No fue posible guardar el producto. Intente nuevamente.', 500);
}
