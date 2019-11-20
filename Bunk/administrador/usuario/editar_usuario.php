<?php
    session_start();
    if (!isset($_SESSION['Rol']) || $_SESSION['Rol'] != 'Admin') {
        header('Location: ../login_registro/login.php');
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Editar Cliente</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    </head>
    <body>
        <form method="POST">
            <input type=submit name=salir value=Salir class='salirBtn'/>
        <h2>Editar Cliente</h2>
        <?php
            include_once '../../config.php';
            $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
            if(mysqli_connect_errno()){
                echo "Error en la conexión: ".mysqli_conecct_error()."<br>";
            }
            include "../../utils/utils.php";
            $cliente=$_SESSION['cliente'];
            $sql = "SELECT * from CLIENTES WHERE id = $cliente";
            $resultado = mysqli_query($con,$sql);
            $row = mysqli_fetch_array($resultado);
            $myArray = array(  
                        'Email' =>  'email',
                        'Nombre' => 'text',
                        'Apellido' => 'text',
                        'Cedula' =>  'number',
                        'Volver'=> 'submit',
                        'Guardar'=> 'submit'); 
            $datos = array(  
                        'Email' =>  $row['email'],
                        'Nombre' => $row['nombre'],
                        'Apellido' => $row['apellido'],
                        'Cedula' =>  $row['cedula']); 
            
            $linea=crearFormulario3($myArray, $datos);
            echo $linea;
            if (isset($_POST['Guardar'])) {
                $email = validar($_POST['Email']);
                $nombre = validar($_POST['Nombre']);
                $apellido = validar($_POST['Apellido']);
                $cedula = validar($_POST['Cedula']);
                $errores="";
                if(!validarEmail($email)){
                    $errores.= "Email: Solo se permiten números.<br>";
                }
                if(!validarNombres($nombre)){
                    $errores.= "Nombre: Solo se permiten letras y espacios.<br>";
                }
                if(!validarNombres($apellido)){
                    $errores.= "Apellido: Solo se permiten letras y espacios.<br>";
                }
                if(!is_numeric($cedula)){
                    $errores.= "Cédula: Solo se permiten números.<br>";
                }
                if($errores!=""){
                    echo $errores;
                }else{
                    $sql = "UPDATE Clientes 
                            SET email='$email',
                                nombre= '$nombre',
                                apellido='$apellido',
                                cedula=$cedula
                            WHERE id = $cliente";
                    if(mysqli_query($con,$sql)){
                        header('Location: listar_usuarios.php');
                    }else{
                        echo "Error: ".mysqli_error($con)."<br>";
                    }
                }
            }
            if (isset($_POST['Volver'])) {
                header('Location: listar_usuarios.php');
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