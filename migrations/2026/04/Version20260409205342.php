<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260409205342 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Replace organization recruiters and candidates with organization membership roles';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE organization_membership (
              id BINARY(16) NOT NULL COMMENT '(DC2Type:uuid)',
              organization_id BINARY(16) NOT NULL COMMENT '(DC2Type:uuid)',
              user_id BINARY(16) NOT NULL COMMENT '(DC2Type:uuid)',
              role VARCHAR(32) NOT NULL,
              created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
              updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
              INDEX IDX_2F5E11C632C8A3DE (organization_id),
              INDEX IDX_2F5E11C6A76ED395 (user_id),
              UNIQUE INDEX uniq_organization_membership_user (organization_id, user_id),
              PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              organization_membership
            ADD
              CONSTRAINT FK_2F5E11C632C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE
              organization_membership
            ADD
              CONSTRAINT FK_2F5E11C6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO organization_membership (id, organization_id, user_id, role, created_at, updated_at)
            SELECT UUID_TO_BIN(UUID()), organization_id, recruiters_id, 'recruiter', NOW(), NULL
            FROM organization_recruiters
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO organization_membership (id, organization_id, user_id, role, created_at, updated_at)
            SELECT UUID_TO_BIN(UUID()), oc.organization_id, oc.candidates_id, 'candidate', NOW(), NULL
            FROM organization_candidates oc
            LEFT JOIN organization_membership om
              ON om.organization_id = oc.organization_id
             AND om.user_id = oc.candidates_id
            WHERE om.id IS NULL
        SQL);
        $this->addSql('ALTER TABLE organization_recruiters DROP FOREIGN KEY FK_7A83813132C8A3DE');
        $this->addSql('ALTER TABLE organization_recruiters DROP FOREIGN KEY FK_7A8381318B1C6ED');
        $this->addSql('ALTER TABLE organization_candidates DROP FOREIGN KEY FK_832C6AE332C8A3DE');
        $this->addSql('ALTER TABLE organization_candidates DROP FOREIGN KEY FK_832C6AE37D5FB314');
        $this->addSql('DROP TABLE organization_recruiters');
        $this->addSql('DROP TABLE organization_candidates');
    }

    public function down(Schema $schema): void
    {
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
        $this->addSql(<<<'SQL'
            INSERT INTO organization_recruiters (organization_id, recruiters_id)
            SELECT organization_id, user_id
            FROM organization_membership
            WHERE role IN ('owner', 'admin', 'recruiter')
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO organization_candidates (organization_id, candidates_id)
            SELECT organization_id, user_id
            FROM organization_membership
            WHERE role = 'candidate'
        SQL);
        $this->addSql('ALTER TABLE organization_membership DROP FOREIGN KEY FK_2F5E11C632C8A3DE');
        $this->addSql('ALTER TABLE organization_membership DROP FOREIGN KEY FK_2F5E11C6A76ED395');
        $this->addSql('DROP TABLE organization_membership');
    }
}
