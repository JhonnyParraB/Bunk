<?php
    include_once dirname(__FILE__).'/config.php';
    $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS);
    $sql = "CREATE DATABASE Bunk";
    if(mysqli_query($con,$sql)){
        echo "Base de datos Bunk creada<br>";
    }else{
        echo "Error en la creación: ".mysqli_error($con)."<br>";
    }
    mysqli_select_db($con, NOMBRE_DB);
    if(mysqli_connect_errno()){
        echo "Error en la conexión: ".mysqli_conecct_error()."<br>";
    }
    $sql = "";// poner query tablas 
    if(mysqli_query($con,$sql)){
        echo "Tabla Personas creada<br>";
    }else{
        echo "Error en la creación: ".mysqli_error($con)."<br>";
    }
?>