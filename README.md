# 🛒 SISTEMA DE REGISTRO DE PRODUCTOS

Sistema web de registro de productos desarrollado con **HTML5, CSS3 nativo, JavaScript (ES6+ / Fetch AJAX) y PHP 8.3** con base de datos **PostgreSQL 18.2**, sin dependencias de frameworks ni librerías externas.

---

## 🚀 Tecnologías y Versiones
- **Frontend:** HTML5, CSS3 nativo, JavaScript puro (sin frameworks).
- **Backend:** PHP 8.3.33 (PDO PostgreSQL, consultas preparadas nativas).
- **Base de Datos:** PostgreSQL 18.2.
- **Comunicación:** AJAX mediante `fetch()`.

---

## ⚡ Requisitos Previos
- PHP 8.3+ con la extensión `pdo_pgsql` habilitada.
- PostgreSQL 18+ en ejecución.

---

## 🛠️ Instalación y Configuración

### Opción A: Desde la Terminal (psql / CLI)

1. **Crear la base de datos:**
   ```bash
   psql -U postgres -c "CREATE DATABASE registro_productos;"
   ```

2. **Importar el esquema y los datos iniciales:**
   ```bash
   psql -U postgres -d registro_productos -f sql/01_esquema.sql
   psql -U postgres -d registro_productos -f sql/02_datos_prueba.sql
   ```

3. **Configurar credenciales (si aplica):**
   Por defecto, `php/conexion.php` utiliza usuario `postgres` y clave vacía (`''`). Si tu entorno requiere otra clave, edítala en `php/conexion.php` o crea opcionalmente `php/config.local.php`.

4. **Levantar el servidor PHP:**
   ```bash
   php -S localhost:8000
   ```

5. **Abrir en el navegador:**
   http://localhost:8000/index.html

### Opción B: Desde pgAdmin (GUI)

1. Abrir pgAdmin y conectarse al servidor de PostgreSQL.
2. Hacer clic derecho en **Databases -> Create -> Database...**, nombrarla `registro_productos` y guardar.
3. Seleccionar la base de datos `registro_productos` y abrir la **Query Tool** (Herramienta de Consultas).
4. Abrir y ejecutar el archivo `sql/01_esquema.sql` (F5).
5. Abrir y ejecutar el archivo `sql/02_datos_prueba.sql` (F5).
6. Levantar el servidor PHP en la terminal (`php -S localhost:8000`) y abrir http://localhost:8000/index.html.

---

## 🖥️ Windows sin PATH (Rutas completas)

Si los comandos `php` o `psql` no están en el PATH del sistema:

```powershell
# Importar scripts SQL
& "C:\Program Files\PostgreSQL\18\bin\psql.exe" -U postgres -c "CREATE DATABASE registro_productos;"
& "C:\Program Files\PostgreSQL\18\bin\psql.exe" -U postgres -d registro_productos -f sql\01_esquema.sql
& "C:\Program Files\PostgreSQL\18\bin\psql.exe" -U postgres -d registro_productos -f sql\02_datos_prueba.sql

# Levantar servidor PHP (ejemplo XAMPP o nativo)
& "C:\xampp\php\php.exe" -S localhost:8000
```

---

## 🧪 Cómo Probar el Sistema (Prueba Rápida)

1. Abrir http://localhost:8000/index.html.
2. Verificar que los desplegables de Bodega y Moneda carguen dinámicamente.
3. Seleccionar una Bodega: el select de Sucursal se habilitará automáticamente con sus opciones.
4. Seleccionar al menos 2 materiales.
5. Completar el formulario y presionar **Guardar Producto**: aparecerá la alerta "Producto guardado correctamente.".
6. Enviar nuevamente el mismo código: aparecerá la alerta "El código del producto ya está registrado.".

---

## 📂 Estructura del Proyecto

```plaintext
├── index.html             # Interfaz web del formulario
├── css/
│   └── estilos.css        # Estilos CSS nativos
├── js/
│   ├── validaciones.js    # Reglas de validación en cliente
│   └── app.js             # Cargas dinámicas AJAX y envío de formulario
├── php/
│   ├── conexion.php       # Conexión PDO a PostgreSQL
│   ├── guardar_producto.php# Validación autoritativa y transacción SQL
│   ├── obtener_bodegas.php# Endpoint JSON bodegas
│   ├── obtener_monedas.php# Endpoint JSON monedas
│   └── obtener_sucursales.php# Endpoint JSON sucursales
├── sql/
│   ├── 01_esquema.sql     # Tablas y restricciones SQL
│   └── 02_datos_prueba.sql# Semillas iniciales
├── LEEME.txt              # Instrucciones en texto plano
└── README.md              # Documentación principal en GitHub
```

---

## ❓ Solución de Problemas

- **Error de conexión:** Revisar credenciales en `php/conexion.php`.
- **Selects vacíos:** Verificar la importación de `sql/01_esquema.sql` y `sql/02_datos_prueba.sql`.
- **Extensión de PHP:** Confirmar con `php -m` que `pdo_pgsql` está activa.
