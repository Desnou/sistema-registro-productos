-- ============================================================
-- 02_datos_prueba.sql
-- Datos iniciales para los catálogos del formulario.
-- Ejecutar después de 01_esquema.sql
-- ============================================================

-- Bodegas
INSERT INTO bodega (nombre) VALUES
    ('Bodega 1'),
    ('Bodega 2');

-- Sucursales (asociadas a cada bodega)
INSERT INTO sucursal (id_bodega, nombre) VALUES
    (1, 'Sucursal 1'),
    (1, 'Sucursal 2'),
    (2, 'Sucursal 3');

-- Monedas
INSERT INTO moneda (nombre) VALUES
    ('DÓLAR');

-- Materiales del producto
INSERT INTO material (nombre) VALUES
    ('Plástico'),
    ('Metal'),
    ('Madera'),
    ('Vidrio'),
    ('Textil');
