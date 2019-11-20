<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" href="css/bootstrap.min.css">
    </head>
    <body>
        <h1>Solicitar Credito</h1>

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
            

            $formularioSolicitarCredito = "";
            $formularioSolicitarCredito .= '<form action="abrir_cuenta_ahorros.php" method="post">';
            $formularioSolicitarCredito .= crearSelect('Banco', 'banco', $bancos);


            //ESTO SE DEBE EXTRAER DE LA SESIÓN
            $usuarioAutenticado = true;
            $formulario = "";
            if ($usuarioAutenticado){
                $formulario = array(
                    array('Tasa de interés propuesta', 'tasa_interes_propuesta', 'number', ''),
                    array('Fecha de pago', 'fecha_pago', 'date', ''),
                    array('Valor', 'valor', 'javecoin', ''),
                    array('Solicitar Crédito', 'solicitar_credito', 'submit', '')
                );
            }
            else{
                $formulario = array(
                    array('Cedula', 'cedula', 'number', ''),
                    array('E-Mail', 'email', 'email', ''),
                    array('Fecha de pago', 'fecha_pago', 'date', ''),
                    array('Valor', 'valor', 'javecoin', ''),
                    array('Solicitar Crédito', 'solicitar_credito', 'submit', '')
                );
            }

           

            $formularioSolicitarCredito .= crearFormulario2($formulario);
            $formularioSolicitarCredito .= '</form>';
            echo $formularioSolicitarCredito;

            if($_SERVER['REQUEST_METHOD'] == 'POST'){

                
                
                if (isset($_POST['solicitar_credito'])){
                    $banco_id = $_POST['banco'];
                    

                    $sql = "SELECT * FROM BANCOS WHERE id=$banco_id";
                    $resultado = mysqli_query($con , $sql);
                    $row = mysqli_fetch_array($resultado);

                    $interes_credito_visitantes = $row['interes_credito_visitantes'];
                    $nombre_banco = $row['nombre'];

                    //ESTO DEBERIA EXTRAERSE DE LA SESIÓN
                    $usuarioAutenticado = true;
                    $valor = $_POST['valor'];
                    $fecha_pago = $_POST['fecha_pago'];


                    
                    if($usuarioAutenticado){
                        //ESTO DEBERIA EXTRAERSE DE LA SESIÓN
                        $cliente_id = 1;
                        $cliente =         23;            

                        


                    }else{
                        
                        $cedula = $_POST['cedula'];
                        $email = $_POST['email'];

                        $sql = "SELECT * FROM VISITANTES WHERE Cedula = '$cedula'";
                        $resultado = mysqli_query($con, $sql);
                        if (mysqli_num_rows($resultado) > 0) {
                            $sql = "UPDATE * FROM VISITANTES 
                                    SET email='$email'
                                    WHERE Cedula = '$cedula'";
                        }
                        else{
                            $sql = "INSERT INTO VISITANTES (cedula, email)
                                    VALUES ($cedula, $email)";
                        }
                        mysqli_query($con, $sql);
                        $sql = "SELECT * FROM VISITANTES WHERE Cedula = '$cedula'";
                        $resultado = mysqli_query($con , $sql);
                        $row = mysqli_fetch_array($resultado);
                        $visitante_id = $row['id'];

                        $sql = "INSERT INTO CREDITOS (tasa_interes, fecha_pago, valor, visitante_id)
                                VALUES ($interes_credito_visitantes, $fecha_pago, $valor, $visitante_id)";




                        


                    }

                    $saldo_inial = 0;
                    //ESTO SE DEBE EXTRAER DE LA SESIÓN:
                    $cliente_id = 1;

                     

                    $sql = "INSERT INTO CREDITOS (saldo, cliente_id, cuota_manejo, banco_id) VALUES ($saldo_inial, $cliente_id, $cuota_manejo, $banco_id)";
                    if (mysqli_query($con, $sql)) {
                        echo "Se ha abierto una cuenta de ahorros en el banco $nombre_banco, la cuota de manejo es $cuota_manejo JaveCoins y la tasa de interes es $interes_cuenta_ahorros%.</br>";
                        echo "El saldo inicial de la cuenta es 0 JaveCoins";
                    } else {
                        echo 'Hubo un error al intentar abrir la cuenta de ahorros '.mysqli_error($con);
                    }
                }
            }

        ?>
    </body>
</html>