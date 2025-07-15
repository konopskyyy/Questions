<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250715201231 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Answers for Closed Questions';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE answer_option (id INT AUTO_INCREMENT NOT NULL, question_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', letter VARCHAR(255) NOT NULL, body VARCHAR(255) NOT NULL, INDEX IDX_A87F3A171E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE correct_answer (id INT AUTO_INCREMENT NOT NULL, question_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', letter VARCHAR(255) NOT NULL, INDEX IDX_A203B7E01E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE answer_option ADD CONSTRAINT FK_A87F3A171E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE correct_answer ADD CONSTRAINT FK_A203B7E01E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE answer_option DROP FOREIGN KEY FK_A87F3A171E27F6BF');
        $this->addSql('ALTER TABLE correct_answer DROP FOREIGN KEY FK_A203B7E01E27F6BF');
        $this->addSql('DROP TABLE answer_option');
        $this->addSql('DROP TABLE correct_answer');
    }
}
