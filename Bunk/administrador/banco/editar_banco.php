<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Editar Bancos</title>
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
                            array('Interés Crédito Visitante', 'intvisitante', 'number', $row['interes_credito_visitantes']), 
                            array('Interés Cuenta Ahorro', 'intahorro', 'number', $row['interes_cuenta_ahorros']),
                            array('Costo Transferencia', 'inttransf', 'number', $row['costo_transferencia']),
                            array('Interés Mora', 'intmora', 'number', $row['interes_mora']),
                            array('Volver','volver', 'submit'),
                            array('Guardar', 'guardar', 'submit')); 
            $linea=crearFormulario2($datos);
            echo $linea;
            if (isset($_POST['guardar'])) {
                echo "toy";
                $intvisitante = $_POST['intvisitante'];
                $intahorro = $_POST['intahorro'];
                $inttransf = $_POST['inttransf'];
                $intmora = $_POST['intmora'];
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