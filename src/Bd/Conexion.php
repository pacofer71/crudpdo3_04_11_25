<?php
namespace App\Bd;
use \PDO;
use \PDOException;

class Conexion{
    private static ?PDO $conexion=null;

    protected static function getConexion(): PDO{
        if(self::$conexion==null){
            self::setConexion();
        }
        return self::$conexion;
    }

    private static function setConexion(): void{
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__."/../../");
        $dotenv->load();
        $usuario=$_ENV['USUARIO'];
        $basedatos=$_ENV['DATABASE'];
        $pass=$_ENV['PASSWORD'];
        $host=$_ENV['HOST'];
        $puerto=$_ENV['PORT'];
        $dsn="mysql:host=$host;dbname=$basedatos;port=$puerto;charset=utf8mb4";
        $opciones=[
            PDO::ATTR_PERSISTENT=>true,
            PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES=>false
        ];
        try{
            self::$conexion=new PDO($dsn, $usuario, $pass, $opciones);
        }catch(PDOException $ex){
            die("Error en la conexión: "-$ex->getMessage());
        }








    }
}