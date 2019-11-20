<?php
    session_start();
    if (!isset($_SESSION['Rol']) || $_SESSION['Rol'] != 'User') {
        header('Location: ../login_registro/login.php');
    }
?>
<!DOCTYPE HTML>
<html>

<head>
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>

<body>
    <form method="POST" action=<?$_SERVER['PHP_SELF']?>>
        <input type=submit name=salir value=Salir></input>
    </form>
    <h1>Hacer una compra con la tarjeta de crédito</h1>

    <?php
    include_once dirname(__FILE__) . '/../config.php';
    include_once dirname(__FILE__) . '/../utils/utils.php';

    //ESTO DEBERIA EXTRAERSE DE LA SESIÓN
    $cliente_id = $_SESSION['Persona'];

    $formularioCompra = "";
    $formularioCompra .= '<form action="compra.php" method="post">';
    $formulario = array(
        array('Valor de la compra', 'valor', 'javecoin', '')
    );
    $formularioCompra .= crearFormulario2($formulario);

    $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
    $sql = "SELECT tc.id AS id, ca.id AS id_cuenta, b.nombre AS nombre_banco
                    FROM TARJETAS_CREDITO tc, CUENTAS_AHORRO ca, BANCOS b 
                    WHERE ca.cliente_id = $cliente_id AND ca.id=tc.cuenta_ahorro_id AND ca.banco_id = b.id AND tc.estado='APROBADA'";

    $resultado = mysqli_query($con, $sql);
    $tarjetas_credito = array();
    while ($fila = mysqli_fetch_array($resultado)) {
        $tarjetas_credito["'" . $fila['id'] . "'"] = 'Tarjeta: ' . $fila['id'] . ", Cuenta: " . $fila['id_cuenta'] . ", Banco: " . $fila['nombre_banco'];
    }

    $formularioCompra .= crearSelect('Tarjetas de credito', 'tarjeta_credito', $tarjetas_credito) . '</br>';
    $cuotas = array();
    for ($i = 1; $i <= 6; $i++) {
        $cuotas["'$i'"] = "$i Cuotas";
        if ($i == 1)
            $cuotas["'$i'"] = "$i Cuota";
    }
    $formularioCompra .= crearSelect('Cuotas', 'cuotas', $cuotas);
    $formulario = array(
        array('Comprar', 'comprar', 'submit', '')
    );
    $formularioCompra .= crearFormulario2($formulario);
    $formularioCompra .= '</form>';
    echo $formularioCompra;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        if (isset($_POST['comprar'])) {
            $cuotas = $_POST['cuotas'];
            $valor = $_POST['valor'];
            $tarjeta = $_POST['tarjeta_credito'];
            date_default_timezone_set('America/Bogota');
            $fecha = date('Y-m-d', time());
            $sql = "INSERT INTO COMPRAS (cuotas, valor, cuotas_restantes, fecha, tarjeta_credito_id)
            VALUES($cuotas, $valor, $cuotas, '$fecha', $tarjeta)";


            if (mysqli_query($con, $sql)) {
                echo "Se ha realizado una compra por el valor de $valor JaveCoins con la tarjeta $tarjeta, el pago se hará a $cuotas cuotas.</br>";
               
            } else {
                echo 'Hubo un error al intentar realizar la compra' . mysqli_error($con);
            }
        }
    }
    if(isset($_POST['salir'])){
        $_SESSION = array();
        session_destroy();
        header('Location: ../index.php');
    }

    ?>
</body>

</html>