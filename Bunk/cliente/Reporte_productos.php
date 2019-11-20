<?php 
    session_start();
    include_once '../config.php';
    if (isset($_SESSION['Rol']) ) {
        $id = $_SESSION['Persona'];
    }
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Reporte Producto</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    </head>
    <body>
        <?php 
        $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
        if (mysqli_connect_errno())
        {
            echo "Error en la conexión: " . mysqli_connect_error();
        }
        $query = "SELECT * FROM cuentas_ahorro"; 
        $result = mysqli_query($con, $query);
        $cuentas = array();
        $id = $_SESSION['Persona'];
        while($row = mysqli_fetch_array($result)){
            if($row['cliente_id'] == $id)
                array_push($cuentas, $row['id']);
        }
        $query = "SELECT * FROM transferencias"; 
        $result = mysqli_query($con, $query);
        echo "<table>"; 
        echo "<tr><td>" . 'Id' . "</td><td>" . 'Valor' . "</td><td>" .  'Cuenta de Ahorro Destino' . "</td><td>" .  'Cuenta de Ahorro Origen' . "</td><td>" .  'Credito Destino' . "</td><td>".  'Fecha' . "</td></tr>";  
        while($row = mysqli_fetch_array($result)){ 
            foreach($cuentas as $cuenta)
            {
                if($row['origen_cuenta_ahorro_id'] == $cuenta)
                {
                    echo "<tr><td>" . $row['id'] . "</td><td>" . $row['valor'] . "</td><td>" .  $row['destio_cuenta_ahorro_id'] . "</td><td>" .  $row['origen_cuenta_ahorro_id'] . "</td><td>" .  $row['destio_credito_id'] . "</td><td>".  $row['fecha'] . "</td></tr>";  
                }
            }   
        }

        echo '</table>'; 
        
        ?>
    </body>
</html>