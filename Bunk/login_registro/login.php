<!DOCTYPE HTML>
<?php
    include_once dirname(__FILE__).'/config.php';
?>

<html>
    <head>
        <title>Admin</title>
        <link rel="stylesheet" href="css/bootstrap.min.css">
    </head>
    <body>

    <div class="sidenav">
        <div class="login-main-text">
            <h2>Application<br> Login Page</h2>
            <p>Login or register from here to access.</p>
        </div>
    </div>
    <div class="main">
        <div class="col-md-6 col-sm-12">
            <div class="login-form">
               <form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "POST">
                  <div class="form-group">
                     <label>User Name</label>
                     <input type="text" class="form-control" name="User Name">
                  </div>
                  <div class="form-group">
                     <label>Password</label>
                     <input type="password" class="form-control" name="Password">
                  </div>
                  <button type="submit" class="btn btn-black">Login</button>
                  <button type="submit" class="btn btn-secondary">Register</button>
               </form>
            </div>
        </div>
    </div>
    <?php
    function log(){
        $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NAME_DB);
        if (mysqli_connect_errno())
        {
            echo "Error en la conexión: ". mysqli_connect_error();
        }else{
            echo "exito!";
        }
        $sql = "SELECT * FROM Administradores";
        $resultado = mysqli_query($con,$sql);
        while($fila = mysqli_fetch_array($resultado))
        {
            if($fila[usuario] == $_POST[User Name] && $fila[contrasena] == $_POST[Password])
            {
                header("Location: http://localhost/Bunk/Bunk/administrador/home_admin.php"); 
            }
        }
        $sql = "SELECT * FROM Clientes";
        $resultado = mysqli_query($con,$sql);
        while($fila = mysqli_fetch_array($resultado))
        {
            if($fila[usuario] == $_POST[User Name] && $fila[contrasena] == $_POST[Password])
            {
                header("Location: http://localhost/Bunk/Bunk/cliente/home_cliente.php"); 
            }
        }
    }
        
    ?>

    
    </body>
</html>