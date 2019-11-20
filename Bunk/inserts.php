<?php
     include_once dirname(__FILE__).'/config.php';
     $con = mysqli_connect(HOST_DB, USUARIO_DB, USUARIO_PASS, NOMBRE_DB);
     if(mysqli_connect_errno()){
         echo "Error en la conexión: ".mysqli_conecct_error()."<br>";
     }
    $sql = "insert into Bancos (nombre,interes_credito_visitantes,interes_cuenta_ahorros,costo_transferencia,interes_mora,cuota_cuenta_ahorros)
            values ('BancoBogotá', 0.4, 0.2, 5000, 0.5, 11000);
            insert into Bancos (nombre,interes_credito_visitantes,interes_cuenta_ahorros,costo_transferencia,interes_mora,cuota_cuenta_ahorros)
            values ('Bancolombia', 0.3, 0.1, 4000, 0.6, 8000);
            insert into Bancos (nombre,interes_credito_visitantes,interes_cuenta_ahorros,costo_transferencia,interes_mora,cuota_cuenta_ahorros)
            values ('BBVA', 0.5, 0.4, 8000, 0.2, 6000);
            
            insert into Clientes (usuario,contrasena,email,nombre, apellido,cedula)
            values('Jhony','cliente1','jhony@gmail.com','Jhony','Parra',1234567);
            insert into Clientes (usuario,contrasena,email,nombre, apellido,cedula)
            values('Laura','cliente2','laura@gmail.com','Laura','Donado',1234537);
            insert into Clientes (usuario,contrasena,email,nombre, apellido,cedula)
            values('Kevin','cliente3','kevin@gmail.com','Kevin','Pelaez',1234527);
            
            insert into Administradores (usuario,contrasena)
            values('admin','admin');
            INSERT INTO CUENTAS_AHORRO (id, saldo, cliente_id, cuota_manejo, banco_id) VALUES ('1', '1000', '1', '1', '1'), ('2', '500', '3', '1', '2');
            INSERT INTO CREDITOS (id, tasa_interes, fecha_pago, valor, dias_mora, aprobado, pagado, cliente_id, visitante_id, fecha_aprobacion, fecha_solicitud, banco_id, mensaje) VALUES ('1', '1', '2019-11-20', '1000', '0', b'0', b'0', '1', NULL, NULL, '2019-11-18 00:00:00', '2', NULL);
            INSERT INTO TARJETAS_CREDITO (id, aprobada, cupo_maximo, cuota_manejo, tasa_interes, sobrecupo, cuenta_ahorro_id, fecha_aprobacion, fecha_solicitud, mensaje) VALUES ('1', b'0', NULL, NULL, NULL, NULL, '1', NULL, '2019-11-11 00:00:00', NULL);";
    if(mysqli_multi_query($con,$sql)){
        echo "Tablas creadas<br>";
    }else{
        echo "Error en la creación: ".mysqli_error($con)."<br>";
    }
?>