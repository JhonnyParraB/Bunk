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
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="monto">Monto: </label>
            <input type="number" name="monto">
            <label for="numeroCuenta">Número de Cuenta: </label>
            <input type="number" name="numeroCuenta">
            <input type="submit" value="Retirar" name="Retirar">
        </form>
    </form>
    <?php
    if(isset($_POST['Retirar']))
    {
        if(isset($_SESSION['Persona']))
        {
            $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
            if (mysqli_connect_errno())
            {
                echo "Error en la conexión: ". mysqli_connect_error();
            }
            $id = $_POST['numeroCuenta'];
            $resultado = mysqli_query($con,"SELECT * FROM Cuentas_Ahorro WHERE ID = $id");
            $cuenta = mysqli_fetch_array($resultado);
            $saldo = $cuenta['saldo'];
            $monto = $_POST['monto'];
            if($saldo < $monto)
            {
                echo "Fondos insuficientes";
            }
            else
            {
                date_default_timezone_set('America/Bogota');
                $fecha_solicitud = date('Y-m-d H:i:s', time());
                $saldo -= $monto;
                $sql = "UPDATE CUENTAS_AHORRO SET SALDO = $saldo where ID = $id";
                $retiro = "INSERT INTO retiros (monto, fecha, cuenta_ahorro_id, actor) values($monto,'$fecha_solicitud',$id,'CLIENTE')";
                if(mysqli_query($con,$sql) and mysqli_query($con,$retiro) and $_SESSION['Persona'] == $cuenta['cliente_id'])
                {
                    echo "RETIRADO";
                }
                else{
                    echo "error en el retiro";
                }
            }
        }else{
            header("Location: http://localhost/login_registro/login.php"); 
        }
    }
    
    ?> 
    </body>
</html>