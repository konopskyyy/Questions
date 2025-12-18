<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251215204019 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Organization table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE address (
              id BINARY(16) NOT NULL COMMENT '(DC2Type:uuid)',
              created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
              updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
              street VARCHAR(255) NOT NULL,
              building_no VARCHAR(5) NOT NULL,
              apartment_no VARCHAR(5) DEFAULT NULL,
              city VARCHAR(255) NOT NULL,
              postal_code VARCHAR(5) NOT NULL,
              country VARCHAR(255) NOT NULL,
              PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE organization (
              id BINARY(16) NOT NULL COMMENT '(DC2Type:uuid)',
              address_id BINARY(16) DEFAULT NULL COMMENT '(DC2Type:uuid)',
              created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
              updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
              name VARCHAR(255) NOT NULL,
              logo VARCHAR(255) NOT NULL,
              tax_id VARCHAR(120) NOT NULL,
              UNIQUE INDEX UNIQ_C1EE637CF5B7AF75 (address_id),
              PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE organization_recruiters (
              organization_id BINARY(16) NOT NULL COMMENT '(DC2Type:uuid)',
              recruiters_id BINARY(16) NOT NULL COMMENT '(DC2Type:uuid)',
              INDEX IDX_7A83813132C8A3DE (organization_id),
              INDEX IDX_7A8381318B1C6ED (recruiters_id),
              PRIMARY KEY(organization_id, recruiters_id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE organization_candidates (
              organization_id BINARY(16) NOT NULL COMMENT '(DC2Type:uuid)',
              candidates_id BINARY(16) NOT NULL COMMENT '(DC2Type:uuid)',
              INDEX IDX_832C6AE332C8A3DE (organization_id),
              INDEX IDX_832C6AE37D5FB314 (candidates_id),
              PRIMARY KEY(organization_id, candidates_id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              organization
            ADD
              CONSTRAINT FK_C1EE637CF5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              organization_recruiters
            ADD
              CONSTRAINT FK_7A83813132C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              organization_recruiters
            ADD
              CONSTRAINT FK_7A8381318B1C6ED FOREIGN KEY (recruiters_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              organization_candidates
            ADD
              CONSTRAINT FK_832C6AE332C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              organization_candidates
            ADD
              CONSTRAINT FK_832C6AE37D5FB314 FOREIGN KEY (candidates_id) REFERENCES user (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE organization DROP FOREIGN KEY FK_C1EE637CF5B7AF75');
        $this->addSql('ALTER TABLE organization_recruiters DROP FOREIGN KEY FK_7A83813132C8A3DE');
        $this->addSql('ALTER TABLE organization_recruiters DROP FOREIGN KEY FK_7A8381318B1C6ED');
        $this->addSql('ALTER TABLE organization_candidates DROP FOREIGN KEY FK_832C6AE332C8A3DE');
        $this->addSql('ALTER TABLE organization_candidates DROP FOREIGN KEY FK_832C6AE37D5FB314');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE organization');
        $this->addSql('DROP TABLE organization_recruiters');
        $this->addSql('DROP TABLE organization_candidates');
    }
}
