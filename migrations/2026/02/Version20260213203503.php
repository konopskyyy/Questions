<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260213203503 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add File table, add relation in Organization with File';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE file (
              id BINARY(16) NOT NULL COMMENT '(DC2Type:uuid)',
              created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
              filename VARCHAR(255) NOT NULL,
              content LONGBLOB NOT NULL,
              mime_type VARCHAR(100) NOT NULL,
              PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              organization
            ADD
              file_id BINARY(16) DEFAULT NULL COMMENT '(DC2Type:uuid)',
            DROP
              logo
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              organization
            ADD
              CONSTRAINT FK_C1EE637C93CB796C FOREIGN KEY (file_id) REFERENCES file (id)
        SQL);
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C1EE637C93CB796C ON organization (file_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE organization DROP FOREIGN KEY FK_C1EE637C93CB796C');
        $this->addSql('DROP TABLE file');
        $this->addSql('DROP INDEX UNIQ_C1EE637C93CB796C ON organization');
        $this->addSql('ALTER TABLE organization ADD logo VARCHAR(255) NOT NULL, DROP file_id');
    }
}
