<?php

declare(strict_types=1);


use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250715203542 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Closed question options';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE answer_option (id INT AUTO_INCREMENT NOT NULL, question_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', letter VARCHAR(255) NOT NULL, body VARCHAR(255) NOT NULL, is_correct TINYINT(1) NOT NULL, INDEX IDX_A87F3A171E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE answer_option ADD CONSTRAINT FK_A87F3A171E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE answer_option DROP FOREIGN KEY FK_A87F3A171E27F6BF');
        $this->addSql('DROP TABLE answer_option');
    }
}
