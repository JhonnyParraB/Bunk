<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Fin de mes</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    </head>
    <body>
        <h1>Fin de mes</h1>
        <form method="POST">
            <input type=submit name=fin value='Fin de mes'/>
        </form>
        <?php
            include_once '../config.php';
            $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
            if(mysqli_connect_errno()){
                echo "Error en la conexión: ".mysqli_conecct_error()."<br>";
            }
            if(isset($_POST['fin'])){
                date_default_timezone_set('America/Bogota');
                $fecha = date('Y-m-d', time());
                $sql = "SELECT fecha FROM DIAS_FESTIVOS WHERE fecha = '$fecha'";
                $resultado = mysqli_query($con,$sql);
                if(mysqli_num_rows($resultado)==0){
                    // creditos
                    $datos="<table class='table'>
                                <tr>
                                    <th>Id</th>
                                    <th>Cobrado</th>
                                    <th>Resultado</th>
                                </tr>";
                    cobrarVisitantes($con);
                    cobrarClientes($con, $fecha);
                    $datos.="</table>";

                    // tarjetas
                    pagarTarjetas($con);
                }else{
                    echo "Es festivo, porfavor intentelo un día hábil<br>";
                }
            }

            function cobrarVisitantes($con){ 
                date_default_timezone_set('America/Bogota');
                $fecha1 = time();
                $sql = "SELECT id, visitante_id, valor, tasa_interes, fecha_pago, fecha_pagado, banco_id
                        FROM creditos
                        WHERE estado='APROBADO' AND visitante_id IS NOT NULL";
                $resultado = mysqli_query($con,$sql);
                $datos="";
                while($fila = mysqli_fetch_array($resultado)){
                    $interes = getInteres($con, $fila['banco_id']);
                    if($fila['fecha_pagado']<$fila['fecha_pago']){
                        $datos.="<tr><td>".$filas['id']."</td><td>0</td><td>Pagado</td><tr>";
                    }else if($fila['fecha_pagado']<$fecha){
                        $your_date = strtotime($fila['fecha_pagado']);
                        $datediff = $fecha1 - $your_date;
                        $cobro=round($datediff / (60 * 60 * 24))*$interes;
                        $cobro*=$filas['valor'];
                        $sql = "UPDATE creditos 
                                SET valor=".$fila['valor']+$cobro."
                                WHERE id =".$fila['id'];
                        if(!mysqli_query($con,$sql)){
                            echo "Error: ".mysqli_error($con)."<br>";
                        }
                        $datos.="<tr><td>".$filas['id']."</td><td>".$cobro."</td><td>Pagado, intereses por los días de mora</td><tr>";
                    }else{
                        //enviar correo
                        $cobro=30*$interes*$filas['valor'];
                        $sql = "UPDATE creditos 
                                SET valor=".$fila['valor']+$cobro."
                                WHERE id =".$fila['id'];
                        if(!mysqli_query($con,$sql)){
                            echo "Error: ".mysqli_error($con)."<br>";
                        }
                        $datos.="<tr><td>".$filas['id']."</td><td>".$cobro."</td><td>No pagado, intereses por un mes de mora</td><tr>";
                    }
                }
                return $datos;
            }
            function cobrarClientes($con, $fecha){
                date_default_timezone_set('America/Bogota');
                $fecha1 = time();
                $sql = "SELECT id, cliente_id, valor, tasa_interes, fecha_pago, fecha_pagado, banco_id
                        FROM creditos
                        WHERE estado='APROBADO' AND cliente_id IS NOT NULL
                        ORDER BY valor DESC";
                $resultado = mysqli_query($con,$sql);
                $datos="";
                while($fila = mysqli_fetch_array($resultado)){
                    $interes = getInteres($con, $fila['banco_id']);
                    $valor=$fila['valor'];
                    $sql = "SELECT * FROM CUENTAS_AHORRO WHERE cliente_id=".$fila['cliente_id'];
                    $resultado1 = mysqli_query($con,$sql);
                    $pagos = array();
                    $cant =0;
                    while($row = mysqli_fetch_array($resultado1)){
                        if($valor<$row['saldo']){
                            $sql = "UPDATE creditos 
                                    SET valor=0,
                                        fecha_pagado='$fecha',
                                        pagado = 1;
                                    WHERE id =".$fila['id'];
                            if(!mysqli_query($con,$sql)){
                                echo "Error: ".mysqli_error($con)."<br>";
                            }
                            $valor = 0;    
                        break;
                        }else{
                            $valor-=$row['saldo'];
                            $cant++;
                        }
                    }
                    if($valor == 0){
                        //cobrar a cuentas mismo sql pero miras que solo hagas cant+1
                        $datos.="<tr><td>".$filas['id']."</td><td>".$valor."</td><td>Pagado</td><tr>";
                    }else{
                        $datos.="<tr><td>".$filas['id']."</td><td>0</td><td>Saldo insuficiente</td><tr>";
                    }
                }
                return $datos;
            }
            function pagarTarjetas($con){
                $sql = "SELECT id, cliente_id, valor, tasa_interes, fecha_pago, fecha_pagado, banco_id
                        FROM creditos
                        WHERE estado='APROBADO' AND cliente_id IS NOT NULL";

            }
            function getInteres($con, $banco){
                $sql = "SELECT interes_mora from BANCOS WHERE id = ".$banco;
                $resultado1 = mysqli_query($con,$sql);
                $row1 = mysqli_fetch_array($resultado1);
                return $row1['interes_mora'];
            }
        ?>
    </body>
</html>
