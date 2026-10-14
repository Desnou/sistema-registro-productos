# Especificación de la Prueba Técnica: Sistema de Registro de Productos

## 1. Objetivo

Desarrollar un sistema de registro de productos cuya interfaz permita validar los datos de un formulario y guardar los productos en una base de datos.

La implementación debe cumplir los requisitos descritos en este documento, derivados de la prueba técnica original.

## 2. Tecnologías obligatorias

- **HTML:** estructuras estándar.
- **CSS:** CSS nativo. No utilizar frameworks CSS como Bootstrap o Tailwind.
- **PHP:** PHP nativo, sin frameworks.
- **JavaScript:** JavaScript nativo, sin frameworks.
- **AJAX:** utilizar JavaScript para comunicar la interfaz con PHP.
- **Base de datos:** almacenar los datos en un gestor de base de datos. Se prefiere PostgreSQL, pero no es obligatorio.

No sustituir las tecnologías exigidas por otras alternativas.

## 3. Formulario de registro

El formulario debe incluir los siguientes campos.

### 3.1. Código del producto

**Control:** input de texto.

**Validaciones:**
- Obligatorio.
- La obligatoriedad debe validarse mediante JavaScript, sin utilizar el atributo HTML `required`.
- Debe contener al menos una letra y un número.
- No debe contener caracteres distintos de letras y números.
- Longitud mínima: 5 caracteres.
- Longitud máxima: 15 caracteres.
- Debe ser único y verificarse en la base de datos.
- La validación del formato debe realizarse mediante una expresión regular (regex).

**Mensajes exactos:**

- Campo vacío:
  `El código del producto no puede estar en blanco.`
- Formato incorrecto:
  `El código del producto debe contener letras y números`
- Longitud incorrecta:
  `El código del producto debe tener entre 5 y 15 caracteres.`
- Código ya registrado:
  `El código del producto ya está registrado.`

### 3.2. Nombre del producto

**Control:** input de texto.

**Validaciones:**
- Obligatorio, con validación mediante JavaScript.
- Debe ingresar el nombre completo del producto, de forma clara y descriptiva.
- Longitud mínima: 2 caracteres.
- Longitud máxima: 50 caracteres.

**Mensajes exactos:**

- Campo vacío:
  `El nombre del producto no puede estar en blanco.`
- Longitud incorrecta:
  `El nombre del producto debe tener entre 2 y 50 caracteres.`

### 3.3. Bodega

**Control:** select.

**Requisitos:**
- Las opciones deben cargarse dinámicamente desde la base de datos.
- La primera opción debe estar en blanco.
- Debe seleccionarse una bodega.
- La obligatoriedad debe validarse mediante JavaScript.

**Mensaje exacto:**

`Debe seleccionar una bodega.`

### 3.4. Sucursal

**Control:** select.

**Requisitos:**
- Las opciones deben cargarse dinámicamente según la bodega seleccionada.
- Las sucursales deben mostrarse únicamente cuando se haya seleccionado una bodega válida.
- La primera opción debe estar en blanco.
- Debe seleccionarse una sucursal para la bodega seleccionada.

**Mensaje exacto:**

`Debe seleccionar una sucursal para la bodega seleccionada.`

### 3.5. Moneda

**Control:** select.

**Requisitos:**
- Las opciones deben cargarse automáticamente desde la base de datos.
- La primera opción debe estar en blanco.
- Debe seleccionarse una moneda.
- La obligatoriedad debe validarse mediante JavaScript.

**Mensaje exacto:**

`Debe seleccionar una moneda para el producto.`

### 3.6. Precio del producto

**Control:** input de texto.

**Validaciones:**
- Obligatorio, con validación mediante JavaScript.
- Debe ser un número positivo.
- Puede tener hasta dos decimales.
- La validación del formato debe realizarse mediante una expresión regular (regex).

**Mensajes exactos:**

- Campo vacío:
  `El precio del producto no puede estar en blanco.`
- Formato incorrecto:
  `El precio del producto debe ser un número positivo con hasta dos decimales.`

### 3.7. Material del producto

**Control:** grupo de checkboxes.

**Opciones:**
- Plástico
- Metal
- Madera
- Vidrio
- Textil

**Validación:**
- Deben seleccionarse al menos dos materiales.

**Mensaje exacto:**

`Debe seleccionar al menos dos materiales para el producto.`

### 3.8. Descripción del producto

**Control:** textarea.

**Validaciones:**
- Obligatoria, con validación mediante JavaScript.
- Longitud mínima: 10 caracteres.
- Longitud máxima: 1000 caracteres.
- Debe permitir cualquier carácter, incluidos espacios y saltos de línea.

