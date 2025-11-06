<?php

use App\Bd\Producto;

require __DIR__."/../vendor/autoload.php";
echo "Creando Productos...\n";
Producto::borrarTodo();
Producto::crearRegistros(10);
echo "\nProductos creados...\n";
