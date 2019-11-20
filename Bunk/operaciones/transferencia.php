<!DOCTYPE HTML>
<html>

<head>
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>

<body>
    <h1>Hacer una transferencia</h1>

    <?php
    include_once dirname(__FILE__) . '/../config.php';
    include_once dirname(__FILE__) . '/../utils/utils.php';

    //ESTO DEBERIA EXTRAERSE DE LA SESIÓN
    $cliente_id = 1;


    $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
    $sql = "SELECT ca.id AS id_cuenta_ahorro, b.nombre AS nombre_banco
                    FROM CUENTAS_AHORRO ca, CLIENTES c, BANCOS b 
                    WHERE c.id=$cliente_id AND c.id=ca.cliente_id AND b.id=ca.banco_id";
    $resultado = mysqli_query($con, $sql);
    $cuentas_ahorro = array();
    while ($fila = mysqli_fetch_array($resultado)) {
        $cuentas_ahorro["'" . $fila['id_cuenta_ahorro'] . "'"] = $fila['id_cuenta_ahorro'] . ": " . $fila['nombre_banco'];
    }


    $formularioTransferencia = "";
    $formularioTransferencia .= '<form action="transferencia.php" method="post">';
    $formularioTransferencia .= crearSelect('Cuenta de ahorro de origen', 'cuenta_ahorro', $cuentas_ahorro) . '</br>';
    $tipo_destino = array(
        'cuenta_ahorro' => 'Cuenta de ahorros',
        'credito' => 'Crédito'
    );
    $formularioTransferencia .= crearSelect('Tipo de destino', 'tipo_destino', $tipo_destino);

    $formulario = array(
        array('Numero (cuenta de ahorros/crédito)', 'numero_destino', 'number', ''),
        array('Monto', 'monto', 'javecoin', ''),
        array('Realizar transferencia', 'transferir', 'submit', ''),

    );

    $formularioTransferencia .= crearFormulario2($formulario);
    $formularioTransferencia .= '</form>';
    echo $formularioTransferencia;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        if (isset($_POST['transferir'])) {
            $cuenta_origen_id = $_POST['cuenta_ahorro'];
            $monto = $_POST['monto'];
            $numero_destino = $_POST['numero_destino'];

            $sql = "SELECT ca.saldo AS saldo, b.nombre AS nombre_banco, b.costo_transferencia AS costo_transferencia 
                    FROM CUENTAS_AHORRO ca, BANCOS b 
                    WHERE ca.id=$cuenta_origen_id AND b.id=ca.banco_id";
            mysqli_query($con, $sql);
            $resultado = mysqli_query($con, $sql);
            $row = mysqli_fetch_array($resultado);
            $saldo_origen = $row['saldo'];
            $banco_origen = $row['nombre_banco'];
            $costo_transferencia = $row['costo_transferencia'];

            date_default_timezone_set('America/Bogota');
            $fecha = date('Y-m-d', time());

            if ($saldo_origen < ($monto + $costo_transferencia)) {
                echo 'Error en la transferencia: No dispone de los suficientes fondos de la cuenta de ahorros indicada';
            } else {
                if ($_POST['tipo_destino'] == 'cuenta_ahorro') {
                    $sql = "SELECT ca.saldo AS saldo, b.nombre AS nombre_banco 
                            FROM CUENTAS_AHORRO ca, BANCOS b 
                            WHERE ca.id=$numero_destino AND b.id=ca.banco_id AND ca.id!=$cuenta_origen_id";
                    mysqli_query($con, $sql);
                    $resultado = mysqli_query($con, $sql);
                    if (mysqli_num_rows($resultado) > 0) {
                        $row = mysqli_fetch_array($resultado);
                        $saldo_destino = $row['saldo'];
                        $banco_destino = $row['nombre_banco'];
                        $saldo_destino += $monto;
                        $saldo_origen -= $monto-$costo_transferencia;

                        $sql = "START TRANSACTION;
                        
                                UPDATE CUENTAS_AHORRO SET saldo=$saldo_origen WHERE id=$cuenta_origen_id;
                                UPDATE CUENTAS_AHORRO SET saldo=$saldo_destino WHERE id=$numero_destino;
                                
                                INSERT INTO TRANSFERENCIAS (valor, origen_cuenta_ahorro_id, destino_cuenta_ahorro_id, fecha)
                                VALUES($monto, $cuenta_origen_id, $numero_destino, '$fecha');

                                COMMIT;";

                        if (mysqli_multi_query($con, $sql)) {
                            echo "Se ha ha realizado la transferencia de $monto JaveCoins de la cuenta $cuenta_origen_id del banco $banco_origen a la cuenta $numero_destino del banco $banco_destino .</br>";
                            echo "Se ha debitado el costo de transferencia del banco $banco_origen que es de $costo_transferencia JaveCoins";
                        } else {
                            echo 'Hubo un error al intentar transferir el monto indicado' . mysqli_error($con);
                        }
                    } else {
                        echo 'Error en la transferencia: El id de la cuenta de ahorros de destino no está registrada en el sistema.</br>';
                        echo 'Recuerde que el número de la cuenta de destino, debe ser distinto al número de la cuenta de origen.';
                    }
                } else if ($_POST['tipo_destino'] == 'credito') {
                    $sql = "SELECT cr.valor AS valor, cr.tasa_interes AS tasa_interes, b.nombre AS nombre_banco FROM CREDITOS cr, BANCOS b WHERE cr.id=$numero_destino AND b.id=cr.banco_id AND cr.estado='APROBADO' AND cr.pagado=0";
                    mysqli_query($con, $sql);
                    $resultado = mysqli_query($con, $sql);
                    if (mysqli_num_rows($resultado) > 0) {
                        $row = mysqli_fetch_array($resultado);
                        $valor_credito = $row['valor'];
                        $tasa_interes = $row['tasa_interes'];
                        $banco_destino = $row['nombre_banco'];
                        $valor_mas_interes = ($valor_credito * (1 + ($tasa_interes / 100)))  + $costo_transferencia;
                        if ($monto != $valor_mas_interes)
                            echo "Error en la transferencia: Se debe pagar un total de $valor_mas_interes JaveCoins del valor del crédito y los intereses, así como el costo de la transferencia, asegurese de poner la misma cantidad en el monto de la transferencia";
                        else {

                            $saldo_origen -= $monto - $costo_transferencia;

                            $sql = "START TRANSACTION;
                        
                                UPDATE CUENTAS_AHORRO SET saldo=$saldo_origen WHERE id=$cuenta_origen_id;
                                UPDATE CREDITOS SET pagado=1, estado='PAGADO', fecha_pagado='$fecha'  WHERE id=$numero_destino;
                                
                                INSERT INTO TRANSFERENCIAS (valor, origen_cuenta_ahorro_id, destino_credito_id, fecha)
                                VALUES($monto, $cuenta_origen_id, $numero_destino, '$fecha');

                                COMMIT;";
                            if (mysqli_multi_query($con, $sql)) {
                                echo "Se ha realizado la transferencia de $monto JaveCoins de la cuenta $cuenta_origen_id del banco $banco_origen al credito $numero_destino del banco $banco_destino.</br>";
                                echo "La deuda del crédito ha quedado saldada.</br>";
                                echo "Se ha debitado el costo de transferencia del banco $banco_origen que es de $costo_transferencia JaveCoins";
                            } else {
                                echo 'Hubo un error al intentar transferir el monto indicado' . mysqli_error($con);
                            }
                        }
                    } else {
                        echo 'Error en la transferencia: El id del crédito de destino no está registrado en el sistema, no está aprobado o ya fue pagado';
                    }
                }
            }
        }
    }

    ?>
</body>

</html>