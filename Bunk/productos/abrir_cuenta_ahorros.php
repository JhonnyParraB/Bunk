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
            $formularioAbrirCuentaAhorros .= '<label for="banco">Banco: </label>';
            $formularioAbrirCuentaAhorros .= crearSelect($bancos, 'banco');

            $formulario = array(
                'Crear cuenta de ahorros' => 'submit'
            );

            $formularioAbrirCuentaAhorros .= crearFormulario($formulario);
            $formularioAbrirCuentaAhorros .= '</form>';
            echo $formularioAbrirCuentaAhorros;

            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                if (isset($_POST['Crear cuenta de ahorros'])){
                    $id_banco = $_POST['banco'];
                    

                    $sql = "SELECT * FROM BANCOS WHERE id=$id_banco";
                    $resultado = mysqli_query($con , $sql);
                    $row = mysqli_fetch_array($resultado);

                    $cuota_manejo = $row['cuota_cuenta_ahorros'];
                    $saldo_inial = 0;
                    //ESTO SE DEBE EXTRAER DE LA SESIÓN:
                    $cliente_id = 1;

                     

                    $sql = "INSERT INTO CUENTAS_AHORRO (saldo, cliente_id, cuota_manejo, id_banco) ";
                }
            }

        ?>
    </body>
</html>