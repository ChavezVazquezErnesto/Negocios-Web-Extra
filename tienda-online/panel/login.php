<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require '../vendor/autoload.php';
$usuario = new Kawschool\Usuario;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = $_POST['nombre_usuario'];
    $clave = $_POST['clave'];

    $resultado = $usuario->login($nombre_usuario, $clave);

    if ($resultado) {
        $_SESSION['usuario_info'] = array(
            'id' => $resultado['id'],
            'nombre_usuario' => $resultado['nombre_usuario']
        );

        header('Location: dashboard.php');
        exit;
    } else {
        echo "Error al iniciar sesión. Credenciales incorrectas.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <form action="login.php" method="POST">
        <label>Nombre de Usuario:</label>
        <input type="text" name="nombre_usuario" required><br>
        <label>Clave:</label>
        <input type="password" name="clave" required><br>
        <button type="submit">Iniciar Sesión</button>
    </form>
</body>
</html>
