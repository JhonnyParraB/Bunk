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
    function crearFormulario2($datos){
        $linea = '<div>';
        for($row = 0; $row < count($datos); ++$row){
            if ($datos[$row][2] === 'submit')
			    $linea .= "<div><input type='".$datos[$row][2]."' name='".$datos[$row][1]."' value='".$datos[$row][0]."' ></div>";
		    else{
                $linea.="<label for='".$datos[$row][1]."'><b>".$datos[$row][0].":</b></label><br>";
                $linea.="<input type='".$datos[$row][2]."' name='".$datos[$row][1]."' ";
                $linea.= "value=".$datos[$row][3];
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