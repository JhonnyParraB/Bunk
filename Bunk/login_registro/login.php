<?php
    session_start();
    include_once '../config.php';
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>Login</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    </head>
    <body>

    <div class="sidenav">
        <div class="login-main-text">
            <h2>Bunk<br> Iniciar Sesión</h2>
            <p>Inicia sesión o registrate para ingresar.</p>
        </div>
    </div>
    <div class="main">
        <div class="col-md-6 col-sm-12">
            <div class="login-form">
               <form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "POST">
                  <div class="form-group">
                     <label>User Name</label>
                     <input type="text" class="form-control" name="User">
                  </div>
                  <div class="form-group">
                     <label>Password</label>
                     <input type="password" class="form-control" name="Password">
                  </div>
                  <button type="submit" class="btn btn-black" name = "login" >Login</button>
                  <button type="submit" class="btn btn-secondary" name = "register">Register</button>
               </form>
            </div>
        </div>
    </div>
    <?php
        if(isset($_POST['User']) and isset($_POST['Password']) and isset($_POST['login'])){
            $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
            if (mysqli_connect_errno())
            {
                echo "Error en la conexión: ". mysqli_connect_error();
            }
            $sql = "SELECT * FROM Administradores";
            $resultado = mysqli_query($con,$sql);
            while($fila = mysqli_fetch_array($resultado))
            {
                if($fila['usuario'] == $_POST["User"] and ($fila['contrasena'] == $_POST["Password"] or $fila['contrasena'] == crypt($_POST["Password"],'banco')) )
                {
                    $_SESSION['Persona'] = $fila['id'];
                    $_SESSION['Rol'] = 'Admin';
                    header("Location: http://localhost/administrador/home_admin.php"); 
                }
            }
            $sql = "SELECT * FROM Clientes";
            $resultado = mysqli_query($con,$sql);
            while($fila = mysqli_fetch_array($resultado))
            {
                if($fila["usuario"] == $_POST["User"] && $fila["contrasena"] == $_POST["Password"])
                {
                    $_SESSION['Persona'] = $fila['id'];
                    $_SESSION['Rol'] = 'User';
                    header("Location: http://localhost/cliente/home_cliente.php"); 
                }
            }
        }
        if(isset($_POST['register']))
        {
            header("Location: http://localhost/login_registro/registro.php"); 
        }
        
    ?>

    
    </body>
</html>