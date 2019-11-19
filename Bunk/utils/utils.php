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
        $linea = '<div>';
        foreach($myArray as $key => $tipo){
            if ($tipo === 'submit')
			    $linea .= "<div><input type='$tipo' name='$key' value='$key' ></div>";
		    else{
                $linea.="<label for='$key'><b>$key:</b></label><br>";
                $linea.="<input type='$tipo' name='$key' ";
                if(isset($_POST["$key"])) 
                    $linea.= "value=".$_POST["$key"];
                $linea.="><br>";
            }
        }
        $linea.='</div>';
        return $linea;
    }
    //Esta función crea un select con los valores y opciones de un arreglo
    function crearSelect($opciones, $nombreSelect){
        $select = "";
        $select .= "<select name=$nombreSelect>";
        foreach($opciones as $value => $text){
            $select .= "<option value=$value>$text</option>";
        }
        $select .="</select>";
        return $select;
    }
?>