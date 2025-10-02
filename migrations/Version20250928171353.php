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
final class Version20250928171353 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adicionar o campo isDefault na tabela Apostador';
    }

    public function up(Schema $schema): void
    {
        $this->addSql($this->apostadorAlterar());
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }

    private function apostadorAlterar(): string
    {
        return "ALTER TABLE `apostador` ADD `is_default` TINYINT(1) NOT NULL DEFAULT '0' AFTER `celular`, ADD INDEX `is_default-INDEX` (`is_default`)";
    }
}
