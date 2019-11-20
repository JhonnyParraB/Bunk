<?php
    session_start();
?>
<!DOCTYPE HTML>
<html>

<head>
    <meta charset="UTF-8">
    <title></title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>
    <h1>Solicitar Credito</h1>

    <?php
    include_once dirname(__FILE__) . '/../config.php';
    include_once dirname(__FILE__) . '/../utils/utils.php';


    $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
    $sql = 'SELECT * FROM BANCOS';
    $resultado = mysqli_query($con, $sql);
    $bancos = array();
    while ($fila = mysqli_fetch_array($resultado)) {
        $bancos["'" . $fila['id'] . "'"] = $fila['nombre'];
    }


    $formularioSolicitarCredito = "";
    $formularioSolicitarCredito .= '<form action="solicitar_credito.php" method="post">';
    if(isset($_SESSION['Rol']) and $_SESSION['Rol'] == 'User'){
        $formularioSolicitarCredito .='<input type=submit name=salir value=Salir></input><br>';
    }

    $formularioSolicitarCredito .= crearSelect('Banco', 'banco', $bancos);


    //ESTO SE DEBE EXTRAER DE LA SESIÓN
    if(isset($_SESSION['Persona']))
        $usuarioAutenticado=true;
    else $usuarioAutenticado = false;
    $formulario = "";
    if ($usuarioAutenticado) {
        $formulario = array(
            array('Tasa de interés propuesta', 'tasa_interes_propuesta', 'number', ''),
            array('Fecha de pago', 'fecha_pago', 'date', ''),
            array('Valor', 'valor', 'javecoin', ''),
            array('Solicitar Crédito', 'solicitar_credito', 'submit', '')
        );
    } else {
        $formulario = array(
            array('Cedula', 'cedula', 'number', ''),
            array('E-Mail', 'email', 'email', ''),
            array('Fecha de pago', 'fecha_pago', 'date', ''),
            array('Valor', 'valor', 'javecoin', ''),
            array('Solicitar Crédito', 'solicitar_credito', 'submit', '')
        );
    }



    $formularioSolicitarCredito .= crearFormulario2($formulario);
    $formularioSolicitarCredito .= '</form>';
    echo $formularioSolicitarCredito;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {



        if (isset($_POST['solicitar_credito'])) {


            $banco_id = $_POST['banco'];


            $sql = "SELECT * FROM BANCOS WHERE id=$banco_id";
            $resultado = mysqli_query($con, $sql);
            $row = mysqli_fetch_array($resultado);

            $interes_credito_visitantes = $row['interes_credito_visitantes'];
            $nombre_banco = $row['nombre'];

            //ESTO DEBERIA EXTRAERSE DE LA SESIÓN
            if(isset($_SESSION['Persona']))
                $usuarioAutenticado = true;
            else
                $usuarioAutenticado = false;
            $valor = $_POST['valor'];
            $fecha_pago = $_POST['fecha_pago'];

            //POR DEFECTO
            $tasa_interes = $interes_credito_visitantes;
            $cedula = "";
            $email = "";

            date_default_timezone_set('America/Bogota');
            $fecha_solicitud = date('Y-m-d H:i:s', time());


            if ($usuarioAutenticado) {
                //ESTO DEBERIA EXTRAERSE DE LA SESIÓN
                $cliente_id = $_SESSION['Persona'];
                $sql = "SELECT * FROM CLIENTES WHERE id = $cliente_id";
                $resultado = mysqli_query($con, $sql);
                $row = mysqli_fetch_array($resultado);
                $cedula = $row['cedula'];
                $email = $row['email'];

                if (isset($_POST['tasa_interes_propuesta'])) {
                    $tasa_interes = $_POST['tasa_interes_propuesta'];
                }

                $sql = "INSERT INTO CREDITOS (tasa_interes, fecha_pago, valor, cliente_id, banco_id, fecha_solicitud)
                                VALUES ($tasa_interes, '$fecha_pago', $valor, $cliente_id, $banco_id, '$fecha_solicitud')";
            } else {

                $cedula = $_POST['cedula'];
                $email = $_POST['email'];


                $sql = "SELECT * FROM VISITANTES WHERE Cedula = '$cedula'";
                $resultado = mysqli_query($con, $sql);
                if (mysqli_num_rows($resultado) > 0) {
                    $sql = "UPDATE VISITANTES
                                    SET email='$email'
                                    WHERE cedula = $cedula";
                } else {
                    $sql = "INSERT INTO VISITANTES (cedula, email)
                                    VALUES ($cedula, '$email')";
                }
                mysqli_query($con, $sql);
                $sql = "SELECT * FROM VISITANTES WHERE Cedula = '$cedula'";
                $resultado = mysqli_query($con, $sql);
                $row = mysqli_fetch_array($resultado);
                $visitante_id = $row['id'];

                $sql = "INSERT INTO CREDITOS (tasa_interes, fecha_pago, valor, visitante_id, banco_id, fecha_solicitud)
                                VALUES ($tasa_interes, '$fecha_pago', $valor, $visitante_id, $banco_id, '$fecha_solicitud')";
            }




            if (mysqli_query($con, $sql)) {
                echo "Se ha solicitado el crédito al banco $nombre_banco con una tasa de interés de $tasa_interes%.</br>";
                echo "Su solicitud será evaluada por un administrador, se le enviará un correo con la aprobación o rechazo.";
            } else {
                echo 'Hubo un error al solicitar su credito: ' . mysqli_error($con);
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