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
    $sql = "
            
            CREATE TABLE IF NOT EXISTS `Bunk`.`BANCOS` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `nombre` VARCHAR(100) NOT NULL,
                `interes_credito_visitantes` DECIMAL(4,2) NOT NULL,
                `interes_cuenta_ahorros` DECIMAL(4,2) NOT NULL,
                `costo_transferencia` DECIMAL(12,2) NOT NULL,
                `interes_mora` DECIMAL(4,2) NOT NULL,
                `cuota_cuenta_ahorros` DECIMAL(12,2) NOT NULL,
                PRIMARY KEY (`id`),
                UNIQUE INDEX `nombre_UNIQUE` (`nombre` ASC) );
            
            
            CREATE TABLE IF NOT EXISTS `Bunk`.`VISITANTES` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `cedula` VARCHAR(45) NOT NULL,
                `email` VARCHAR(45) NOT NULL,
                PRIMARY KEY (`id`),
                UNIQUE INDEX `cedula_UNIQUE` (`cedula` ASC) );
            
            
            
            
            CREATE TABLE IF NOT EXISTS `Bunk`.`CLIENTES` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `usuario` VARCHAR(100) NOT NULL,
                `contrasena` VARCHAR(100) NOT NULL,
                `email` VARCHAR(100) NOT NULL,
                `nombre` VARCHAR(100) NOT NULL,
                `apellido` VARCHAR(100) NOT NULL,
                `cedula` INT NOT NULL,
                PRIMARY KEY (`id`),
                UNIQUE INDEX `usuario_UNIQUE` (`usuario` ASC)  );
            
            
            
            
            CREATE TABLE IF NOT EXISTS `Bunk`.`CREDITOS` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `tasa_interes` DECIMAL(4,2) NOT NULL,
                `fecha_pago` DATE NOT NULL,
                `valor` DECIMAL(12,2) NOT NULL,
                `dias_mora` INT NOT NULL DEFAULT 0,
                `aprobado` BIT NOT NULL DEFAULT 0,
                `pagado` BIT NOT NULL DEFAULT 0,
                `cliente_id` INT NULL,
                `visitante_id` INT NULL,
                `banco_id` INT NOT NULL,
                `fecha_aprobacion` DATETIME NULL,
                `fecha_solicitud` DATETIME NULL,
                `BANCOS_id` INT NOT NULL,
                PRIMARY KEY (`id`),
                INDEX `fk_CREDITOS_VISITANTES1_idx` (`cliente_id` ASC)  ,
                INDEX `fk_CREDITOS_CLIENTES1_idx` (`visitante_id` ASC)  ,
                INDEX `fk_CREDITOS_BANCOS1_idx` (`banco_id` ASC)  ,
                INDEX `fk_CREDITOS_BANCOS2_idx` (`BANCOS_id` ASC)  ,
                CONSTRAINT `fk_CREDITOS_VISITANTES1`
                FOREIGN KEY (`cliente_id`)
                REFERENCES `Bunk`.`VISITANTES` (`id`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION,
                CONSTRAINT `fk_CREDITOS_CLIENTES1`
                FOREIGN KEY (`visitante_id`)
                REFERENCES `Bunk`.`CLIENTES` (`id`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION,
                CONSTRAINT `fk_CREDITOS_BANCOS1`
                FOREIGN KEY (`banco_id`)
                REFERENCES `Bunk`.`BANCOS` (`id`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION,
                CONSTRAINT `fk_CREDITOS_BANCOS2`
                FOREIGN KEY (`BANCOS_id`)
                REFERENCES `Bunk`.`BANCOS` (`id`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION);
            
            
            
            
            CREATE TABLE IF NOT EXISTS `Bunk`.`ADMINISTRADORES` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `usuario` VARCHAR(100) NOT NULL,
                `contrasena` VARCHAR(100) NOT NULL,
                PRIMARY KEY (`id`),
                UNIQUE INDEX `usuario_UNIQUE` (`usuario` ASC)  );
            
            
            
            
            CREATE TABLE IF NOT EXISTS `Bunk`.`CUENTAS_AHORRO` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `saldo` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
                `cliente_id` INT NOT NULL,
                `cuota_manejo` DECIMAL(12,2) NULL,
                `id_banco` INT NOT NULL,
                PRIMARY KEY (`id`),
                INDEX `fk_CUENTAS_AHORRO_CLIENTES1_idx` (`cliente_id` ASC)  ,
                INDEX `fk_CUENTAS_AHORRO_BANCOS1_idx` (`id_banco` ASC)  ,
                CONSTRAINT `fk_CUENTAS_AHORRO_CLIENTES1`
                FOREIGN KEY (`cliente_id`)
                REFERENCES `Bunk`.`CLIENTES` (`id`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION,
                CONSTRAINT `fk_CUENTAS_AHORRO_BANCOS1`
                FOREIGN KEY (`id_banco`)
                REFERENCES `Bunk`.`BANCOS` (`id`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION);
            
            
            
            
            CREATE TABLE IF NOT EXISTS `Bunk`.`TARJETAS_CREDITO` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `aprobada` BIT NOT NULL DEFAULT 0,
                `cupo_maximo` DECIMAL(12,2) NULL,
                `cuota_manejo` DECIMAL(12,2) NULL,
                `tasa_interes` DECIMAL(4,2) NULL,
                `sobrecupo` DECIMAL(12,2) NULL,
                `cuenta_ahorro_id` INT NOT NULL,
                `fecha_aprobacion` DATETIME NULL,
                `fecha_solicitud` DATETIME NULL,
                PRIMARY KEY (`id`),
                INDEX `fk_TARJETAS_CREDITO_CUENTAS_AHORRO_idx` (`cuenta_ahorro_id` ASC)  ,
                CONSTRAINT `fk_TARJETAS_CREDITO_CUENTAS_AHORRO`
                FOREIGN KEY (`cuenta_ahorro_id`)
                REFERENCES `Bunk`.`CUENTAS_AHORRO` (`id`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION);
            
            
            
            
            CREATE TABLE IF NOT EXISTS `Bunk`.`COMPRA` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `cuotas` INT NOT NULL,
                `valor` DECIMAL(12,2) NOT NULL,
                `cuotas_restantes` INT NOT NULL,
                `pagada` BIT NOT NULL DEFAULT 0,
                `fecha` DATE NOT NULL,
                `tarjeta_credito_id` INT NOT NULL,
                PRIMARY KEY (`id`),
                INDEX `fk_COMPRA_TARJETAS_CREDITO1_idx` (`tarjeta_credito_id` ASC)  ,
                CONSTRAINT `fk_COMPRA_TARJETAS_CREDITO1`
                FOREIGN KEY (`tarjeta_credito_id`)
                REFERENCES `Bunk`.`TARJETAS_CREDITO` (`id`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION);
            
            
            
            
            CREATE TABLE IF NOT EXISTS `Bunk`.`TRANSFERENCIAS` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `valor` DECIMAL(12,2) NOT NULL,
                `destino_cuenta_ahorro_id` INT NULL,
                `origen_cuenta_ahorro_id` INT NOT NULL,
                `destino_credito_id` INT NULL,
                `fecha` DATE NOT NULL,
                PRIMARY KEY (`id`),
                INDEX `fk_TRANSFERENCIAS_CUENTAS_AHORRO1_idx` (`destino_cuenta_ahorro_id` ASC)  ,
                INDEX `fk_TRANSFERENCIAS_CUENTAS_AHORRO2_idx` (`origen_cuenta_ahorro_id` ASC)  ,
                INDEX `fk_TRANSFERENCIAS_CREDITOS1_idx` (`destino_credito_id` ASC)  ,
                CONSTRAINT `fk_TRANSFERENCIAS_CUENTAS_AHORRO1`
                FOREIGN KEY (`destino_cuenta_ahorro_id`)
                REFERENCES `Bunk`.`CUENTAS_AHORRO` (`id`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION,
                CONSTRAINT `fk_TRANSFERENCIAS_CUENTAS_AHORRO2`
                FOREIGN KEY (`origen_cuenta_ahorro_id`)
                REFERENCES `Bunk`.`CUENTAS_AHORRO` (`id`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION,
                CONSTRAINT `fk_TRANSFERENCIAS_CREDITOS1`
                FOREIGN KEY (`destino_credito_id`)
                REFERENCES `Bunk`.`CREDITOS` (`id`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION);
            
            
            
            
            CREATE TABLE IF NOT EXISTS `Bunk`.`DIAS_FESTIVOS` (
                `FECHA` DATE NOT NULL COMMENT 'Solo hay que comparar los días y los meses (se toman solo las fechas especiales de 2019)',
                PRIMARY KEY (`FECHA`),
                UNIQUE INDEX `FECHA_UNIQUE` (`FECHA` ASC)  );  
  
            ";
    if(mysqli_multi_query($con,$sql)){
        echo "Tablas creadas<br>";
        header("Location: llenar_festivos.php");
    }else{
        echo "Error en la creación: ".mysqli_error($con)."<br>";
    }
?>