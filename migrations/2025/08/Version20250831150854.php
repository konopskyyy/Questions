<?php

declare(strict_types=1);


use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250831150854 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add is_active to user and change id to uuid';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user ADD is_active TINYINT(1) NOT NULL, CHANGE id id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('UPDATE user SET is_active = 1');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user DROP is_active, CHANGE id id INT AUTO_INCREMENT NOT NULL');
    }
}
