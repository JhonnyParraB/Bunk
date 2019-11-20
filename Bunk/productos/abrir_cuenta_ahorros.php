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
        <h1>Abrir Cuenta de Ahorros</h1>

        <?php 
            include_once dirname(__FILE__) . '/../config.php';
            include_once dirname(__FILE__) . '/../utils/utils.php';


            $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
            $sql = 'SELECT * FROM BANCOS';
            $resultado = mysqli_query($con , $sql);
            $bancos = array();
            while ($fila = mysqli_fetch_array($resultado)){
                $bancos["'".$fila['id']."'"] = $fila['nombre'];
            }
            

            $formularioAbrirCuentaAhorros = "";
            $formularioAbrirCuentaAhorros .= '<form action="abrir_cuenta_ahorros.php" method="post">';
            $formularioAbrirCuentaAhorros .='<input type=submit name=salir value=Salir></input>';
            $formularioAbrirCuentaAhorros .= crearSelect('Banco', 'banco', $bancos);

            $formulario = array(
                array('Abrir Cuenta de Ahorros', 'abrir_cuenta_ahorros', 'submit', '')
            );

            $formularioAbrirCuentaAhorros .= crearFormulario2($formulario);
            $formularioAbrirCuentaAhorros .= '</form>';
            echo $formularioAbrirCuentaAhorros;

            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                
                if (isset($_POST['abrir_cuenta_ahorros'])){
                    $banco_id = $_POST['banco'];
                    

                    $sql = "SELECT * FROM BANCOS WHERE id=$banco_id";
                    
                    $resultado = mysqli_query($con , $sql);
                    $row = mysqli_fetch_array($resultado);

                    $cuota_manejo = $row['cuota_cuenta_ahorros'];
                    $interes_cuenta_ahorros = $row['interes_cuenta_ahorros'];
                    $nombre_banco = $row['nombre'];
                    $saldo_inial = 0;
                    //ESTO SE DEBE EXTRAER DE LA SESIÓN:
                    $cliente_id = $_SESSION['Persona'];

                     

                    $sql = "INSERT INTO CUENTAS_AHORRO (saldo, cliente_id, cuota_manejo, banco_id) VALUES ($saldo_inial, $cliente_id, $cuota_manejo, $banco_id)";
                    if (mysqli_query($con, $sql)) {
                        echo "Se ha abierto una cuenta de ahorros en el banco $nombre_banco, la cuota de manejo es $cuota_manejo JaveCoins y la tasa de interes es $interes_cuenta_ahorros%.</br>";
                        echo "El saldo inicial de la cuenta es 0 JaveCoins";
                    } else {
                        echo 'Hubo un error al intentar abrir la cuenta de ahorros '.mysqli_error($con);
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