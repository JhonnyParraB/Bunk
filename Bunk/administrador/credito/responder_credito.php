<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Responder Crédito</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    </head>
    <body>
        <form method="POST">
            <input type=submit name=salir value=Salir class='salirBtn'/>
            <h2>Responder Crédito</h2>
            <?php
                include_once '../../config.php';
                $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
                if(mysqli_connect_errno()){
                    echo "Error en la conexión: ".mysqli_conecct_error()."<br>";
                }
                include "../../utils/utils.php";
                $credito=$_SESSION['credito'];
                $sql = "SELECT * from CREDITOS WHERE id = $credito";
                $resultado = mysqli_query($con,$sql);
                $row = mysqli_fetch_array($resultado);
                $sql = "SELECT interes_credito_visitantes from BANCOS WHERE id = ".$row['banco_id'];
                $resultado = mysqli_query($con,$sql);
                $row1 = mysqli_fetch_array($resultado);
                $interes_visitantes = $row1['interes_credito_visitantes'];
                $datos = array(array('Fecha de pago', 'fechapago', 'date', $row['fecha_pago']));
                if($row['cliente_id']!=null){
                    array_push($datos, array('Tasa de interés', 'interes', 'text', $row['tasa_interes']));
                }else if($row['visitante_id']!=null){
                    array_push($datos, array('Tasa de interés', 'interes', 'text', $interes_visitantes));
                }
                array_push($datos, array('Aceptar', 'aceptar', 'submit', ""));
                array_push($datos, array('Rechazar', 'rechazar', 'submit', ""));
                array_push($datos, array('Volver', 'volver', 'submit', ""));
                
                $linea=crearFormulario2($datos);
                echo $linea;
                if (isset($_POST['aceptar'])) {
                    $tasa = validar($_POST['interes']);
                    $fecha = validar($_POST['fechapago']);
                    $errores="";
                    if(!is_numeric($interes)){
                        $errores.= "Tasa de interés: Solo se permiten números.<br>";
                    }
                    if($errores!=""){
                        echo $errores;
                    }else{
                        $sql = "UPDATE creditos 
                                SET tasa_interes='$tasa',
                                    fecha_pago='$fecha',
                                    estado='APROBADO',
                                    fecha_respuesta=CURDATE()
                                WHERE id = $credito";
                        if(mysqli_query($con,$sql)){
                            header('Location: ../centro_mensajes.php');
                        }else{
                            echo "Error: ".mysqli_error($con)."<br>";
                        }
                    }
                }
                if (isset($_POST['rechazar'])) {
                    $sql = "UPDATE creditos 
                                SET estado='RECHAZADO',
                                    fecha_respuesta=CURDATE()
                                WHERE id = $credito";
                    if(mysqli_query($con,$sql)){
                        header('Location: ../centro_mensajes.php');
                    }else{
                        echo "Error: ".mysqli_error($con)."<br>";
                    }
                    header('Location: ../centro_mensajes.php');
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