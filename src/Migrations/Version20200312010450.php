<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200312010450 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C637802FF');
        $this->addSql('DROP INDEX IDX_9474526C637802FF ON comment');
        $this->addSql('ALTER TABLE comment CHANGE comment_owner_id commentOwner INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C75B21B8F FOREIGN KEY (commentOwner) REFERENCES comment (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_9474526C75B21B8F ON comment (commentOwner)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C75B21B8F');
        $this->addSql('DROP INDEX IDX_9474526C75B21B8F ON comment');
        $this->addSql('ALTER TABLE comment CHANGE commentowner comment_owner_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C637802FF FOREIGN KEY (comment_owner_id) REFERENCES comment (id)');
        $this->addSql('CREATE INDEX IDX_9474526C637802FF ON comment (comment_owner_id)');
    }
}
