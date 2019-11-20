<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Editar Crédito</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    </head>
    <body>
        <form method="POST">
        <input type=submit name=salir value=Salir class='salirBtn'/>
        <h2>Editar Crédito</h2>
        <?php
            include_once '../../config.php';
            $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
            if(mysqli_connect_errno()){
                echo "Error en la conexión: ".mysqli_conecct_error()."<br>";
            }
            include "../../utils/utils.php";
            $credito=$_SESSION['credito'];
            $sql = "SELECT tasa_interes, fecha_pago from CREDITOS WHERE id = $credito";
            $resultado = mysqli_query($con,$sql);
            $row = mysqli_fetch_array($resultado);
            $datos = array(
                array('Tasa de interés', 'interes', 'text', $row['tasa_interes']),
                array('Fecha de pago', 'fechapago', 'date', $row['fecha_pago']),
                array('Volver','volver', 'submit'),
                array('Guardar', 'guardar', 'submit'));  
            
            $linea=crearFormulario2($datos);
            echo $linea;
            if (isset($_POST['guardar'])) {
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
                                fecha_pago='$fecha'
                            WHERE id = $credito";
                    if(mysqli_query($con,$sql)){
                        header('Location: listar_creditos.php');
                    }else{
                        echo "Error: ".mysqli_error($con)."<br>";
                    }
                }
            }
            if (isset($_POST['volver'])) {
                header('Location: listar_creditos.php');
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