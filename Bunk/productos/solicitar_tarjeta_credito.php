<!DOCTYPE HTML>
<html>

<head>
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>

<body>
    <h1>Solicitar tarjeta de crédito</h1>

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


    $formularioSolicitarTarjetaCredito = "";
    $formularioSolicitarTarjetaCredito .= '<form action="solicitar_tarjeta_credito.php" method="post">';
    $formularioSolicitarTarjetaCredito .= crearSelect('Cuentas de ahorro', 'cuenta_ahorro', $cuentas_ahorro);

    $formulario = array(
        array('Solicitar Tarjeta de Crédito', 'solicitar_tarjeta_credito', 'submit', '')
    );

    $formularioSolicitarTarjetaCredito .= crearFormulario2($formulario);
    $formularioSolicitarTarjetaCredito .= '</form>';
    echo $formularioSolicitarTarjetaCredito;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        if (isset($_POST['solicitar_tarjeta_credito'])) {
            $cuenta_ahorro_id = $_POST['cuenta_ahorro'];


            $sql = "SELECT b.nombre AS nombre
                    FROM CUENTAS_AHORRO ca, BANCOS b 
                    WHERE ca.id=$cuenta_ahorro_id AND ca.banco_id=b.id";

            $resultado = mysqli_query($con, $sql);
            $row = mysqli_fetch_array($resultado);


            $nombre_banco = $row['nombre'];
            //ESTO SE DEBE EXTRAER DE LA SESIÓN:
            $cliente_id = 1;

            date_default_timezone_set('America/Bogota');
            $fecha_solicitud = date('Y-m-d H:i:s', time());



            $sql = "INSERT INTO TARJETAS_CREDITO (cuenta_ahorro_id, fecha_solicitud) 
                            VALUES ($cuenta_ahorro_id, '$fecha_solicitud')";
            if (mysqli_query($con, $sql)) {
                echo "Se ha solicitado una tarjeta de crédito que esté asociada a la cuenta de ahorro $cuenta_ahorro_id abierta en el banco $nombre_banco.</br>";
                echo "El administrador determinará si es aprobada, así como el cupo máximo, la cuota de manejo, la tasa de interés y el sobrecupo.";
            } else {
                echo 'Hubo un error al intentar abrir la cuenta de ahorros ' . mysqli_error($con);
            }
        }
    }

    ?>
</body>

</html>