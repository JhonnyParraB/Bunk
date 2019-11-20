<?php
    session_start();
    if (!isset($_SESSION['Rol']) || $_SESSION['Rol'] != 'Admin') {
        header('Location: ../../login_registro/login.php');
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Responder Tarjeta de Crédito</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    </head>
    <body>
        <form method="POST">
            <input type=submit name=salir value=Salir class='salirBtn'/>
            <h2>Responder Tarjeta de Crédito</h2>
            <?php
                include_once '../../config.php';
                $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
                if(mysqli_connect_errno()){
                    echo "Error en la conexión: ".mysqli_conecct_error()."<br>";
                }
                include "../../utils/utils.php";
                $tarjeta=$_SESSION['tcredito'];
                $sql = "SELECT * from TARJETAS_CREDITO WHERE id = $tarjeta";
                $resultado = mysqli_query($con,$sql);
                $row = mysqli_fetch_array($resultado);
                $datos = array(
                        array('Cupo máximo', 'cupo', 'text', ""),
                        array('Sobrecupo', 'sobre', 'text', ""),
                        array('Cuota de manejo', 'cuota', 'text', ""),
                        array('Tasa de interés', 'interes', 'text', ""));
                $linea=crearFormulario2($datos);
                echo $linea;
            ?>
            <label for='mensaje'><b>Mensaje:</b></label><br>
            <textarea rows="4" cols="30" name='mensaje'></textarea>
            <?php
                $datos=array();
                array_push($datos, array('Aceptar', 'aceptar', 'submit', ""));
                array_push($datos, array('Rechazar', 'rechazar', 'submit', ""));
                array_push($datos, array('Volver', 'volver', 'submit', ""));
                
                $linea=crearFormulario2($datos);
                echo $linea;
                date_default_timezone_set('America/Bogota');
                $fecha_solicitud = date('Y-m-d H:i:s', time());
                if (isset($_POST['aceptar'])) {
                    $cupo = validar($_POST['cupo']);
                    $sobre = validar($_POST['sobre']);
                    $cuota = validar($_POST['cuota']);
                    $tasa = validar($_POST['interes']);
                    $mensaje = validar($_POST['mensaje']);
                    $errores="";
                    if(!is_numeric($cupo)){
                        $errores.= "Cupo máximo: Solo se permiten números.<br>";
                    }
                    if(!is_numeric($sobre)){
                        $errores.= "Sobrecupo: Solo se permiten números.<br>";
                    }
                    if(!is_numeric($cuota)){
                        $errores.= "Cuota de manejo: Solo se permiten números.<br>";
                    }
                    if(!is_numeric($tasa)){
                        $errores.= "Tasa de interés: Solo se permiten números.<br>";
                    }
                    if(!validarNombres($mensaje)){
                        $errores.= "Mensaje: Solo se permiten letras y espacios.<br>";
                    }
                    if($errores!=""){
                        echo $errores;
                    }else{
                        $sql = "UPDATE TARJETAS_CREDITO 
                                SET cupo_maximo=$cupo,
                                    sobrecupo=$sobre,
                                    cuota_manejo=$cuota,
                                    tasa_interes=$tasa,
                                    estado='APROBADO',
                                    fecha_respuesta='$fecha_solicitud',
                                    mensaje='$mensaje'
                                WHERE id = $tarjeta";
                        if(mysqli_query($con,$sql)){
                            header('Location: ../centro_mensajes.php');
                        }else{
                            echo "Error: ".mysqli_error($con)."<br>";
                        }
                    }
                }
                if (isset($_POST['rechazar'])) {
                    $mensaje = validar($_POST['mensaje']);
                    if(!validarNombres($mensaje)){
                        $errores.= "Mensaje: Solo se permiten letras y espacios.<br>";
                    }
                    if($errores!=""){
                        echo $errores;
                    }else{
                        $sql = "UPDATE TARJETAS_CREDITO 
                                    SET estado='RECHAZADO',
                                        fecha_respuesta='$fecha_solicitud',
                                        mensaje='$mensaje'
                                    WHERE id = $tarjeta";
                        if(mysqli_query($con,$sql)){
                            header('Location: ../centro_mensajes.php');
                        }else{
                            echo "Error: ".mysqli_error($con)."<br>";
                        }
                        header('Location: ../centro_mensajes.php');
                    }
                }
                if (isset($_POST['volver'])) {
                    header('Location: ../centro_mensajes.php');
                }
                if(isset($_POST['salir'])){
                    $_SESSION = array();
                    session_destroy();
                    header('Location: ../../index.php');
                }
            ?>
        </form>
    </body>
</html>