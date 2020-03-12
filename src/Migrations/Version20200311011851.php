<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200311011851 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE attachment_usage (id INT AUTO_INCREMENT NOT NULL, used_as VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE attachment ADD used_as_id INT NOT NULL, DROP used_as');
        $this->addSql('ALTER TABLE attachment ADD CONSTRAINT FK_795FD9BB6436E81C FOREIGN KEY (used_as_id) REFERENCES attachment_usage (id)');
        $this->addSql('CREATE INDEX IDX_795FD9BB6436E81C ON attachment (used_as_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE attachment DROP FOREIGN KEY FK_795FD9BB6436E81C');
        $this->addSql('DROP TABLE attachment_usage');
        $this->addSql('DROP INDEX IDX_795FD9BB6436E81C ON attachment');
        $this->addSql('ALTER TABLE attachment ADD used_as VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP used_as_id');
    }
}
