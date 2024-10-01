<?php

namespace Kawschool;

class Pedido{

    private $config;
    private $cn = null;

    public function __construct(){
        $this->config = parse_ini_file(__DIR__.'/../config.ini');

        // Conectar a la base de datos
        $this->cn = new \PDO(
            $this->config['dns'],
            $this->config['usuario'],
            $this->config['clave'],
            array(
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
            )
        );
    }

    // Función para registrar un pedido principal
    public function registrar($_params){
        $sql = "INSERT INTO `pedidos`(`users_id`, `total`, `fecha`) 
        VALUES (:users_id, :total, :fecha)";

        $resultado = $this->cn->prepare($sql);

        $_array = array(
            ":users_id" => $_params['users_id'],
            ":total" => $_params['total'],
            ":fecha" => $_params['fecha'],
        );

        if($resultado->execute($_array))
            return $this->cn->lastInsertId();

        return false;
    }

    // Función para registrar los detalles de un pedido
    public function registrarDetalle($_params){
        $sql = "INSERT INTO `detalle_pedidos`(`pedido_id`, `producto_id`, `precio`, `cantidad`) 
        VALUES (:pedido_id, :producto_id, :precio, :cantidad)";

        $resultado = $this->cn->prepare($sql);

        $_array = array(
            ":pedido_id" => $_params['pedido_id'],
            ":producto_id" => $_params['producto_id'],
            ":precio" => $_params['precio'],
            ":cantidad" => $_params['cantidad'],
        );

        if($resultado->execute($_array))
            return true;

        return false;
    }

    // Función corregida para mostrar los pedidos
    public function mostrar()
    {
        $sql = "SELECT p.id, nombre, apellidos, email, total, fecha FROM pedidos p 
        INNER JOIN users c ON p.users_id = c.id ORDER BY p.id DESC"; // Usar `p.users_id` en lugar de `p.cliente_id`

        $resultado = $this->cn->prepare($sql);

        if($resultado->execute())
            return  $resultado->fetchAll();

        return false;
    }

    // Mostrar pedido por ID
    public function mostrarPorId($id)
    {
        $sql = "SELECT p.id, nombre, apellidos, email, total, fecha FROM pedidos p 
        INNER JOIN users c ON p.users_id = c.id WHERE p.id = :id"; // Revisar la tabla `clientes` si se usa correctamente

        $resultado = $this->cn->prepare($sql);

        $_array = array(
            ':id'=>$id
        );

        if($resultado->execute($_array ))
            return  $resultado->fetch();

        return false;
    }

    // Mostrar detalles de un pedido por ID de pedido
    public function mostrarDetallePorIdPedido($id)
    {
        $sql = "SELECT 
                dp.id,
                pe.titulo,
                dp.precio,
                dp.cantidad,
                pe.foto
                FROM detalle_pedidos dp
                INNER JOIN productos pe ON pe.id= dp.producto_id
                WHERE dp.pedido_id = :id";

        $resultado = $this->cn->prepare($sql);

        $_array = array(
            ':id'=>$id
        );

        if($resultado->execute( $_array))
            return  $resultado->fetchAll();

        return false;

    }

}
