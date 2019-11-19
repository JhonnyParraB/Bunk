<?php 
    function validar($data) {
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    function validarNombres($data){
        if (!preg_match("/^[a-zA-Z ]*$/", $data)) {
            return false;
        }
        return true;
    }
    function validarNombres($data){
        if (!filter_var($data, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }
?>