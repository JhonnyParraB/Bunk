<?php
    session_start();
    if (!isset($_SESSION['Rol']) || $_SESSION['Rol'] != 'User') {
        header('Location: ../login_registro/login.php');
    }
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Cliente</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    </head>
    <body>
        <form method="POST" action=<?$_SERVER['PHP_SELF']?>>
            <input type=submit name=salir value=Salir></input>
        </form>
        <h1>Bienvenido Cliente</h1>
        <h3>Mensajeria</h3>
        <ul>
            <li><a href="centro_mensajes.php">Centro de mensajes</a></li>
        </ul>
        <h3>Productos</h3>
        <ul>
            <li><a href="../productos/solicitar_credito.php">Solicitar un Crédito</a></li>
            <li><a href="../productos/abrir_cuenta_ahorros.php">Abrir una cuenta de ahorros</a></li>
            <li><a href="../productos/solicitar_tarjeta_credito.php">Solicitar tarjeta de crédito</a></li>
            <li><a href="../cliente/Reporte_productos.php">Listar Transferencias</a></li>
        </ul>
        <h3>Operaciones</h3>
        <ul>
            <li><a href="../operaciones/consignacion.php">Consignar</a></li>
            <li><a href="../operaciones/transferencia.php">Transferencia</a></li>
            <li><a href="../operaciones/retiro.php">Retiro</a></li>
            <li><a href="../operaciones/compra.php">Comprar con tarjeta de crédito</a></li>
        </ul>
        <?php
            if(isset($_POST['salir'])){
                $_SESSION = array();
                session_destroy();
                header('Location: ../index.php');
            }
        ?>
    </body>
</html>
