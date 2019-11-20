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
        <input type=submit name=fin value='Fin de mes' />
    </form>
    <?php
    include_once '../config.php';
    $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
    if (mysqli_connect_errno()) {
        echo "Error en la conexión: " . mysqli_conecct_error() . "<br>";
    }
    if (isset($_POST['fin'])) {
        date_default_timezone_set('America/Bogota');
        $fecha = date('Y-m-d', time());
        $sql = "SELECT fecha FROM DIAS_FESTIVOS WHERE fecha = '$fecha'";
        $resultado = mysqli_query($con, $sql);
        if (mysqli_num_rows($resultado) == 0) {
            // creditos
            $datos = "<h2>Créditos</h2>
                <table class='table'>
                                <tr>
                                    <th>Id</th>
                                    <th>Cobrado</th>
                                    <th>Resultado</th>
                                </tr>";
            $datos.=cobrarVisitantes($con);
            $datos.=cobrarClientes($con, $fecha);
            $datos .= "</table>";
            echo $datos;
            // tarjetas
            pagarTarjetas($con);
            incrementarCuentasAhorro($con);
            cobrarCuotasTarjetasCuentas($con);
        } else {
            echo "Es festivo, porfavor intentelo un día hábil<br>";
        }
    }

    function cobrarVisitantes($con)
    {
        date_default_timezone_set('America/Bogota');
        $fecha1 = time();
        
        $sql = "SELECT id, visitante_id, valor, tasa_interes, fecha_pago, fecha_pagado, banco_id
                        FROM creditos
                        WHERE estado='APROBADO' AND visitante_id IS NOT NULL";
        $resultado = mysqli_query($con, $sql);
        $datos = "";
        while ($fila = mysqli_fetch_array($resultado)) {
            $interes = getInteres($con, $fila['banco_id']);
            if ($fila['fecha_pagado'] < $fila['fecha_pago']) {
                $datos .= "<tr><td>" . $fila['id'] . "</td><td>0</td><td>Pagado</td><tr>";
            } else if ($fila['fecha_pagado'] < $fecha) {
                $your_date = strtotime($fila['fecha_pagado']);
                $datediff = $fecha1 - $your_date;
                $cobro = round($datediff / (60 * 60 * 24)) * $interes;
                $cobro *= $fila['valor'];
                
                $sql = "UPDATE creditos 
                                SET valor=" . $fila['valor'] + $cobro . "
                                WHERE id =" . $fila['id'];
                if (!mysqli_query($con, $sql)) {
                    echo "Error: " . mysqli_error($con) . "<br>";
                }
                $datos .= "<tr><td>" . $fila['id'] . "</td><td>" . $cobro . "</td><td>Pagado, intereses por los días de mora</td><tr>";
            } else {
                //enviar correo
                $cobro = 30 * $interes * $fila['valor'];
                
                $sql = "UPDATE creditos 
                                SET valor=" . $fila['valor'] + $cobro . "
                                WHERE id =" . $fila['id'];
                if (!mysqli_query($con, $sql)) {
                    echo "Error: " . mysqli_error($con) . "<br>";
                }
                $datos .= "<tr><td>" . $fila['id'] . "</td><td>" . $cobro . "</td><td>No pagado, intereses por un mes de mora</td><tr>";
            }
        }
        return $datos;
    }
    function cobrarClientes($con, $fecha)
    {
        date_default_timezone_set('America/Bogota');
        $fecha1 = time();
        
        $sql = "SELECT id, cliente_id, valor, tasa_interes, fecha_pago, fecha_pagado, banco_id
                        FROM creditos
                        WHERE estado='APROBADO' AND cliente_id IS NOT NULL
                        ORDER BY valor DESC";
        $resultado = mysqli_query($con, $sql);
        $datos = "";
        while ($fila = mysqli_fetch_array($resultado)) {
            $interes = getInteres($con, $fila['banco_id']);
            $valor = $fila['valor'];
            
            $sql = "SELECT * FROM CUENTAS_AHORRO WHERE cliente_id=" . $fila['cliente_id'];
            $resultado1 = mysqli_query($con, $sql);
            $pagos = array();
            $cant = 0;
            while ($row = mysqli_fetch_array($resultado1)) {
                if ($valor < $row['saldo']) {
                    
                    $sql = "UPDATE creditos 
                                    SET valor=0,
                                        fecha_pagado='$fecha',
                                        pagado = 1
                                    WHERE id =" . $fila['id'];
                    if (!mysqli_query($con, $sql)) {
                        echo "Error: " . mysqli_error($con) . "<br>";
                    }
                    $valor = 0;
                    break;
                } else {
                    $valor -= $row['saldo'];
                    $cant++;
                }
            }
            if ($valor == 0) {
                //cobrar a cuentas mismo sql pero miras que solo hagas cant+1
                $datos .= "<tr><td>" . $fila['id'] . "</td><td>" . $valor . "</td><td>Pagado</td><tr>";
            } else {
                $datos .= "<tr><td>" . $fila['id'] . "</td><td>0</td><td>Saldo insuficiente</td><tr>";
            }
        }
        return $datos;
    }
    function pagarTarjetas($con)
    {
        $sql = "SELECT id, tarjeta_credito_id , cuotas, cuotas_restantes, valor
                        FROM COMPRAS
                        WHERE pagada=0";
        $resultado = mysqli_query($con, $sql);
        while ($row = mysqli_fetch_array($resultado)) {
            $tarjeta_credito_id = $row['tarjeta_credito_id'];
            $compra_id = $row['id'];
            $cuotas = $row['cuotas'];
            $valor = $row['valor'];
            $cuotas_restantes = $row['cuotas_restantes'];
            $sql = "SELECT ca.id AS cuenta_ahorro_id, ca.saldo AS saldo, tc.tasa_interes AS tasa_interes
                            FROM CUENTAS_AHORRO ca, TARJETAS_CREDITO tc
                            WHERE tc.id =$tarjeta_credito_id AND tc.cuenta_ahorro_id=ca.id AND tc.estado='APROBADA'";

            $resultado1 = mysqli_query($con, $sql);
            $row1 = mysqli_fetch_array($resultado1);
            $tasa_interes = $row1['tasa_interes'];
            $saldo = $row1['saldo'];
            $cuenta_ahorro_id = $row1['cuenta_ahorro_id'];
            $valorAPagar = 0;
            $valorAPagar = $valor / $cuotas;
            //es el primer pago
            if ($cuotas_restantes != $cuotas) {
                $valorAPagar += ($valorAPagar * $tasa_interes);
            }
            if ($saldo >= $valorAPagar) {
                $nuevo_saldo = $saldo - $valorAPagar;
                $cuotas_restantes = $cuotas_restantes - 1;
                date_default_timezone_set('America/Bogota');
                $fecha = date('Y-m-d', time());
                $sql = "START TRANSACTION;
                                UPDATE CUENTAS_AHORRO SET saldo=$nuevo_saldo WHERE id=$cuenta_ahorro_id;
                                UPDATE COMPRAS SET cuotas_restantes=$cuotas_restantes WHERE id=$compra_id;

                                INSERT INTO RETIROS (monto, fecha, cuenta_ahorro_id, actor) 
                                VALUES ($valorAPagar, $fecha, $cuenta_ahorro_id, 'BANCO');";

                if ($cuotas_restantes == 0)
                    $sql .= "UPDATES COMPRAS SET pagada=1 WHERE id=$compra_id;";
                $sql .= 'COMMIT;';

                if (mysqli_multi_query($con, $sql)) {
                    echo "Compra $compra_id cobrada a la cuenta $cuenta_ahorro_id por un valor de $valorAPagar JaveCoins" . "</br>";
                } else {
                    echo "Hubo un error al cobrar la compra $compra_id de la cuenta $cuenta_ahorro_id: " . mysqli_error($con);
                }
            } else {
                echo "No hay suficientes fondos en la cuenta de ahorros $cuenta_ahorro_id, se enviará un correo al cliente." . "</br>";
            }
        }
    }
    function getInteres($con, $banco)
    {
        $sql = "SELECT interes_mora from BANCOS WHERE id = " . $banco;
        $resultado1 = mysqli_query($con, $sql);
        $row1 = mysqli_fetch_array($resultado1);
        return $row1['interes_mora'];
    }
    function incrementarCuentasAhorro($con)
    {
        $sql = "SELECT b.interes_cuenta_ahorros AS tasa_interes, ca.id AS cuenta_ahorro_id, ca.saldo AS saldo
                FROM BANCOS b, CUENTAS_AHORRO ca
                WHERE ca.banco_id = b.id";
        $resultado = mysqli_query($con, $sql);
        while ($row = mysqli_fetch_array($resultado)) {
            $tasa_interes = $row['tasa_interes'];
            $cuenta_ahorro_id = $row['cuenta_ahorro_id'];
            $saldo = $row['saldo'];
            $saldo_viejo = $saldo;
            $saldo += $saldo * $tasa_interes;
            $sql = "UPDATE CUENTAS_AHORRO SET saldo=$saldo WHERE id=$cuenta_ahorro_id";

            if (mysqli_query($con, $sql)) {
                echo "Se incrementa la cuenta de ahorros con número $cuenta_ahorro_id de $saldo_viejo JaveCoins a $saldo JaveCoins con una tasa de interés de $tasa_interes" . "</br>";
            } else {
                echo "Hubo un error al incrementar el saldo de la cuenta de ahorros con número $cuenta_ahorro_id: " . mysqli_error($con);
            }
        }
    }

    function cobrarCuotasTarjetasCuentas($con)
    {

        $sql = "SELECT tc.id AS tarjeta_credito_id, tc.cuota_manejo AS cuota_manejo, ca.id AS cuenta_ahorro_id, ca.saldo AS saldo
                FROM TARJETAS_CREDITO tc, CUENTAS_AHORRO ca
                WHERE tc.cuenta_ahorro_id=ca.id";
        $resultado = mysqli_query($con, $sql);
        while ($row = mysqli_fetch_array($resultado)) {
            $cuota_manejo = $row['cuota_manejo'];
            $cuenta_ahorro_id = $row['cuenta_ahorro_id'];
            $tarjeta_credito_id = $row['tarjeta_credito_id'];
            $saldo = $row['saldo'];
            if ($saldo < $cuota_manejo) {
                echo "No se puede cobrar la cuota de manejo de la tarjeta $tarjeta_credito_id a $cuenta_ahorro_id";
            } else {
                $saldo_viejo = $saldo;
                $saldo -= $cuota_manejo;
                $sql = "UPDATE CUENTAS_AHORRO SET saldo=$saldo WHERE id=$cuenta_ahorro_id";

                if (mysqli_query($con, $sql)) {
                    echo "Se cobró la cuota de manejo de la tarjeta con número $tarjeta_credito_id asociada a la cuenta $cuenta_ahorro_id por valor de $cuota_manejo" . "</br>";
                } else {
                    echo "Hubo un error al incrementar el saldo de la cuenta de ahorros con número $cuenta_ahorro_id: " . mysqli_error($con);
                }
            }
        }
    }


    ?>
</body>

</html>