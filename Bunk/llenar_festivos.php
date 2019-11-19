<?php
    include_once dirname(__FILE__).'/config.php';
    $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
    if(mysqli_connect_errno()){
        echo "Error en la conexión: ".mysqli_conecct_error()."<br>";
    }
    
    $sql = "TRUNCATE TABLE DIAS_FESTIVOS;
            INSERT INTO DIAS_FESTIVOS (FECHA) VALUES ('2019-01-01');
            INSERT INTO DIAS_FESTIVOS (FECHA) VALUES ('2019-01-07');
            INSERT INTO DIAS_FESTIVOS (FECHA) VALUES ('2019-03-25');
            INSERT INTO DIAS_FESTIVOS (FECHA) VALUES ('2019-04-14');
            INSERT INTO DIAS_FESTIVOS (FECHA) VALUES ('2019-04-18');
            INSERT INTO DIAS_FESTIVOS (FECHA) VALUES ('2019-04-19');
            INSERT INTO DIAS_FESTIVOS (FECHA) VALUES ('2019-04-21');
            INSERT INTO DIAS_FESTIVOS (FECHA) VALUES ('2019-05-01');
            INSERT INTO DIAS_FESTIVOS (FECHA) VALUES ('2019-06-03');
            INSERT INTO DIAS_FESTIVOS (FECHA) VALUES ('2019-06-24');
            INSERT INTO DIAS_FESTIVOS (FECHA) VALUES ('2019-07-01');
            INSERT INTO DIAS_FESTIVOS (FECHA) VALUES ('2019-07-20');
            INSERT INTO DIAS_FESTIVOS (FECHA) VALUES ('2019-08-07');
            INSERT INTO DIAS_FESTIVOS (FECHA) VALUES ('2019-08-19');
            INSERT INTO DIAS_FESTIVOS (FECHA) VALUES ('2019-09-14');
            INSERT INTO DIAS_FESTIVOS (FECHA) VALUES ('2019-11-04');
            INSERT INTO DIAS_FESTIVOS (FECHA) VALUES ('2019-11-11');
            INSERT INTO DIAS_FESTIVOS (FECHA) VALUES ('2019-12-08');
            INSERT INTO DIAS_FESTIVOS (FECHA) VALUES ('2019-12-25')";
    if(mysqli_multi_query($con,$sql)){
        echo "Todo done<br>";
    }else{
        echo "Error en el query: ".mysqli_error($con)."<br>";
    }
?>