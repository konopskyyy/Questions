<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260301142257 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change field_id to logo_id name in Organization';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE organization DROP FOREIGN KEY FK_C1EE637C93CB796C');
        $this->addSql('DROP INDEX UNIQ_C1EE637C93CB796C ON organization');
        $this->addSql(<<<'SQL'
            ALTER TABLE
              organization
            CHANGE
              file_id logo_id BINARY(16) DEFAULT NULL COMMENT '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              organization
            ADD
              CONSTRAINT FK_C1EE637CF98F144A FOREIGN KEY (logo_id) REFERENCES file (id)
        SQL);
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C1EE637CF98F144A ON organization (logo_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE organization DROP FOREIGN KEY FK_C1EE637CF98F144A');
        $this->addSql('DROP INDEX UNIQ_C1EE637CF98F144A ON organization');
        $this->addSql(<<<'SQL'
            ALTER TABLE
              organization
            CHANGE
              logo_id file_id BINARY(16) DEFAULT NULL COMMENT '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              organization
            ADD
              CONSTRAINT FK_C1EE637C93CB796C FOREIGN KEY (file_id) REFERENCES file (id) ON
            UPDATE
              NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C1EE637C93CB796C ON organization (file_id)');
    }
}
