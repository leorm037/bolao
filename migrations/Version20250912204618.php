<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250912204618 extends AbstractMigration
{

    public function getDescription(): string {
        return '
            Usuário,
            Arquivo,
            Messenger,
            Rememberme,
            Reset Password,
            Apostador,
            Loteria,
            Loteria Rateio'
        ;
    }

    public function up(Schema $schema): void {
        $this->addSql($this->usuario());
        $this->addSql($this->messenger());
        $this->addSql($this->rememberme());
        $this->addSql($this->resetPassword());
        $this->addSql($this->apostador());
        $this->addSql($this->loteria());
        $this->addSql($this->loteriaRateio());
    }

    public function down(Schema $schema): void {
        // this down() migration is auto-generated, please modify it to your needs
    }

    private function usuario(): string {
        return "CREATE TABLE IF NOT EXISTS `bolao`.`usuario` (
                    `id` INT NOT NULL AUTO_INCREMENT,
                    `nome` VARCHAR(60) NOT NULL,
                    `email` VARCHAR(180) NOT NULL,
                    `roles` LONGTEXT NOT NULL DEFAULT '[\"ROLE_USER\",\"ROLE_ADMIN\"]',
                    `password` VARCHAR(255) NOT NULL,
                    `is_verified` TINYINT(1) NOT NULL,
                    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
                    `updated_at` TIMESTAMP NULL,
                    PRIMARY KEY (`id`))
                ENGINE = InnoDB";
    }
    
    private function arquivo(): string {
        return "CREATE TABLE IF NOT EXISTS `bolao`.`arquivo` (
                    `id` INT NOT NULL AUTO_INCREMENT,
                    `uuid` BINARY(16) NOT NULL,
                    `usuario_id` INT NOT NULL,
                    `nome_original` VARCHAR(255) NOT NULL,
                    `nome` VARCHAR(255) NOT NULL,
                    `caminho` VARCHAR(255) NOT NULL,
                    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
                    `updated_at` TIMESTAMP NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE INDEX `uuid_UNIQUE` (`uuid` ASC),
                    INDEX `fk_arquivo_usuario_idx` (`usuario_id` ASC),
                    CONSTRAINT `fk_arquivo_usuario`
                      FOREIGN KEY (`usuario_id`)
                      REFERENCES `bolao`.`usuario` (`id`)
                      ON DELETE NO ACTION
                      ON UPDATE NO ACTION)
                ENGINE = InnoDB";
    }
    
    private function messenger(): string {
        return "CREATE TABLE IF NOT EXISTS `bolao`.`messenger_messages` (
                    `id` BIGINT NOT NULL AUTO_INCREMENT,
                    `body` LONGTEXT NOT NULL,
                    `headers` LONGTEXT NOT NULL,
                    `queue_name` VARCHAR(190) NOT NULL,
                    `created_at` DATETIME NOT NULL,
                    `available_at` DATETIME NOT NULL,
                    `delivered_at` DATETIME NULL DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    INDEX `queue_name_INDEX` (`queue_name` ASC),
                    INDEX `availabled_at_INDEX` (`available_at` ASC),
                    INDEX `delivered_at_INDEX` (`delivered_at` ASC))
                ENGINE = InnoDB";
    }
    
    private function rememberme(): string {
        return "CREATE TABLE IF NOT EXISTS `bolao`.`rememberme_token` (
                    `series` VARCHAR(88) NOT NULL,
                    `value` VARCHAR(88) NOT NULL,
                    `lastUsed` DATETIME NOT NULL,
                    `class` VARCHAR(100) NOT NULL,
                    `username` VARCHAR(200) NOT NULL,
                    PRIMARY KEY (`series`))
                ENGINE = InnoDB";
    }
    
    private function resetPassword(): string {
        return "CREATE TABLE IF NOT EXISTS `bolao`.`reset_password_request` (
                    `id` INT NOT NULL AUTO_INCREMENT,
                    `user_id` INT NOT NULL,
                    `selector` VARCHAR(20) NOT NULL,
                    `hashed_token` VARCHAR(100) NOT NULL,
                    `requested_at` DATETIME NOT NULL,
                    `expires_at` DATETIME NOT NULL,
                    PRIMARY KEY (`id`),
                    INDEX `fk_reset_password_request_usuario1_idx` (`user_id` ASC),
                    CONSTRAINT `fk_reset_password_request_usuario1`
                      FOREIGN KEY (`user_id`)
                      REFERENCES `bolao`.`usuario` (`id`)
                      ON DELETE NO ACTION
                      ON UPDATE NO ACTION)
                ENGINE = InnoDB";
    }
    
    private function apostador(): string {
        return "CREATE TABLE IF NOT EXISTS `bolao`.`apostador` (
                    `id` INT NOT NULL AUTO_INCREMENT,
                    `uuid` BINARY(16) NOT NULL,
                    `usuario_id` INT NOT NULL,
                    `nome` VARCHAR(60) NOT NULL,
                    `email` VARCHAR(180) NULL,
                    `pix` VARCHAR(180) NULL,
                    `telefone` VARCHAR(20) NULL,
                    `celular` VARCHAR(20) NULL,
                    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
                    `updated_at` TIMESTAMP NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE INDEX `usuario_id_nome_UNIQUE` (`nome` ASC, `usuario_id` ASC),
                    INDEX `fk_apostador_usuario_idx` (`usuario_id` ASC),
                    FULLTEXT INDEX `nome_FULLTEXT` (`nome`),
                    CONSTRAINT `fk_apostador_usuario`
                      FOREIGN KEY (`usuario_id`)
                      REFERENCES `bolao`.`usuario` (`id`)
                      ON DELETE NO ACTION
                      ON UPDATE NO ACTION)
                ENGINE = InnoDB";
    }
    
    private function loteria(): string {
        return "CREATE TABLE IF NOT EXISTS `bolao`.`loteria` (
                    `id` INT NOT NULL,
                    `uuid` BINARY(16) NOT NULL,
                    `nome` VARCHAR(60) NOT NULL,
                    `url_slug` VARCHAR(60) NOT NULL,
                    `url_api` VARCHAR(2048) NOT NULL,
                    `dezenas` LONGTEXT NOT NULL,
                    `apostas` LONGTEXT NOT NULL,
                    `premios` LONGTEXT NOT NULL,
                    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
                    `updated_at` TIMESTAMP NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE INDEX `nome_UNIQUE` (`nome` ASC),
                    UNIQUE INDEX `url_slug_UNIQUE` (`url_slug` ASC),
                    UNIQUE INDEX `url_api_UNIQUE` (`url_api` ASC),
                    UNIQUE INDEX `uuid_UNIQUE` (`uuid` ASC))
                ENGINE = InnoDB";
    }
    
    private function loteriaRateio(): string {
        return "CREATE TABLE IF NOT EXISTS `bolao`.`loteria_rateio` (
                    `id` INT NOT NULL AUTO_INCREMENT,
                    `uuid` BINARY(16) NOT NULL,
                    `loteria_id` INT NOT NULL,
                    `quantidade_dezenas_jogadas` TINYINT(2) NOT NULL,
                    `quantidade_dezenas_acertadas` TINYINT(2) NOT NULL,
                    `quantidade_dezenas_premiadas` TINYINT(2) NOT NULL,
                    `quantidade_premios` TINYINT(2) NOT NULL,
                    `created_at` TIMESTAMP NOT NULL,
                    `updated_at` TIMESTAMP NULL,
                    PRIMARY KEY (`id`),
                    INDEX `fk_loteria_raterio_loteria1_idx` (`loteria_id` ASC),
                    INDEX `uuid_INDEX` (`uuid` ASC),
                    CONSTRAINT `fk_loteria_raterio_loteria1`
                      FOREIGN KEY (`loteria_id`)
                      REFERENCES `bolao`.`loteria` (`id`)
                      ON DELETE NO ACTION
                      ON UPDATE NO ACTION)
                ENGINE = InnoDB";
    }
}
