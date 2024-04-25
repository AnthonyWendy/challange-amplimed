
CREATE SCHEMA IF NOT EXISTS `MedicalChallenge` DEFAULT CHARACTER SET utf8 ;

DROP TABLE IF EXISTS `MedicalChallenge`.`convenios` ;

CREATE TABLE IF NOT EXISTS `MedicalChallenge`.`convenios` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NOT NULL,
  `descricao` VARCHAR(255) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

DROP TABLE IF EXISTS `MedicalChallenge`.`pacientes` ;

CREATE TABLE IF NOT EXISTS `MedicalChallenge`.`pacientes` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(255) NOT NULL,
  `sexo` ENUM('Masculino', 'Feminino') NOT NULL,
  `nascimento` DATE NOT NULL,
  `cpf` VARCHAR(14) NULL,
  `rg` VARCHAR(20) NULL,
  `id_convenio` INT NULL,
  `cod_referencia` VARCHAR(50) NULL,
  PRIMARY KEY (`id`),
  INDEX `paciente_id_convenio_idx` (`id_convenio` ASC) VISIBLE,
  CONSTRAINT `paciente_id_convenio`
    FOREIGN KEY (`id_convenio`)
    REFERENCES `MedicalChallenge`.`convenios` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

DROP TABLE IF EXISTS `MedicalChallenge`.`procedimentos` ;

CREATE TABLE IF NOT EXISTS `MedicalChallenge`.`procedimentos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NOT NULL,
  `descricao` VARCHAR(255) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

DROP TABLE IF EXISTS `MedicalChallenge`.`profissionais` ;

CREATE TABLE IF NOT EXISTS `MedicalChallenge`.`profissionais` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(255) NOT NULL,
  `crm` VARCHAR(20) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

DROP TABLE IF EXISTS `MedicalChallenge`.`agendamentos` ;

CREATE TABLE IF NOT EXISTS `MedicalChallenge`.`agendamentos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_paciente` INT NULL,
  `id_profissional` INT NOT NULL,
  `dh_inicio` DATETIME NOT NULL,
  `dh_fim` DATETIME NOT NULL,
  `id_convenio` INT NULL,
  `id_procedimento` INT NULL,
  `observacoes` TEXT NULL,
  PRIMARY KEY (`id`),
  INDEX `agendamento_id_convenio_idx` (`id_convenio` ASC) VISIBLE,
  INDEX `agendamento_id_procedimento_idx` (`id_procedimento` ASC) VISIBLE,
  INDEX `agendamento_id_profissional_idx` (`id_profissional` ASC) VISIBLE,
  INDEX `agendamento_id_paciente_idx` (`id_paciente` ASC) VISIBLE,
  CONSTRAINT `agendamento_id_convenio`
    FOREIGN KEY (`id_convenio`)
    REFERENCES `MedicalChallenge`.`convenios` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `agendamento_id_procedimento`
    FOREIGN KEY (`id_procedimento`)
    REFERENCES `MedicalChallenge`.`procedimentos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `agendamento_id_profissional`
    FOREIGN KEY (`id_profissional`)
    REFERENCES `MedicalChallenge`.`profissionais` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `agendamento_id_paciente`
    FOREIGN KEY (`id_paciente`)
    REFERENCES `MedicalChallenge`.`pacientes` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

START TRANSACTION;
USE `MedicalChallenge`;
INSERT INTO `MedicalChallenge`.`convenios` (`id`, `nome`, `descricao`) VALUES (1, 'Particular', 'Convênio Particular (Padrão)');
INSERT INTO `MedicalChallenge`.`convenios` (`id`, `nome`, `descricao`) VALUES (2, 'DevMed', 'Convênio da Empresa Dev');
INSERT INTO `MedicalChallenge`.`convenios` (`id`, `nome`, `descricao`) VALUES (4, 'MigraMed', 'Convênio dos Funcionário de Migração da Empresa Dev');

COMMIT;

START TRANSACTION;
USE `MedicalChallenge`;
INSERT INTO `MedicalChallenge`.`pacientes` (`id`, `nome`, `sexo`, `nascimento`, `cpf`, `rg`, `id_convenio`, `cod_referencia`) VALUES (1, 'Paciente de Testes', DEFAULT, '1989-05-12', '000.000.000-00', '00000-0', 1, NULL);
INSERT INTO `MedicalChallenge`.`pacientes` (`id`, `nome`, `sexo`, `nascimento`, `cpf`, `rg`, `id_convenio`, `cod_referencia`) VALUES (10272, 'Fulano de Tal', DEFAULT, '1974-06-19', '111.111.111-22', '11111-2', 1, NULL);
INSERT INTO `MedicalChallenge`.`pacientes` (`id`, `nome`, `sexo`, `nascimento`, `cpf`, `rg`, `id_convenio`, `cod_referencia`) VALUES (10276, 'Ciclano de Tal', DEFAULT, '2001-12-25', '222.222.222-33', '22222-3', 4, NULL);

COMMIT;

START TRANSACTION;
USE `MedicalChallenge`;
INSERT INTO `MedicalChallenge`.`procedimentos` (`id`, `nome`, `descricao`) VALUES (1, 'Consulta', 'Procedimento Padrão da Clínica');
INSERT INTO `MedicalChallenge`.`procedimentos` (`id`, `nome`, `descricao`) VALUES (2, 'Retorno', 'Pacientes em Caráter de Retorno');
INSERT INTO `MedicalChallenge`.`procedimentos` (`id`, `nome`, `descricao`) VALUES (3, 'Acompanhamento', 'Consulta de Acompanhamento');

COMMIT;

START TRANSACTION;
USE `MedicalChallenge`;
INSERT INTO `MedicalChallenge`.`profissionais` (`id`, `nome`, `crm`) VALUES (85217, 'Dr. Lucas KNE', NULL);
INSERT INTO `MedicalChallenge`.`profissionais` (`id`, `nome`, `crm`) VALUES (85218, 'Dr. Analista Pietro', NULL);
INSERT INTO `MedicalChallenge`.`profissionais` (`id`, `nome`, `crm`) VALUES (85219, 'Suporte MedicalChallenge', NULL);

COMMIT;

START TRANSACTION;
USE `MedicalChallenge`;
INSERT INTO `MedicalChallenge`.`agendamentos` (`id`, `id_paciente`, `id_profissional`, `dh_inicio`, `dh_fim`, `id_convenio`, `id_procedimento`, `observacoes`) VALUES (1, 1, 85217, '2021-05-12 11:15:00', '2021-05-12 11:30:00', 1, 1, 'Primeira consulta do paciente.');
INSERT INTO `MedicalChallenge`.`agendamentos` (`id`, `id_paciente`, `id_profissional`, `dh_inicio`, `dh_fim`, `id_convenio`, `id_procedimento`, `observacoes`) VALUES (2, 1, 85217, '2021-05-14 08:00:00', '2021-05-14 08:30:00', 1, 2, 'Retorno do paciente.');
INSERT INTO `MedicalChallenge`.`agendamentos` (`id`, `id_paciente`, `id_profissional`, `dh_inicio`, `dh_fim`, `id_convenio`, `id_procedimento`, `observacoes`) VALUES (3, 10276, 85218, '2021-06-01 14:30:00', '2021-06-01 14:45:00', 4, 3, 'Acompanhamento de rotina.');

COMMIT;