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
        <title>Créditos</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    </head>
    <body>
        <form method="POST" action=<?$_SERVER['PHP_SELF']?>>
            <input type=submit name=salir value=Salir></input>
        </form>
        <h1>Créditos</h1>
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
                <th>Cédula</th>
                <th>Email</th>
                <th>Valor</th>
                <th>Tasa Interés</th>
                <th>Fecha de pago</th>
                <th>Dias de mora</th>
                <th>Pagado</th>
                <th>Fecha de aprobación</th>
                <th></th>
            </tr>
        <?php
            $sql = "SELECT cr.id, b.nombre, c.cedula, c.email, cr.valor, cr.tasa_interes, cr.fecha_pago, 
                            cr.dias_mora, cr.pagado, cr.fecha_aprobacion
                    FROM Clientes c, Bancos b, creditos cr
                    WHERE b.id = cr.banco_id AND c.id=cr.cliente_id AND cr.aprobado=1";
            $resultado = mysqli_query($con,$sql);
            while($fila = mysqli_fetch_array($resultado)){
                $datos.='<tr>';
                for($i =1; $i <=9; ++$i){
                    $datos.="<td>".($i==8? ($fila[$i]==1? 'Pagado': 'Debe'):$fila[$i])."</td>";
                }
                $datos.="<td><a href=".$_SERVER['PHP_SELF']."?credito=".$fila[0].">Editar</td>";
                $datos.='</tr>';
            }
            $sql = "SELECT cr.id, b.nombre, v.cedula, v.email, cr.valor, cr.tasa_interes, cr.fecha_pago, 
                            cr.dias_mora, cr.pagado, cr.fecha_aprobacion
                    FROM visitantes v, Bancos b, creditos cr
                    WHERE b.id = cr.banco_id AND v.id=cr.visitante_id AND cr.aprobado=1";
            $resultado = mysqli_query($con,$sql);
            while($fila = mysqli_fetch_array($resultado)){
                $datos.='<tr>';
                for($i =1; $i <=9; ++$i){
                    $datos.="<td>".($i==8? ($fila[$i]==1? 'Pagado': 'Debe'):$fila[$i])."</td>";
                }
                $datos.="<td><a href=".$_SERVER['PHP_SELF']."?credito=".$fila[0].">Editar</td>";
                $datos.='</tr>';
            }
            $datos.='</table>';
            echo $datos;
            if(isset($_GET['credito'])){
                $_SESSION['credito']=$_GET['credito'];
                header('Location: editar_credito.php');
            }
            if(isset($_POST['salir'])){
                $_SESSION = array();
                session_destroy();
                header('Location: ../index.php');
            }
        ?>
    </body>
</html>