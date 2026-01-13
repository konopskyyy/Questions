<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260112195854 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change postal_code length';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE address CHANGE postal_code postal_code VARCHAR(6) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE address CHANGE postal_code postal_code VARCHAR(5) NOT NULL');
    }
}
