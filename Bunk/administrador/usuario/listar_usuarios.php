<?php
    session_start();
    if (!isset($_SESSION['Rol']) || $_SESSION['Rol'] != 'Admin') {
        header('Location: ../login_registro/login.php');
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Bancos</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    </head>
    <body>
        <form method="POST" action=<?$_SERVER['PHP_SELF']?>>
            <input type=submit name=salir value=Salir></input>
        </form>
        <h1>Clientes</h1>
        <?php
            include_once '../../config.php';
            $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
            $datos = "";
            if(mysqli_connect_errno()){
                $datos.= "Error en la conexión: ".mysqli_conecct_error()."<br>";
            }
        ?>
        <table class="table">
            <tr>
                <th>Email</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Cédula</th>
                <th></th>
                <th></th>
            </tr>
        <?php
            $sql = "SELECT * FROM Clientes";
            $resultado = mysqli_query($con,$sql);
            while($fila = mysqli_fetch_array($resultado)){
                $datos.='<tr>';
                for($i =3; $i <=6; ++$i){
                    $datos.="<td>".$fila[$i]."</td>";
                }
                $datos.="<td><a href=".$_SERVER['PHP_SELF']."?cliente=".$fila[0].">Editar</td>";
                $datos.='</tr>';
            }
            $datos.='</table>';
            echo $datos;
            if(isset($_GET['cliente'])){
                $_SESSION['cliente']=$_GET['cliente'];
                header('Location: editar_usuario.php');
            }
            if(isset($_POST['salir'])){
                $_SESSION = array();
                session_destroy();
                header('Location: ../index.php');
            }
        ?>
    </body>
</html>