<?php

namespace App\Bd;

use \PDO;
use \PDOStatement;

class Producto extends Conexion
{
    private int $id;
    private string $nombre;
    private string $descripcion;
    private float $precio;
    private string $disponible;
    private string $imagen;

    private static function executeQuery(string $q, array $parametros = [], bool $devolverAlgo = false): ?PDOStatement
    {
        $stmt = self::getConexion()->prepare($q);
        try {
            $stmt->execute($parametros);
        } catch (\PDOException $ex) {
            die("Eror en el query: " . $ex->getMessage());
        }
        return $devolverAlgo ?  $stmt : null;
    }

    public function create(): void
    {
        $q = "insert into productos(nombre, descripcion, precio, disponible, imagen) values(:n, :de, :p, :di, :im)";
        self::executeQuery($q, [
            ':n' => $this->nombre,
            ':de' => $this->descripcion,
            ':p' => $this->precio,
            ':di' => $this->disponible,
            ':im' => $this->imagen
        ]);
    }
    public static function read(?int $id = null): array
    {
        $q = ($id == null) ? "select * from productos order by id desc" :
            "select * from productos where id=:i";
        $parametros=($id==null) ? [] :[':i'=>$id];
        $stmt=self::executeQuery($q, $parametros, true);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function borrarTodo()
    {
        $q = "delete from productos";
        self::executeQuery($q);
    }
    public static function delete(int $id){
        $q="delete from productos where id=:i";
        self::executeQuery($q, [':i'=>$id]);
    }

    public static function existeNombre(string $nombre): bool{
        $q="select id from productos where nombre=:n";
        $stmt=self::executeQuery($q, [':n'=>$nombre], true);
        return count($stmt->fetchAll(PDO::FETCH_OBJ));
    }

    public static function crearRegistros(int $cantidad)
    {
        $faker = \Faker\Factory::create('es_ES');
        $faker->addProvider(new \Mmo\Faker\PicsumProvider($faker));
        //$faker->addProvider(new \Mmo\Faker\FakeimgProvider($faker));

        for ($i = 0; $i < $cantidad; $i++) {
            $nombre = $faker->unique()->sentence(3, true);
            $descripcion = $faker->text();
            $precio = $faker->randomFloat(2, 10, 9999.99);
            $disponible = $faker->randomElement(['SI', 'NO']);
            //$imagen = "/imagenes/" . $faker->picsum(__DIR__ . "/../../public/imagenes/", 600, 480, false);
            //$imagen="/imagenes/". $faker->fakeImg(__DIR__."/../../public/imagenes/", 400, 400, false);
            $imagen = "/imagenes/default.png";
            (new Producto)
                ->setNombre($nombre)
                ->setDescripcion($descripcion)
                ->setPrecio($precio)
                ->setDisponible($disponible)
                ->setImagen($imagen)
                ->create();
        }
    }

    /**
     * Set the value of id
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set the value of nombre
     */
    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Set the value of descripcion
     */
    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }



    /**
     * Set the value of disponible
     */
    public function setDisponible(string $disponible): self
    {
        $this->disponible = $disponible;

        return $this;
    }

    /**
     * Set the value of imagen
     */
    public function setImagen(string $imagen): self
    {
        $this->imagen = $imagen;

        return $this;
    }

    /**
     * Set the value of precio
     */
    public function setPrecio(float $precio): self
    {
        $this->precio = $precio;

        return $this;
    }
}
