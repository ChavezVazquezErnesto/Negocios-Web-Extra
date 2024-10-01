<?php

namespace Kawschool;

class Usuario{

    private $config;
    private $cn = null;

    public function __construct(){

        $this->config = parse_ini_file(__DIR__.'/../config.ini') ;

        $this->cn = new \PDO( $this->config['dns'], $this->config['usuario'],$this->config['clave'],array(
            \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
        ));
    }

    public function login($nombre, $clave){
        
        $sql = "SELECT nombre_usuario FROM `usuarios` WHERE nombre_usuario = :nombre AND clave = :clave";
        
        $resultado = $this->cn->prepare($sql);

        // Asignar los valores a los parámetros
        $_array = array(
            ":nombre" => $nombre,
            ":clave" => $clave
        );

        // Ejecutar la consulta pasando los valores
        if($resultado->execute($_array)) {
            return $resultado->fetch();
        }

        return false;
    }
}
