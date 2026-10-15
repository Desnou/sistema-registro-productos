-- ============================================================
-- 02_datos_prueba.sql
-- Datos iniciales para los catálogos del formulario.
-- Ejecutar después de 01_esquema.sql
-- ============================================================

-- Bodegas
INSERT INTO bodega (nombre) VALUES
    ('Bodega Central'),
    ('Bodega Norte'),
    ('Bodega Sur');

-- Sucursales (vinculadas a cada bodega)
INSERT INTO sucursal (id_bodega, nombre) VALUES
    -- Bodega Central (id_bodega = 1)
    (1, 'Sucursal Santiago Centro'),
    (1, 'Sucursal Providencia'),
    -- Bodega Norte (id_bodega = 2)
    (2, 'Sucursal Antofagasta'),
    (2, 'Sucursal Iquique'),
    -- Bodega Sur (id_bodega = 3)
    (3, 'Sucursal Concepción'),
    (3, 'Sucursal Puerto Montt');

-- Monedas
INSERT INTO moneda (nombre) VALUES
    ('PESO CHILENO (CLP)'),
    ('DÓLAR (USD)'),
    ('EURO (EUR)');

-- Materiales del producto
INSERT INTO material (nombre) VALUES
    ('Plástico'),
    ('Metal'),
    ('Madera'),
    ('Vidrio'),
    ('Textil');
