<?php

use App\Bd\Producto;
use App\Utils\Validacion;

session_start();
require __DIR__ . "/../vendor/autoload.php";
if (isset($_POST['nombre'])) {
    //1.- Recogemos y Limpiamos
    $nombre = Validacion::sanearCadenas($_POST['nombre']);
    $descripcion = Validacion::sanearCadenas($_POST['descripcion']);
    $precio = Validacion::sanearCadenas($_POST['precio']);
    $precio = (float) $precio;
    $disponible = $_POST['disponible'] ?? "Error";
    $disponible = Validacion::sanearCadenas($disponible);
    //Validamos
    $errores = false;
    if (!Validacion::longitudCampoValida('nombre', $nombre, 3, 50)) {
        $errores = true;
    }else{
        if(!Validacion::esNombreUnico($nombre)){
            $errores=true;
        }
    }
    if (!Validacion::longitudCampoValida('descripcion', $descripcion, 10, 150)) {
        $errores = true;
    }
    if (!Validacion::esPrecioValido($precio, 10, 9999.99)) {
        $errores = true;
    }
    if (!Validacion::disponibleValido($disponible)) {
        $errores = true;
    }

    // Procesamos el campo imagen
    $imagen = "/imagenes/noimage.jpg";
    $file = $_FILES['imagen'];
    if (is_uploaded_file($file['tmp_name'])) {
        //he subido un archivo 
        if (Validacion::esImagenValida($file['type'], $file['size'])) {
            //La imagen es valida, vamos a guardarla
            //fisicamente en la carpeta imagenes y su referencia en la bbdd
            $imagen = "/imagenes/" . uniqid() . "_" . $file['name']; // /imagenes/123123_nombre.jpg
            $ruta = ".$imagen"; //  ./imagenes/123123_nombre.jpg
            if (!move_uploaded_file($file['tmp_name'], $ruta)) {
                $errores = true;
                $_SESSION['error_imagen'] = "*** Error, no se pudo guardar la imagen";
            }
        } else {
            $errores = true;
        }
    }
    if ($errores) {
        header("Location:{$_SERVER['PHP_SELF']}");
        die();
    }
    // No ha habido errores, vamos a guarar el producto
    (new Producto)
        ->setNombre($nombre)
        ->setDescripcion($descripcion)
        ->setPrecio($precio)
        ->setImagen($imagen)
        ->setDisponible($disponible)
        ->create();
    $_SESSION['mensaje']="Registro Guardado";
    header("Location:index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CDN Tailwindcss -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- CDN FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CDn SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Document</title>
</head>

<body class="bg-blue-200 p-8">
    <h3 class="text-center text-xl font-bold mb-2">Crear Producto</h3>
    <div class="w-1/3 mx-auto p-6 bg-white rounded-lg shadow-md mt-8">
        <form action="<?= $_SERVER['PHP_SELF'] ?>" method='POST' enctype="multipart/form-data">
            <!-- Campo Nombre -->
            <div class="mb-4">
                <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-tag mr-2 text-blue-500"></i>Nombre del Producto
                </label>
                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Ingresa el nombre del producto">
                    <?php Validacion::pintarError('error_nombre') ?>
            </div>

            <!-- Campo Descripción -->
            <div class="mb-4">
                <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-align-left mr-2 text-blue-500"></i>Descripción
                </label>
                <textarea
                    id="descripcion"
                    name="descripcion"
                    rows="4"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Describe el producto..."></textarea>
                    <?php Validacion::pintarError('error_descripcion') ?>
            </div>

            <!-- Campo Precio -->
            <div class="mb-4">
                <label for="precio" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-dollar-sign mr-2 text-blue-500"></i>Precio
                </label>
                <input
                    type="number"
                    id="precio"
                    name="precio"
                    step="0.01"
                    min="0"
                    max="9999.99"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="0.00">
                    <?php Validacion::pintarError('error_precio') ?>
            </div>

            <!-- Radio Buttons Disponible -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-box mr-2 text-blue-500"></i>Disponible
                </label>
                <div class="flex space-x-6">
                    <label class="inline-flex items-center">
                        <input
                            type="radio"
                            name="disponible"
                            value="SI"
                            class="text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-gray-700">Sí</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input
                            type="radio"
                            name="disponible"
                            value="NO"
                            class="text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-gray-700">No</span>
                    </label>
                </div>
                <?php Validacion::pintarError('error_disponible') ?>
            </div>
            <!-- Campo Imagen -->
            <div class="mb-4 p-1 rounded-lg bg-green-100 relative w-full h-82">
                <input type="file" name="imagen" id="cimagen" class="hidden" accept="image/*" />
                <label for="cimagen" class="absolute bottom-2 right-2 p-1 rounded-lg bg-gray-700 hover:bg-gray-900 text-white font-bold">
                    <i class="fa-solid fa-upload mr-1"></i>SUBIR
                </label>
                <img src="" id="preview" class="w-full h-full bg-center bg-cover bg-no-repeat" />
            </div>
            <?php Validacion::pintarError('error_imagen') ?>
            <!-- Botones -->
            <div class="flex justify-end space-x-4">
                <a
                    href="index.php"
                    class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent transition duration-200">
                    <i class="fas fa-times mr-2"></i>Cancelar
                </a>
                <button
                    type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                    <i class="fas fa-save mr-2"></i>Guardar
                </button>
            </div>
        </form>
    </div>
    <!-- Script para previsualizar la imagen elegida -->
    <script>
        const fileInput = document.getElementById('cimagen');
        const preview = document.getElementById('preview');
        fileInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                preview.src = URL.createObjectURL(file);
            }
        });
    </script>
</body>

</html>