<?php
namespace App\Bd;

use \PDO;
use \PDOStatement;

class Producto extends Conexion{
    private int $id;
    private string $nombre;
    private string $descripcion;
    private float $precio;
    private string $disponible;
    private string $imagen;

    private static function executeQuery(string $q, array $parametros=[], bool $devolverAlgo=false): ?PDOStatement{
        $stmt=self::getConexion()->prepare($q);
        try{
            count($parametros) ? $stmt->execute($parametros) : $stmt->execute();
        }catch(\PDOException $ex){
            die("Eror en el query: ".$ex->getMessage());
        }
        return $devolverAlgo ?  $stmt : null;
    }

    public function create(): void{
        $q="insert into productos(nombre, descripcion, precio, disponible, imagen) values(:n, :de, :p, :di, :im)";
        self::executeQuery($q, [
            ':n'=>$this->nombre,
            ':de'=>$this->descripcion,
            ':p'=>$this->precio,
            ':di'=>$this->disponible,
            ':im'=>$this->imagen
        ]);
    } 

    public static function crearRegistros(int $cantidad){
        
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