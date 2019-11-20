<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Editar Cuenta</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    </head>
    <body>
        <form method="POST">
        <input type=submit name=salir value=Salir class='salirBtn'/>
        <h2>Editar Cuenta</h2>
        <?php
            include_once '../../config.php';
            $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
            if(mysqli_connect_errno()){
                echo "Error en la conexión: ".mysqli_conecct_error()."<br>";
            }
            include "../../utils/utils.php";
            $cuenta=$_SESSION['cuenta'];
            $sql = "SELECT id, cuota_manejo from CUENTAS_AHORRO WHERE id = $cuenta";
            $resultado = mysqli_query($con,$sql);
            $row = mysqli_fetch_array($resultado);
            $datos = array(
                array('Cuota de manejo', 'cuota', 'javecoin', $row['cuota_manejo']),
                array('Volver','volver', 'submit'),
                array('Guardar', 'guardar', 'submit'));  
            
            $linea=crearFormulario2($datos);
            echo $linea;
            if (isset($_POST['guardar'])) {
                $cuota = validar($_POST['cuota']);
                $errores="";
                if(!is_numeric($cuota)){
                    $errores.= "Cuota de manejo: Solo se permiten números.<br>";
                }
                if($errores!=""){
                    echo $errores;
                }else{
                    $sql = "UPDATE CUENTAS_AHORRO 
                            SET cuota_manejo='$cuota'
                            WHERE id = $cuenta";
                    if(mysqli_query($con,$sql)){
                        header('Location: listar_cuentas.php');
                    }else{
                        echo "Error: ".mysqli_error($con)."<br>";
                    }
                }
            }
            if (isset($_POST['volver'])) {
                header('Location: listar_cuentas.php');
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