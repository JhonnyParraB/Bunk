<?php
    session_start();
    if (!isset($_SESSION['Rol']) || $_SESSION['Rol'] != 'Admin') {
        header('Location: ../login_registro/login.php');
    }
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Admin</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    </head>
    <body>
        <form method="POST" action=<?$_SERVER['PHP_SELF']?>>
            <input type=submit name=salir value=Salir></input>
        </form>
        <h1>Bienvenido Admin</h1>
        <ul>
            <li><a href="banco/listar_bancos.php">Administrar bancos</a></li>
            <li><a href="usuario/listar_usuarios.php">Gestionar usuarios</a></li>
            <li><a href="cuenta_ahorro/listar_cuentas.php">Gestionar cuentas</a></li>
            <li><a href="credito/listar_creditos.php">Gestionar créditos</a></li>
            <li><a href="centro_mensajes.php">Centro de mensajes</a></li>
            <li><a href="fin_de_mes.php">Fin de mes</a></li>
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
