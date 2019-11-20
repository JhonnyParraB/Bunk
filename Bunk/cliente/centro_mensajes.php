<?php
    session_start();
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Centro de mensajes</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    </head>
    <body>
        <h1>Centro de mensajes</h1>
        <?php
            include_once '../config.php';
            $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
            $datos = "";
            if(mysqli_connect_errno()){
                $datos.= "Error en la conexión: ".mysqli_conecct_error()."<br>";
            }
        ?>
        <h2>Mensajes de tus créditos</h2>
        <table class="table">
            <tr>
                <th>Fecha de respuesta</th>
                <th>Estado</th>
                <th>Banco</th>
                <th>Tasa de Interés</th>
                <th>Fecha de pago</th>
                <th>Valor</th>                
                <th>Fecha de solicitud</th>
                <th>Mensaje</th>
                <th></th>
            </tr>
        <?php
            //ESTO DEBE EXTRAERSE DE LA SESIÓN
            $cliente_id = 1;

            $sql = "SELECT cr.fecha_respuesta, cr.estado, b.nombre, cr.tasa_interes, cr.fecha_pago, cr.valor, cr.fecha_solicitud, cr.mensaje
                    FROM CLIENTES c, CREDITOS cr, BANCOS b
                    WHERE cr.cliente_id = $cliente_id AND cr.banco_id=b.id AND (cr.estado='APROBADO' OR cr.estado='RECHAZADO')
                    ORDER BY cr.fecha_respuesta DESC";
            $resultado = mysqli_query($con,$sql);

            while($fila = mysqli_fetch_array($resultado)){
                $datos.='<tr>';
                for($i =0; $i < 8; ++$i){
                    $datos.="<td>".$fila[$i]."</td>";
                }
                $datos.='</tr>';
            }
            
            $datos.='</table>';
            echo $datos;

        ?>
        <h2>Mensajes de tus tarjetas de crédito</h2>
        <table class="table">
        <tr>
            <th>Fecha de respuesta</th>
            <th>Estado</th>
            <th>Cupo máximo</th>
            <th>Cuota de manejo</th>
            <th>Tasa de interés</th>
            <th>Sobrecupo</th>
            <th>Cuenta de ahorro</th>
            <th>Banco</th>
            <th>Fecha de solicitud</th>
            <th>Mensaje</th>
            <th></th>
        </tr>
        <?php
            $datos="";
            $sql = "SELECT tc.fecha_respuesta, tc.estado, tc.cupo_maximo, tc.tasa_interes, tc.sobrecupo, tc.cuenta_ahorro_id, tc.fecha_solicitud, tc.mensaje
                    FROM CLIENTES c, TARJETAS_CREDITO tc, BANCOS b, CUENTAS_AHORRO ca
                    WHERE ca.cliente_id = $cliente_id AND ca.banco_id=b.id AND (tc.estado='APROBADO' OR tc.estado='RECHAZADO') AND tc.cuenta_ahorro_id=ca.id
                    ORDER BY tc.fecha_respuesta DESC";
            $resultado = mysqli_query($con,$sql);
            while($fila = mysqli_fetch_array($resultado)){
                $datos.='<tr>';
                for($i =0; $i < 10; ++$i){
                    $datos.="<td>".$fila[$i]."</td>";
                }
                $datos.='</tr>';
            }
            $datos.='</table>';
            echo $datos;
        ?>
    </body>
</html>
