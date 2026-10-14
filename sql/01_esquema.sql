-- ============================================================
-- 01_esquema.sql
-- Esquema de la base de datos del Sistema de Registro de Productos
-- PostgreSQL 18
-- ============================================================

-- ---------- Catálogo: bodegas ----------
CREATE TABLE IF NOT EXISTS bodega (
    id_bodega  SERIAL       PRIMARY KEY,
    nombre     VARCHAR(100) NOT NULL UNIQUE
);

-- ---------- Catálogo: sucursales (dependen de una bodega) ----------
CREATE TABLE IF NOT EXISTS sucursal (
    id_sucursal SERIAL       PRIMARY KEY,
    id_bodega   INTEGER      NOT NULL REFERENCES bodega (id_bodega),
    nombre      VARCHAR(100) NOT NULL,
    CONSTRAINT uq_sucursal_bodega_nombre UNIQUE (id_bodega, nombre)
);

-- ---------- Catálogo: monedas ----------
CREATE TABLE IF NOT EXISTS moneda (
    id_moneda SERIAL       PRIMARY KEY,
    nombre    VARCHAR(50)  NOT NULL UNIQUE
);

-- ---------- Catálogo: materiales ----------
CREATE TABLE IF NOT EXISTS material (
    id_material SERIAL      PRIMARY KEY,
    nombre      VARCHAR(50) NOT NULL UNIQUE
);

-- ---------- Productos ----------
CREATE TABLE IF NOT EXISTS producto (
    id_producto   SERIAL        PRIMARY KEY,
    codigo        VARCHAR(15)   NOT NULL UNIQUE,
    nombre        VARCHAR(50)   NOT NULL,
    id_bodega     INTEGER       NOT NULL REFERENCES bodega (id_bodega),
    id_sucursal   INTEGER       NOT NULL REFERENCES sucursal (id_sucursal),
    id_moneda     INTEGER       NOT NULL REFERENCES moneda (id_moneda),
    precio        NUMERIC(12,2) NOT NULL CHECK (precio > 0),
    descripcion   TEXT          NOT NULL
);

-- ---------- Relación productos - materiales (N:M) ----------
CREATE TABLE IF NOT EXISTS producto_material (
    id_producto INTEGER NOT NULL REFERENCES producto (id_producto) ON DELETE CASCADE,
    id_material INTEGER NOT NULL REFERENCES material (id_material),
    PRIMARY KEY (id_producto, id_material)
);

-- ---------- Índices de apoyo a las consultas del formulario ----------
CREATE INDEX IF NOT EXISTS ix_sucursal_bodega ON sucursal (id_bodega);
CREATE INDEX IF NOT EXISTS ix_producto_material_material ON producto_material (id_material);
