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
    function validarMail($data){
        if (!filter_var($data, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }
    function crearFormulario($myArray){
        $linea="";
        foreach($myArray as $key => $tipo){
            if ($tipo === 'submit')
			    $linea .= "<div><input type=$tipo name=$key value=$key ></div>";
		    else{
                $linea.="<label for=$key><b>$key:</b></label>";
                $linea.="<input type=$tipo name=$key ";
                if(isset($_POST["$key"])) 
                    $linea.= "value=".$_POST["$key"];
                $linea.=">";
            }
        }
        return $linea;
    }
?>