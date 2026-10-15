/* ============================================================
   app.js
   Cargas dinámicas (AJAX con fetch) y envío del formulario.
   JavaScript nativo (sin frameworks).
   ============================================================ */

'use strict';

document.addEventListener('DOMContentLoaded', function () {

    var formulario = document.getElementById('formulario-producto');
    var campoCodigo = document.getElementById('codigo');
    var campoNombre = document.getElementById('nombre');
    var campoBodega = document.getElementById('bodega');
    var campoSucursal = document.getElementById('sucursal');
    var campoMoneda = document.getElementById('moneda');
    var campoPrecio = document.getElementById('precio');
    var campoDescripcion = document.getElementById('descripcion');
    var selectorMateriales = 'input[name="materiales[]"]:checked';

    // ---------- Utilidades ----------

    function opcionEnBlanco(select, texto) {
        select.innerHTML = '';
        var opcion = document.createElement('option');
        opcion.value = '';
        opcion.textContent = texto;
        select.appendChild(opcion);
    }

    function poblarSelect(select, items, nombreId, textoPorDefecto) {
        opcionEnBlanco(select, textoPorDefecto);
        items.forEach(function (item) {
            var opcion = document.createElement('option');
            opcion.value = item[nombreId];
            opcion.textContent = item.nombre;
            select.appendChild(opcion);
        });
    }

    // ---------- Cargas dinámicas ----------

    function cargarBodegas() {
        return fetch('php/obtener_bodegas.php')
            .then(function (respuesta) {
                return respuesta.json();
            })
            .then(function (datos) {
                if (!Array.isArray(datos)) {
                    throw new Error('Respuesta inválida');
                }
                poblarSelect(campoBodega, datos, 'id_bodega', 'Seleccione...');
            })
            .catch(function () {
                alert('No fue posible cargar la lista de bodegas.');
            });
    }

    function cargarMonedas() {
        return fetch('php/obtener_monedas.php')
            .then(function (respuesta) {
                return respuesta.json();
            })
            .then(function (datos) {
                if (!Array.isArray(datos)) {
                    throw new Error('Respuesta inválida');
                }
                poblarSelect(campoMoneda, datos, 'id_moneda', 'Seleccione...');
            })
            .catch(function () {
                alert('No fue posible cargar la lista de monedas.');
            });
    }

    function cargarSucursales(idBodega) {
        return fetch('php/obtener_sucursales.php?id_bodega=' + encodeURIComponent(idBodega))
            .then(function (respuesta) {
                return respuesta.json();
            })
            .then(function (datos) {
                if (!Array.isArray(datos)) {
                    throw new Error('Respuesta inválida');
                }
                poblarSelect(campoSucursal, datos, 'id_sucursal', 'Seleccione...');
                campoSucursal.disabled = false;
            })
            .catch(function () {
                campoSucursal.disabled = true;
                alert('No fue posible cargar la lista de sucursales.');
            });
    }

    // ---------- Evento change en Bodega ----------

    campoBodega.addEventListener('change', function () {
        var idBodega = campoBodega.value;

        opcionEnBlanco(campoSucursal, 'Seleccione...');
        campoSucursal.disabled = true;

        if (idBodega !== '') {
            cargarSucursales(idBodega);
        }
    });

    // ---------- Evento submit del formulario ----------

    formulario.addEventListener('submit', function (evento) {
        evento.preventDefault();

        var datos = {
            codigo: campoCodigo.value,
            nombre: campoNombre.value,
            bodega: campoBodega.value,
            sucursal: campoSucursal.value,
            moneda: campoMoneda.value,
            precio: campoPrecio.value,
            cantidadMateriales: document.querySelectorAll(selectorMateriales).length,
            descripcion: campoDescripcion.value
        };

        if (!validarFormulario(datos)) {
            return;
        }

        var datosEnvio = new FormData(formulario);

        fetch('php/guardar_producto.php', {
            method: 'POST',
            body: datosEnvio
        })
            .then(function (respuesta) {
                return respuesta.json();
            })
            .then(function (respuesta) {
                alert(respuesta.mensaje);

                if (respuesta.exito === true) {
                    formulario.reset();
                    opcionEnBlanco(campoSucursal, 'Seleccione...');
                    campoSucursal.disabled = true;
                }
            })
            .catch(function () {
                alert('No fue posible guardar el producto. Intente nuevamente.');
            });
    });

    // ---------- Cargas iniciales ----------

    cargarBodegas();
    cargarMonedas();
});
