<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200129082346 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, recette_id INT DEFAULT NULL, note INT NOT NULL, commentaire LONGTEXT NOT NULL, date_creation DATETIME NOT NULL, INDEX IDX_67F068BCA76ED395 (user_id), INDEX IDX_67F068BC89312FE9 (recette_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, latin VARCHAR(255) NOT NULL, INDEX IDX_29A5EC27C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit_recette (id INT AUTO_INCREMENT NOT NULL, produit_id INT NOT NULL, recette_id INT DEFAULT NULL, quantite VARCHAR(255) NOT NULL, INDEX IDX_8F40E8E9F347EFB (produit_id), INDEX IDX_8F40E8E989312FE9 (recette_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recette (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, publie_par_id INT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, slug VARCHAR(255) NOT NULL, INDEX IDX_49BB6390BCF5E72D (categorie_id), INDEX IDX_49BB6390801A2092 (publie_par_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_produit (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC89312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27C54C8C93 FOREIGN KEY (type_id) REFERENCES type_produit (id)');
        $this->addSql('ALTER TABLE produit_recette ADD CONSTRAINT FK_8F40E8E9F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE produit_recette ADD CONSTRAINT FK_8F40E8E989312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id)');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT FK_49BB6390BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT FK_49BB6390801A2092 FOREIGN KEY (publie_par_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE aromatheque_produit');
        $this->addSql('DROP TABLE aromatheque_user');
        $this->addSql('ALTER TABLE aromatheque ADD user_id INT DEFAULT NULL, ADD produit_id INT DEFAULT NULL, ADD quantite VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE aromatheque ADD CONSTRAINT FK_E23E7F96A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE aromatheque ADD CONSTRAINT FK_E23E7F96F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_E23E7F96A76ED395 ON aromatheque (user_id)');
        $this->addSql('CREATE INDEX IDX_E23E7F96F347EFB ON aromatheque (produit_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE recette DROP FOREIGN KEY FK_49BB6390BCF5E72D');
        $this->addSql('ALTER TABLE aromatheque DROP FOREIGN KEY FK_E23E7F96F347EFB');
        $this->addSql('ALTER TABLE produit_recette DROP FOREIGN KEY FK_8F40E8E9F347EFB');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC89312FE9');
        $this->addSql('ALTER TABLE produit_recette DROP FOREIGN KEY FK_8F40E8E989312FE9');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27C54C8C93');
        $this->addSql('CREATE TABLE aromatheque_produit (aromatheque_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_C246E28CF6197902 (aromatheque_id), INDEX IDX_C246E28CF347EFB (produit_id), PRIMARY KEY(aromatheque_id, produit_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE aromatheque_user (aromatheque_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_FF119DDCF6197902 (aromatheque_id), INDEX IDX_FF119DDCA76ED395 (user_id), PRIMARY KEY(aromatheque_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE aromatheque_produit ADD CONSTRAINT FK_C246E28CF6197902 FOREIGN KEY (aromatheque_id) REFERENCES aromatheque (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE aromatheque_user ADD CONSTRAINT FK_FF119DDCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE aromatheque_user ADD CONSTRAINT FK_FF119DDCF6197902 FOREIGN KEY (aromatheque_id) REFERENCES aromatheque (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE produit_recette');
        $this->addSql('DROP TABLE recette');
        $this->addSql('DROP TABLE type_produit');
        $this->addSql('ALTER TABLE aromatheque DROP FOREIGN KEY FK_E23E7F96A76ED395');
        $this->addSql('DROP INDEX IDX_E23E7F96A76ED395 ON aromatheque');
        $this->addSql('DROP INDEX IDX_E23E7F96F347EFB ON aromatheque');
        $this->addSql('ALTER TABLE aromatheque DROP user_id, DROP produit_id, DROP quantite');
    }
}
