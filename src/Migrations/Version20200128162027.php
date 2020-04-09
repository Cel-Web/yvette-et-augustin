<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200128162027 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE aromatheque DROP FOREIGN KEY FK_E23E7F964FD8F9C3');
        $this->addSql('DROP INDEX IDX_E23E7F964FD8F9C3 ON aromatheque');
        $this->addSql('ALTER TABLE aromatheque CHANGE produit_id_id produit_id INT NOT NULL');
        $this->addSql('ALTER TABLE aromatheque ADD CONSTRAINT FK_E23E7F96F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_E23E7F96F347EFB ON aromatheque (produit_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE aromatheque DROP FOREIGN KEY FK_E23E7F96F347EFB');
        $this->addSql('DROP INDEX IDX_E23E7F96F347EFB ON aromatheque');
        $this->addSql('ALTER TABLE aromatheque CHANGE produit_id produit_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE aromatheque ADD CONSTRAINT FK_E23E7F964FD8F9C3 FOREIGN KEY (produit_id_id) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_E23E7F964FD8F9C3 ON aromatheque (produit_id_id)');
    }
}
