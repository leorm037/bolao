<?php

declare(strict_types=1);

/*
 *     This file is part of Bolão.
 *
 *     (c) Leonardo Rodrigues Marques <leonardo@rodriguesmarques.com.br>
 *
 *     This source file is subject to the MIT license that is bundled
 *     with this source code in the file LICENSE.
 */

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251002182817 extends AbstractMigration
{

    public function getDescription(): string
    {
        return 'Concurso e Bolão';
    }

    public function up(Schema $schema): void
    {
        $this->addSql($this->concurso());
        $this->addSql($this->bolao());
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }

    private function concurso(): string
    {
        return 'CREATE TABLE IF NOT EXISTS `bolao`.`concurso` (
                    `id` INT NOT NULL AUTO_INCREMENT,
                    `uuid` BINARY(16) NOT NULL,
                    `loteria_id` INT NOT NULL,
                    `numero` INT NOT NULL,
                    `dezenas` LONGTEXT NULL,
                    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` TIMESTAMP NULL,
                    `apuracao` DATE NULL,
                    `municipio` VARCHAR(60) NULL,
                    `uf` VARCHAR(2) NULL,
                    `local` VARCHAR(60) NULL,
                    PRIMARY KEY (`id`),
                    INDEX `uuid_INDEX` (`uuid` ASC),
                    INDEX `numero_INDEX` (`numero` ASC),
                    INDEX `fk_concurso_loteria_idx` (`loteria_id` ASC),
                    CONSTRAINT `fk_concurso_loteria`
                      FOREIGN KEY (`loteria_id`)
                      REFERENCES `bolao`.`loteria` (`id`)
                      ON DELETE NO ACTION
                      ON UPDATE NO ACTION)
                  ENGINE = InnoDB'
        ;
    }

    private function bolao(): string
    {
        return 'CREATE TABLE IF NOT EXISTS `bolao`.`bolao` (
                    `id` INT NOT NULL AUTO_INCREMENT,
                    `uuid` BINARY(16) NOT NULL,
                    `nome` VARCHAR(120) NOT NULL,
                    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` TIMESTAMP NULL,
                    `concurso_id` INT NOT NULL,
                    `usuario_id` INT NOT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE INDEX `uuid_UNIQUE` (`uuid` ASC),
                    INDEX `fk_bolao_concurso_idx` (`concurso_id` ASC),
                    INDEX `fk_bolao_usuario_idx` (`usuario_id` ASC),
                    FULLTEXT INDEX `nome_FULLTEXT` (`nome`),
                    CONSTRAINT `fk_bolao_concurso`
                      FOREIGN KEY (`concurso_id`)
                      REFERENCES `bolao`.`concurso` (`id`)
                      ON DELETE NO ACTION
                      ON UPDATE NO ACTION,
                    CONSTRAINT `fk_bolao_usuario`
                      FOREIGN KEY (`usuario_id`)
                      REFERENCES `bolao`.`usuario` (`id`)
                      ON DELETE NO ACTION
                      ON UPDATE NO ACTION)
                  ENGINE = InnoDB'
        ;
    }
}