<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200128162251 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE produit_recette DROP FOREIGN KEY FK_8F40E8E94FD8F9C3');
        $this->addSql('ALTER TABLE produit_recette DROP FOREIGN KEY FK_8F40E8E983B016C1');
        $this->addSql('DROP INDEX IDX_8F40E8E983B016C1 ON produit_recette');
        $this->addSql('DROP INDEX IDX_8F40E8E94FD8F9C3 ON produit_recette');
        $this->addSql('ALTER TABLE produit_recette CHANGE produit_id_id produit_id INT NOT NULL, CHANGE recette_id_id recette_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit_recette ADD CONSTRAINT FK_8F40E8E9F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE produit_recette ADD CONSTRAINT FK_8F40E8E989312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id)');
        $this->addSql('CREATE INDEX IDX_8F40E8E9F347EFB ON produit_recette (produit_id)');
        $this->addSql('CREATE INDEX IDX_8F40E8E989312FE9 ON produit_recette (recette_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE produit_recette DROP FOREIGN KEY FK_8F40E8E9F347EFB');
        $this->addSql('ALTER TABLE produit_recette DROP FOREIGN KEY FK_8F40E8E989312FE9');
        $this->addSql('DROP INDEX IDX_8F40E8E9F347EFB ON produit_recette');
        $this->addSql('DROP INDEX IDX_8F40E8E989312FE9 ON produit_recette');
        $this->addSql('ALTER TABLE produit_recette CHANGE produit_id produit_id_id INT NOT NULL, CHANGE recette_id recette_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit_recette ADD CONSTRAINT FK_8F40E8E94FD8F9C3 FOREIGN KEY (produit_id_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE produit_recette ADD CONSTRAINT FK_8F40E8E983B016C1 FOREIGN KEY (recette_id_id) REFERENCES recette (id)');
        $this->addSql('CREATE INDEX IDX_8F40E8E983B016C1 ON produit_recette (recette_id_id)');
        $this->addSql('CREATE INDEX IDX_8F40E8E94FD8F9C3 ON produit_recette (produit_id_id)');
    }
}
