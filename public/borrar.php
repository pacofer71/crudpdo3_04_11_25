<?php

use App\Bd\Producto;

session_start();
require __DIR__."/../vendor/autoload.php";
$id=filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if(!$id){
    header("Location:index.php");
    exit;
}
//necesitaremos el nombre de la imagen para borrarla fisicamente del disco
$producto=Producto::read($id)[0] ?? null;
if(!$producto){
    header("Location:index.php");
    exit; 
}
$imagen=$producto->imagen; // /imagenes/12312312_nombre.png
Producto::delete($id); //Booro de la base de datos el producto;
if(basename($imagen)!='noimage.jpg'){
    @unlink(".$imagen");
}
$_SESSION['mensaje']="Producto borrado.";
header("Location:index.php");