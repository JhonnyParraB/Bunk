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
            include_once dirname(__FILE__) . '../utils/utils.php';

            $formularioAbrirCuentaAhorros = "";
            $formularioAbrirCuentaAhorros .= '<form action="abrir_cuenta_ahorros.php" method="post">';
            
            $camposFormularioAbrirCuentaAhorros = array(
                'Banco' => ''

            );


            $formularioAbrirCuentaAhorros .= '</form>'

        ?>
    </body>
</html>