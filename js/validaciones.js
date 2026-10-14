/* ============================================================
   validaciones.js
   Funciones puras de validación para el formulario de productos.
   JavaScript nativo (sin frameworks).
   Cada función devuelve el mensaje de error exacto (string) o null
   si el valor es válido. La secuencia de evaluación es:
   vacío -> longitud -> expresión regular.
   ============================================================ */

'use strict';

// ---------- Reglas de formato (expresiones regulares) ----------
var REGEX_CODIGO = /^(?=.*[A-Za-z])(?=.*[0-9])[A-Za-z0-9]{5,15}$/;
var REGEX_PRECIO = /^\d+(\.\d{1,2})?$/;

// ---------- Código del producto ----------
function validarCodigo(valor) {
    var codigo = valor.trim();

    if (codigo === '') {
        return 'El código del producto no puede estar en blanco.';
    }

    if (codigo.length < 5 || codigo.length > 15) {
        return 'El código del producto debe tener entre 5 y 15 caracteres.';
    }

    if (!REGEX_CODIGO.test(codigo)) {
        return 'El código del producto debe contener letras y números';
    }

    return null;
}

// ---------- Nombre del producto ----------
function validarNombre(valor) {
    var nombre = valor.trim();

    if (nombre === '') {
        return 'El nombre del producto no puede estar en blanco.';
    }

    if (nombre.length < 2 || nombre.length > 50) {
        return 'El nombre del producto debe tener entre 2 y 50 caracteres.';
    }

    return null;
}

// ---------- Bodega ----------
function validarBodega(valor) {
    if (valor.trim() === '') {
        return 'Debe seleccionar una bodega.';
    }

    return null;
}

// ---------- Sucursal ----------
function validarSucursal(valor) {
    if (valor.trim() === '') {
        return 'Debe seleccionar una sucursal para la bodega seleccionada.';
    }

    return null;
}

// ---------- Moneda ----------
function validarMoneda(valor) {
    if (valor.trim() === '') {
        return 'Debe seleccionar una moneda para el producto.';
    }

    return null;
}

// ---------- Precio ----------
function validarPrecio(valor) {
    var precio = valor.trim();

    if (precio === '') {
        return 'El precio del producto no puede estar en blanco.';
    }

    if (!REGEX_PRECIO.test(precio) || parseFloat(precio) <= 0) {
        return 'El precio del producto debe ser un número positivo con hasta dos decimales.';
    }

    return null;
}

// ---------- Material del producto (mínimo dos) ----------
function validarMateriales(cantidadSeleccionados) {
    if (cantidadSeleccionados < 2) {
        return 'Debe seleccionar al menos dos materiales para el producto.';
    }

    return null;
}

// ---------- Descripción ----------
function validarDescripcion(valor) {
    var descripcion = valor.trim();

    if (descripcion === '') {
        return 'La descripción del producto no puede estar en blanco.';
    }

    if (descripcion.length < 10 || descripcion.length > 1000) {
        return 'La descripción del producto debe tener entre 10 y 1000 caracteres.';
    }

    return null;
}

/* ============================================================
   validarFormulario(datos)
   Recibe un objeto con los valores del formulario:
   {
     codigo, nombre, bodega, sucursal, moneda, precio,
     cantidadMateriales, descripcion
   }
   Evalúa los campos en orden; ante el primer error muestra
   alert(mensajeExacto) y devuelve false deteniendo la ejecución.
   Devuelve true si todos los campos son válidos.
   ============================================================ */
function validarFormulario(datos) {
    var verificaciones = [
        validarCodigo(datos.codigo),
        validarNombre(datos.nombre),
        validarBodega(datos.bodega),
        validarSucursal(datos.sucursal),
        validarMoneda(datos.moneda),
        validarPrecio(datos.precio),
        validarMateriales(datos.cantidadMateriales),
        validarDescripcion(datos.descripcion)
    ];

    for (var i = 0; i < verificaciones.length; i++) {
        if (verificaciones[i] !== null) {
            alert(verificaciones[i]);
            return false;
        }
    }

    return true;
}
