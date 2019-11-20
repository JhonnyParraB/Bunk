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
        <title>Centro de mensajes</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    </head>
    <body>
        <form method="POST" action=<?$_SERVER['PHP_SELF']?>>
            <input type=submit name=salir value=Salir></input>
        </form>
        <h1>Centro de mensajes</h1>
        <?php
            include_once '../config.php';
            $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
            $datos = "";
            if(mysqli_connect_errno()){
                $datos.= "Error en la conexión: ".mysqli_conecct_error()."<br>";
            }
        ?>
        <h2>Créditos por aprobar</h2>
        <table class="table">
            <tr>
                <th>Banco</th>
                <th>Cédula</th>
                <th>Email</th>
                <th>Valor</th>
                <th>Tasa Interés</th>
                <th>Fecha de pago</th>
                <th>Fecha de solicitud</th>
                <th></th>
            </tr>
        <?php
            $sql = "SELECT cr.id, b.nombre, c.cedula, c.email, cr.valor, cr.tasa_interes, cr.fecha_pago, cr.fecha_solicitud
                    FROM Clientes c, Bancos b, creditos cr
                    WHERE b.id = cr.banco_id AND c.id=cr.cliente_id AND cr.estado='PENDIENTE'
                    ORDER BY cr.fecha_solicitud DESC";
            $resultado = mysqli_query($con,$sql);
            while($fila = mysqli_fetch_array($resultado)){
                $datos.='<tr>';
                for($i =1; $i <=6; ++$i){
                    $datos.="<td>".$fila[$i]."</td>";
                }
                $datos.="<td><a href=".$_SERVER['PHP_SELF']."?credito=".$fila[0].">Responder solicitud</td>";
                $datos.='</tr>';
            }
            $sql = "SELECT cr.id, b.nombre, v.cedula, v.email, cr.valor, cr.tasa_interes, cr.fecha_pago, cr.fecha_solicitud
                    FROM visitantes v, Bancos b, creditos cr
                    WHERE b.id = cr.banco_id AND v.id=cr.visitante_id AND cr.estado='PENDIENTE'
                    ORDER BY cr.fecha_solicitud DESC";
            $resultado = mysqli_query($con,$sql);
            while($fila = mysqli_fetch_array($resultado)){
                $datos.='<tr>';
                for($i =1; $i <=count($fila); ++$i){
                    $datos.="<td>".$fila[$i]."</td>";
                }
                $datos.="<td><a href=".$_SERVER['PHP_SELF']."?credito=".$fila[0].">Responder solicitud</td>";
                $datos.='</tr>';
            }
            $datos.='</table>';
            echo $datos;
            if(isset($_GET['credito'])){
                $_SESSION['credito']=$_GET['credito'];
                header('Location: credito/responder_credito.php');
            }
        ?>
        <h2>Tarjetas de crédito por aprobar</h2>
        <table class="table">
        <tr>
            <th>Banco</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Cédula</th>
            <th>Cuenta ahorros</th>
            <th>Fecha solicitud</th>
            <th></th>
        </tr>
        <?php
            $datos="";
            $sql = "SELECT tc.id, b.nombre, c.nombre, c.apellido, c.cedula, tc.cuenta_ahorro_id, tc.fecha_solicitud
                    FROM Clientes c, Bancos b, tarjetas_credito tc, cuentas_ahorro cu
                    WHERE b.id = cu.banco_id AND c.id=cu.cliente_id AND cu.id=tc.cuenta_ahorro_id AND tc.estado='PENDIENTE'
                    ORDER BY tc.fecha_solicitud DESC";
            $resultado = mysqli_query($con,$sql);
            while($fila = mysqli_fetch_array($resultado)){
                $datos.='<tr>';
                for($i =1; $i <=6; ++$i){
                    $datos.="<td>".$fila[$i]."</td>";
                }
                $datos.="<td><a href=".$_SERVER['PHP_SELF']."?tcredito=".$fila[0].">Responder solicitud</td>";
                $datos.='</tr>';
            }
            $datos.='</table>';
            echo $datos;
            if(isset($_GET['tcredito'])){
                $_SESSION['tcredito']=$_GET['tcredito'];
                header('Location: tarjeta_credito/responder_tarjeta_credito.php');
            }
            if(isset($_POST['salir'])){
                $_SESSION = array();
                session_destroy();
                header('Location: ../index.php');
            }
        ?>
    </body>
</html>
