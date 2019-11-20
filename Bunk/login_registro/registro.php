<?php
    include_once '../config.php';
?>
<!DOCTYPE HTML>
<html>
    <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Registro</title>
        </head>
        <body>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        
            <h1>Registro</h1>
            <label for="name">Nombre de usuario:</label>
            <input type="text" id="name" name="usuario">
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password"><br>

            <label>Tipo de usuario:</label>
            <input type="submit" id="Usuario" value="Usuario" name="tipoUs">
            <input type="submit" id="Administrador" value="Registrar Administrador" name="tipoAd">
        </form>
            <?php if(isset($_POST['tipoUs']) ): ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">  
                <label for="mail">Email:</label>
                <input type="email" id="mail" name="email">

                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre">

                <label for="apellido">Apellido:</label>
                <input type="text"  name="apellido">

                <label for="cedula">Cedula:</label>
                <input type="number" name="cedula">
                <input type = "submit" value = "Registro" name="registrar">
            </form>
            <?php elseif(isset($_POST["tipoAd"])): 
                $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
                if (mysqli_connect_errno())
                {
                    echo "Error en la conexión: ". mysqli_connect_error();
                }
                $usuario = $_POST["usuario"];
                if (CRYPT_SHA512 == 1)
                {
                    $contrasena = crypt($_POST["password"],'banco');
                } 
                $sql = "insert into Administradores (usuario, contrasena) values('$usuario','$contrasena')";
                if(mysqli_query($con,$sql))
                {
                    echo "Registrado Correctamente";
                    header("Location: http://localhost/login_registro/login.php"); 
                }
                else{
                    echo "error: " . mysqli_error($con);
                }
                ?> 
            <?php endif; 
            if(isset($_POST["registrar"]))
            {
                $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
                if (mysqli_connect_errno())
                {
                    echo "Error en la conexión: ". mysqli_connect_error();
                }
                $usuario = $_POST["usuario"];
                if (CRYPT_SHA512 == 1)
                {
                    $contrasena = crypt($_POST["password"],'banco');
                }
                $email = $_POST["email"];
                $nombre = $_POST["nombre"];
                $apellido = $_POST["apellido"];
                $cedula = $_POST["cedula"];
                $sql = "insert into Clientes (usuario,contrasena,email,nombre, apellido,cedula) values('$usuario','$contrasena','$email','$nombre','$apellido',$cedula)";
                if(mysqli_query($con,$sql))
                {
                    echo "Registrado Correctamente";
                    header("Location: http://localhost/login_registro/login.php"); 
                }
            }
            ?> 
        
        
    </body>
</html>