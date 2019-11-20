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
        <h1>Cuentas</h1>
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
                <th>Banco</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Cédula</th>
                <th>Saldo</th>
                <th>Cuota de manejo</th>
                <th></th>
            </tr>
        <?php
            $sql = "SELECT cu.id, b.nombre, c.nombre, c.apellido, c.cedula, cu.saldo, cu.cuota_manejo 
                    FROM Clientes c, Bancos b, Cuentas_Ahorro cu
                    WHERE b.id = cu.banco_id AND c.id=cu.cliente_id";
            $resultado = mysqli_query($con,$sql);
            while($fila = mysqli_fetch_array($resultado)){
                $datos.='<tr>';
                for($i =1; $i <=6; ++$i){
                    $datos.="<td>".$fila[$i]."</td>";
                }
                $datos.="<td><a href=".$_SERVER['PHP_SELF']."?cuenta=".$fila[0].">Editar</td>";
                $datos.='</tr>';
            }
            $datos.='</table>';
            echo $datos;
            if(isset($_GET['cuenta'])){
                $_SESSION['cuenta']=$_GET['cuenta'];
                header('Location: editar_cuenta.php');
            }
        ?>
    </body>
</html>