**Mensajes exactos:**

- Campo vacío:
  `La descripción del producto no puede estar en blanco.`
- Longitud incorrecta:
  `La descripción del producto debe tener entre 10 y 1000 caracteres.`

## 4. Estilos visuales

Aplicar los siguientes estilos:

- Fuente general: `Arial, sans-serif`.
- Tamaño de fuente general: `16px`.
- Botón de guardado: texto `Guardar Producto`.
- Color del botón: `#1aab8a`.
- Tamaño de fuente del botón: `16px`.

El diseño debe considerar la imagen de referencia incluida en el documento original. No inventar requisitos visuales que no estén indicados en el documento o en dicha referencia.

### 4.1. Imagen de referencia de la interfaz

El proyecto dispone de la imagen `docs/referencia.png`, que corresponde a la referencia visual incluida en el documento original de la prueba técnica.

- Utilizar esta imagen como referencia para reproducir la interfaz solicitada.
- Respetar los requisitos visuales expresamente indicados en esta especificación.
- No asumir que la imagen introduce funcionalidades o validaciones adicionales a las descritas en los requisitos.
- Si algún detalle visual no está definido expresamente, utilizar la imagen como referencia sin inventar requisitos funcionales.

La imagen es una referencia visual; no sustituye las reglas de validación ni los demás requisitos del documento original.

## 5. Flujo de guardado

Implementar el siguiente flujo:

1. **Validación del formulario:** validar todos los campos mediante JavaScript antes de enviar los datos.
2. **Errores de validación:** si una validación falla, mostrar el mensaje personalizado correspondiente mediante `alert()` y no continuar con el envío.
3. **Envío AJAX:** cuando las validaciones sean satisfactorias, enviar los datos al servidor mediante AJAX.
4. **Procesamiento PHP:** recibir los datos y realizar la inserción en la tabla correspondiente si las validaciones son satisfactorias.
5. **Respuesta al usuario:** devolver una respuesta que permita informar si la operación tuvo éxito o produjo un error.

La verificación de unicidad del código debe realizarse en la base de datos.

## 6. Entregables obligatorios

### 6.1. Archivos de la aplicación

- Archivo HTML o PHP con la estructura de la interfaz.
- Uno o más archivos CSS para los estilos.
- Uno o más archivos JavaScript para las validaciones, las interacciones dinámicas y el envío AJAX.
- Uno o más archivos PHP para la conexión con la base de datos y las operaciones del servidor.
- Archivos SQL con las sentencias necesarias para crear tablas, funciones y otros objetos de base de datos que correspondan a la solución.

### 6.2. Carpeta SQL

Debe existir una carpeta llamada `sql` que contenga los archivos SQL de la base de datos.

### 6.3. Archivo LEEME.txt

Debe existir un archivo `LEEME.txt` en la raíz del proyecto que indique:

- Los pasos para instalar el proyecto.
- La versión de PHP.
- La versión del gestor de base de datos utilizado.

### 6.4. Repositorio

El código debe entregarse mediante una URL pública de GitHub o una plataforma similar.

No se aceptarán proyectos comprimidos.

### 6.5. Información para la entrega

El documento original indica que la entrega debe realizarse por correo electrónico e incluir:

- Nombre completo.
- RUT / DNI (Perú).
- Edad.
- URL pública del repositorio.
- Teléfono.

La dirección de correo indicada en el documento original es `fmellafarias@gmail.com`.

## 7. Restricciones para la implementación

- No utilizar frameworks para HTML, CSS, PHP o JavaScript.
- No utilizar el atributo HTML `required` para sustituir las validaciones JavaScript solicitadas.
- No modificar los mensajes de error especificados.
- No omitir campos, validaciones ni entregables.
- No agregar funcionalidades que no formen parte de la prueba.
- No presentar decisiones técnicas propias como requisitos expresos del documento original.
- Cuando el documento no defina un detalle técnico necesario, elegir la solución mínima compatible con los requisitos, sin ampliar el alcance funcional.

## 8. Criterio de cumplimiento

La implementación debe cumplir los requisitos anteriores y los detalles visuales de la referencia original.

El documento de la prueba menciona una rúbrica de evaluación adjunta. Dicha rúbrica debe revisarse por separado si está disponible; sus criterios no se pueden deducir únicamente de esta especificación.

Antes de considerar terminada la prueba, verificar cada campo, sus mensajes, las cargas dinámicas, el envío AJAX, el guardado en la base de datos y todos los entregables exigidos.
