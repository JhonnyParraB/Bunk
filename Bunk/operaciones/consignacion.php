<?php
    session_start();
    include_once '../config.php';
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    </head>
    <body>
    <?php if(isset($_SESSION['Rol']) and $_SESSION['Rol'] == 'User'): ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <input type = "radio" name = "tipoConsignacion" value ="Credito">Credito <br>
            <input type = "radio" name = "tipoConsignacion" value ="Ahorros">Ahorros <br>
            <label for="numeroCuenta">Número de cuenta: </label>
            <input type = "number" name = "numeroCuenta">
            <label for="monto">Cantidad: </label>
            <input type = "number" name = "monto">
            <input type = "submit" value = "consignar" name = "consignar">
        </form>
    <?php else: ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="Cedula">Cedula: </label>
            <input type = "number" name = "Cedula"><br>
            <input type = "radio" name = "tipoConsignacion" value ="Credito">Credito <br>
            <input type = "radio" name = "tipoConsignacion" value ="Ahorros">Ahorros <br>
            <label for="numeroCuenta">Número de cuenta: </label>
            <input type = "number" name = "numeroCuenta">
            <label for="monto">Cantidad: </label>
            <input type = "number" name = "monto">
            <input type = "submit" value = "consignar" name = "consignar">
        </form>
    <?php endif; ?>
    </body>
    <?php 
    if(isset($_POST["consignar"]) )
    {
        $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
                if (mysqli_connect_errno())
                {
                    echo "Error en la conexión: ". mysqli_connect_error();
                }
        $numCuenta = $_POST["numeroCuenta"];
        $monto = $_POST["monto"];
        if(isset($_SESSION['Persona'])){
            $idCliente = $_SESSION['Persona'];
            $resultado = mysqli_query($con,"SELECT * FROM Clientes WHERE ID = $idCliente");
            $fila = mysqli_fetch_array($resultado);
            $cedula = $fila['cedula'];
        }else{
            $cedula = $_POST['Cedula'];
        }
            
        date_default_timezone_set('America/Bogota');
        $fecha_solicitud = date('Y-m-d H:i:s', time());
        if(isset($_SESSION['Rol']) and $_POST['tipoConsignacion'] == 'Ahorros'){
            $resultado = mysqli_query($con,"SELECT * FROM CUENTAS_AHORRO WHERE ID = $numCuenta");
            $fila = mysqli_fetch_array($resultado);
            $valor = $fila['saldo'] + $monto;
            $suma = "UPDATE CUENTAS_AHORRO SET SALDO = $valor WHERE ID = $numCuenta";
            $sql = "INSERT INTO Consignaciones (monto, cedula, fecha, destino_cuenta_ahorro_id, actor) values('$monto','$cedula','$fecha_solicitud','$numCuenta', 'CLIENTE')";
            if(mysqli_query($con,$suma) and mysqli_query($con,$sql)){
                echo "Consignado";
            }
            else
                echo "error" . mysqli_error($con);
        }elseif (isset($_SESSION['Rol']) and $_POST['tipoConsignacion'] == 'Credito') {
            $resultado = mysqli_query($con,"SELECT * FROM Creditos WHERE ID = $numCuenta");
            $fila = mysqli_fetch_array($resultado);
            $valor = $fila['valor'];
            if($valor == $monto)
            {
                if($fila['pagado']!= 1)
                {
                    $update = "UPDATE CREDITOS SET  pagado = 1, fecha_pago = '$fecha_solicitud', estado = 'Pagado' WHERE id = $numCuenta";
                    $sql = "INSERT INTO Consignaciones (monto, cedula, fecha, destino_credito_id, actor) values('$monto','$cedula','$fecha_solicitud','$numCuenta', 'VISITANTE')";
                    if(mysqli_query($con,$update) and mysqli_query($con,$sql))
                        echo "Pagado";
                    else
                        echo "error" . mysqli_error($con);
                }else {
                    echo "El credito ya fue pagado";
                }
            }else{
                echo "Debe pagar todo el crédito: ". $valor;
            }
        }elseif ($_POST['tipoConsignacion'] == 'Credito' and !isset($_SESSION['Rol'])) {
            $resultado = mysqli_query($con,"SELECT * FROM Creditos WHERE ID = $numCuenta");
            $fila = mysqli_fetch_array($resultado);
            $valor = $fila['valor'];
            if($valor == $monto)
            {
                if(!$fila['pagado'])
                {
                    $update = "UPDATE CREDITOS SET  pagado = 1, fecha_pago = '$fecha_solicitud', estado = 'Pagado' WHERE id = $numCuenta";
                    $sql = "INSERT INTO Consignaciones (monto, cedula, fecha, destino_credito_id, actor) values('$monto','$cedula','$fecha_solicitud','$numCuenta', 'VISITANTE')";
                    if(mysqli_query($con,$update) and mysqli_query($con,$sql))
                        echo "Pagado";
                    else
                        echo "error" . mysqli_error($con);
                }else {
                    echo "El credito ya fue pagado";
                }
                    
            }else{
                echo "Debe pagar todo el crédito: ". $valor;
            }
        }elseif ($_POST['tipoConsignacion'] == 'Ahorros' and !isset($_SESSION['Rol'])) {
            $resultado = mysqli_query($con,"SELECT * FROM CUENTAS_AHORRO WHERE ID = $numCuenta");
            $fila = mysqli_fetch_array($resultado);
            $valor = $fila['saldo'] + $monto;
            $suma = "UPDATE CUENTAS_AHORRO SET SALDO = $valor WHERE ID = $numCuenta";
            $sql = "INSERT INTO Consignaciones (monto, cedula, fecha, destino_cuenta_ahorro_id,actor) values('$monto','$cedula','$fecha_solicitud','$numCuenta', 'VISITANTE)";
            if(mysqli_query($con,$suma) and mysqli_query($con,$sql)){
                echo "Consignado";
            }
            else
                echo "error" . mysqli_error($con);
        }
    }
    ?>
</html>