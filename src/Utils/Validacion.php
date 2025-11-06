<?php

namespace App\Utils;

use App\Bd\Producto;

abstract class Validacion
{
    public static function sanearCadenas(string $cad): string
    {
        return htmlspecialchars(trim($cad));
    }

    public static function longitudCampoValida(string $nomCampo, string $valor, int $min, $max): bool
    {
        if (strlen($valor) < $min || strlen($valor) > $max) {
            $_SESSION["error_$nomCampo"] = "*** Error, se esperaba una longitud entre $min y $max caracteres";
            return false;
        }
        return true;
    }
    public static function esPrecioValido(float $precio, float $min, float $max): bool
    {
        if ($precio < $min || $precio > $max) {
            $_SESSION["error_precio"] = "*** Error, se esperaba un precio entre $min y $max";
            return false;
        }
        return true;
    }
    public static function disponibleValido(string $valor): bool
    {
        $valores = ['SI', 'NO'];
        if (!in_array($valor, $valores)) {
            $_SESSION['error_disponible'] = "Error, valor disponible inválido o no seleccionado";
            return false;
        }
        return true;
    }
    public static function esNombreUnico(string $nombre): bool{
        if(Producto::existeNombre($nombre)){
            $_SESSION['error_nombre']="*** Error, ya existe un producto con ese nombre.";
            return false;
        }
        return true;
    }

    public static function esImagenValida(string $tipo, int $size): bool
    {
        $types = [
            'image/jpeg',      // .jpg, .jpeg
            'image/png',       // .png
            'image/gif',       // .gif
            'image/webp',      // .webp
            'image/bmp',       // .bmp
            'image/tiff',      // .tif, .tiff
            'image/svg+xml',   // .svg
            'image/x-icon',    // .ico
            'image/heic',      // .heic (formato de Apple)
            'image/heif',      // .heif (alta eficiencia)
            'image/avif'       // .avif (formato moderno de alta compresión)
        ];
        if(!in_array($tipo, $types)){
            $_SESSION['error_imagen']="Error, se esperaba un archivo de imagen";
            return false;
        }
        if($size>2097152){
             $_SESSION['error_imagen']="Error, el tamaño de la imagen max es 2MB";
            return false;
        }
        return true;
    }
    public static function pintarError(string $nombre): void{
        if(isset($_SESSION[$nombre])){
            echo "<p class='mt-1 text-red-600 text-sm italic'>{$_SESSION[$nombre]}</p>";
            unset($_SESSION[$nombre]);
        }
    }

}
