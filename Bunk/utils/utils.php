<?php 
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';

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
    function validarEmail($data){
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
                if($tipo === 'javecoin'){
                    $linea.="<input type=number name='$key' ";
                }else{
                    $linea.="<input type='$tipo' name='$key' ";
                }
                if(isset($_POST["$key"])) 
                    $linea.= "value=".$_POST["$key"];
                if($tipo === 'javecoin'){
                    $linea.="> JaveCoins<br>";
                }else{
                    $linea.="><br>";
                }  
            }
        }
        $linea.='</div>';
        return $linea;
    }
    //label, name, type, value
    function crearFormulario2($datos){
        $linea = '<div>';
        for($row = 0; $row < count($datos); ++$row){
            if ($datos[$row][2] === 'submit')
			    $linea .= "<div><input type='".$datos[$row][2]."' name='".$datos[$row][1]."' value='".$datos[$row][0]."' ></div>";
		    else{
                $linea.="<label for='".$datos[$row][1]."'><b>".$datos[$row][0].":</b></label><br>";
                if($datos[$row][2] === 'javecoin'){
                    $linea.="<input type='number' name='".$datos[$row][1]."' ";
                }else{
                    $linea.="<input type='".$datos[$row][2]."' name='".$datos[$row][1]."' ";
                }
                $linea.= "value=".$datos[$row][3];
                if($datos[$row][2] === 'javecoin'){
                    $linea.="> JaveCoins<br>";
                }else{
                    $linea.="><br>";
                }
            }
        }
        $linea.='</div>';
        return $linea;
    }
    function crearFormulario3($myArray, $datos){
        $linea = '<div>';
        foreach($myArray as $key => $tipo){
            if ($tipo === 'submit')
			    $linea .= "<div><input type='$tipo' name='$key' value='$key' ></div>";
		    else{
                $linea.="<label for='$key'><b>$key:</b></label><br>";
                if($tipo === 'javecoin'){
                    $linea.="<input type=number name='$key' ";
                }else{
                    $linea.="<input type='$tipo' name='$key' ";
                }
                $linea.= "value=".$datos["$key"];
                if($tipo === 'javecoin'){
                    $linea.="> JaveCoins<br>";
                }else{
                    $linea.="><br>";
                } 
            }
        }
        $linea.='</div>';
        return $linea;
    }
    //Esta función crea un select con los valores y opciones de un arreglo
    function crearSelect($label, $nombreSelect, $opciones){
        $select = "<label for='$nombreSelect'><b>$label:</b></label><br>";
        $select .= "<select name=$nombreSelect>";
        foreach($opciones as $value => $text){
            $select .= "<option value=$value>$text</option>";
        }
        $select .="</select>";
        return $select;
    }

    function sendemail($to, $name, $last, $subject, $msg){
        $mail = new PHPMailer(true);
        try{
            $mail->isSMTP();
            $mail->CharSet = 'utf-8';
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->Username = "pruebadonado@gmail.com";
            $mail->Password = "Prueba.1234";
            $mail->setFrom('noreply@donado.com', 'No Reply');
            $mail->addAddress($to, $name.' '.$last);
            $mail->Subject = $subject;
            $mail->Body = $msg;
            $mail->send();
        }catch(Exception $e){
            echo $e->errorMessage();
        }
        catch (\Exception $e)
        {
        echo $e->getMessage();
        }
    }
?>