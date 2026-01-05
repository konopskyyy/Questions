<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250715112809 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Additional field for open question';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE question DROP INDEX UNIQ_B6F7494EDC9EE959, ADD INDEX IDX_B6F7494EDC9EE959 (metadata_id)');
        $this->addSql('ALTER TABLE question ADD answer LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE question DROP INDEX IDX_B6F7494EDC9EE959, ADD UNIQUE INDEX UNIQ_B6F7494EDC9EE959 (metadata_id)');
        $this->addSql('ALTER TABLE question DROP answer');
    }
}
