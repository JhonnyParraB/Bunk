<?php
    session_start();
    if (!isset($_SESSION['Rol']) && $_SESSION['Rol'] == 'Admin') {
        header('Location: ../../login_registro/login.php');
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Editar Banco</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    </head>
    <body>
        <form method="POST">
        <input type=submit name=salir value=Salir class='salirBtn'/>
        <h2>Editar Banco</h2>
        <?php
            include_once '../../config.php';
            $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
            if(mysqli_connect_errno()){
                echo "Error en la conexión: ".mysqli_conecct_error()."<br>";
            }
            include "../../utils/utils.php";
            $banco=$_SESSION['banco'];
            $sql = "SELECT * from BANCOS WHERE id = $banco";
            $resultado = mysqli_query($con,$sql);
            $row = mysqli_fetch_array($resultado);
            $datos = array(
                            array('Interés Crédito Visitante', 'intvisitante', 'text', $row['interes_credito_visitantes']), 
                            array('Interés Cuenta Ahorro', 'intahorro', 'text', $row['interes_cuenta_ahorros']),
                            array('Costo Transferencia', 'inttransf', 'text', $row['costo_transferencia']),
                            array('Interés Mora', 'intmora', 'text', $row['interes_mora']),
                            array('Volver','volver', 'submit'),
                            array('Guardar', 'guardar', 'submit')); 
            $linea=crearFormulario2($datos);
            echo $linea;
            if (isset($_POST['guardar'])) {
                $intvisitante = validar($_POST['intvisitante']);
                $intahorro = validar($_POST['intahorro']);
                $inttransf = validar($_POST['inttransf']);
                $intmora = validar($_POST['intmora']);
                $errores="";
                if(!is_numeric($intvisitante)){
                    $errores.= "Interés Crédito Visitante: Solo se permiten números.<br>";
                }
                if(!is_numeric($intahorro)){
                    $errores.= "Interés Cuenta Ahorro: Solo se permiten números.<br>";
                }
                if(!is_numeric($inttransf)){
                    $errores.= "Costo Transferencia: Solo se permiten números.<br>";
                }
                if(!is_numeric($intmora)){
                    $errores.= "Interés Mora: Solo se permiten números.<br>";
                }
                if($errores!=""){
                    echo $errores;
                }else{
                    $sql = "UPDATE BANCOS 
                            SET interes_credito_visitantes=$intvisitante,
                                interes_cuenta_ahorros= $intahorro,
                                costo_transferencia=$inttransf,
                                interes_mora=$intmora
                            WHERE id = $banco";
                    if(mysqli_query($con,$sql)){
                        header('Location: listar_bancos.php');
                    }else{
                        echo "Error: ".mysqli_error($con)."<br>";
                    }
                }
            }
            if (isset($_POST['volver'])) {
                header('Location: listar_bancos.php');
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