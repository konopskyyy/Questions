<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250916043221 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add unique constraints to name and description in question_tag, and add color field';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE question_tag ADD color VARCHAR(7) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_339D56FB5E237E06 ON question_tag (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_339D56FB6DE44026 ON question_tag (description)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_339D56FB5E237E06 ON question_tag');
        $this->addSql('DROP INDEX UNIQ_339D56FB6DE44026 ON question_tag');
        $this->addSql('ALTER TABLE question_tag DROP color');
    }
}
