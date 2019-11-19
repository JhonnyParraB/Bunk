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
            $formularioAbrirCuentaAhorros .= crearSelect($bancos, 'bancos');

            $formulario = array(
                'Crear cuenta de ahorros' => 'submit'
            );

            $formularioAbrirCuentaAhorros .= crearFormulario($formulario);
            $formularioAbrirCuentaAhorros .= '</form>';
            echo $formularioAbrirCuentaAhorros;

        ?>
    </body>
</html>