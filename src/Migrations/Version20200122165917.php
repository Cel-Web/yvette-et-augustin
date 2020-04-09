<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200122165917 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE aromatheque ADD user_id_id INT NOT NULL, ADD produit_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE aromatheque ADD CONSTRAINT FK_E23E7F969D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE aromatheque ADD CONSTRAINT FK_E23E7F964FD8F9C3 FOREIGN KEY (produit_id_id) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_E23E7F969D86650F ON aromatheque (user_id_id)');
        $this->addSql('CREATE INDEX IDX_E23E7F964FD8F9C3 ON aromatheque (produit_id_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE aromatheque DROP FOREIGN KEY FK_E23E7F969D86650F');
        $this->addSql('ALTER TABLE aromatheque DROP FOREIGN KEY FK_E23E7F964FD8F9C3');
        $this->addSql('DROP INDEX IDX_E23E7F969D86650F ON aromatheque');
        $this->addSql('DROP INDEX IDX_E23E7F964FD8F9C3 ON aromatheque');
        $this->addSql('ALTER TABLE aromatheque DROP user_id_id, DROP produit_id_id');
    }
}
