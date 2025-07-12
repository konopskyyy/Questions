<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250704162208 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Question Aggregate';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE question (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', metadata_id INT DEFAULT NULL, body LONGTEXT NOT NULL, type VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_B6F7494EDC9EE959 (metadata_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question_question_tag (question_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', question_tag_id INT NOT NULL, INDEX IDX_A077ADB91E27F6BF (question_id), INDEX IDX_A077ADB9BD8F4C19 (question_tag_id), PRIMARY KEY(question_id, question_tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question_image (id INT AUTO_INCREMENT NOT NULL, question_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, url LONGTEXT NOT NULL, INDEX IDX_F5D6155B1E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question_metadata (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_by VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question_tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question_tip (id INT AUTO_INCREMENT NOT NULL, question_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', description LONGTEXT NOT NULL, INDEX IDX_789759341E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question_url (id INT AUTO_INCREMENT NOT NULL, question_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', description VARCHAR(255) NOT NULL, url LONGTEXT NOT NULL, INDEX IDX_C462A4D61E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EDC9EE959 FOREIGN KEY (metadata_id) REFERENCES question_metadata (id)');
        $this->addSql('ALTER TABLE question_question_tag ADD CONSTRAINT FK_A077ADB91E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE question_question_tag ADD CONSTRAINT FK_A077ADB9BD8F4C19 FOREIGN KEY (question_tag_id) REFERENCES question_tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE question_image ADD CONSTRAINT FK_F5D6155B1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE question_tip ADD CONSTRAINT FK_789759341E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE question_url ADD CONSTRAINT FK_C462A4D61E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EDC9EE959');
        $this->addSql('ALTER TABLE question_question_tag DROP FOREIGN KEY FK_A077ADB91E27F6BF');
        $this->addSql('ALTER TABLE question_question_tag DROP FOREIGN KEY FK_A077ADB9BD8F4C19');
        $this->addSql('ALTER TABLE question_image DROP FOREIGN KEY FK_F5D6155B1E27F6BF');
        $this->addSql('ALTER TABLE question_tip DROP FOREIGN KEY FK_789759341E27F6BF');
        $this->addSql('ALTER TABLE question_url DROP FOREIGN KEY FK_C462A4D61E27F6BF');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE question_question_tag');
        $this->addSql('DROP TABLE question_image');
        $this->addSql('DROP TABLE question_metadata');
        $this->addSql('DROP TABLE question_tag');
        $this->addSql('DROP TABLE question_tip');
        $this->addSql('DROP TABLE question_url');
    }
}
