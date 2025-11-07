<?php

use App\Bd\Producto;

session_start();
require __DIR__ . "/../vendor/autoload.php";
$productos = Producto::read();
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

<body class="p-8 bg-blue-200">
    <h3 class="text-center text-xl font-bold mb-2">Listado de Productos</h3>


    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <div class="flex flex-row-reverse my-1">
            <a href="nuevo.php" class="p-2 rounded-lg bg-green-700 hover:bg-green-900 text-white font-bold">
                <i class="fas fa-add mr-1"></i>NUEVO
            </a>
        </div>
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-16 py-3">
                        <span class="sr-only">Image</span>
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Nombre
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Descripción
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Precio
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Disponible
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($productos as $item): ?>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="p-4">
                        <img src="<?= ".".$item->imagen ?>" class="w-16 md:w-32 max-w-full max-h-full" alt="Imagen Producto" />
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                        <?= $item->nombre ?>
                    </td>
                    <td class="px-6 py-4 italic">
                        <?= $item->descripcion ?>
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white whitespace-nowrap">
                        <?= $item->precio ?> €
                    </td>
                     <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                        <?= $item->disponible ?> 
                    </td>
                    <td class="px-6 py-4">
                        <form method="POST" action="borrar.php">
                            <input type="hidden" name="id" value="<?=$item->id ?>" />
                            <a href="update.php?id=<?=$item->id ?>">
                                <i class="fas fa-edit text-green-500 hover:tegext-lg mt-2"></i>
                            </a>
                            <button type="submit">
                                <i class="fas fa-trash text-red-500 hover:text-lg"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php
    if(isset($_SESSION['mensaje'])){
    echo <<< TXT
    <script>
        Swal.fire({
            icon: "success",
            title: "{$_SESSION['mensaje']}",
            showConfirmButton: false,
            timer: 1500
        });
    </script>
    TXT;
    unset($_SESSION['mensaje']);
    }
?>
</body>

</html