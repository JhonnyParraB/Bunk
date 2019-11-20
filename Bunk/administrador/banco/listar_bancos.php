<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Bancos</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    </head>
    <body>
        <h1>Bancos</h1>
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
                <th>Nombre</th>
                <th>Interés Crédito Visitantes</th>
                <th>Interés Cuenta Ahorro</th>
                <th>Costo Transferencia</th>
                <th>Interés Mora</th>
                <th></th>
            </tr>
        <?php
            $sql = "SELECT * FROM Bancos";
            $resultado = mysqli_query($con,$sql);
            while($fila = mysqli_fetch_array($resultado)){
                $datos.='<tr>';
                for($i =1; $i <=5; ++$i){
                    $datos.="<td>".$fila[$i]."</td>";
                }
                $datos.="<td><a href=".$_SERVER['PHP_SELF']."?banco=".$fila[0].">Editar</td>";
                $datos.='</tr>';
            }
            $datos.='</table>';
            echo $datos;
            if(isset($_GET['banco'])){
                $_SESSION['banco']=$_GET['banco'];
                header('Location: editar_banco.php');
            }
        ?>
    </body>
</html>