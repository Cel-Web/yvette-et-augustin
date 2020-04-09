<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200122124421 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE aromatheque (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE aromatheque_user (aromatheque_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_FF119DDCF6197902 (aromatheque_id), INDEX IDX_FF119DDCA76ED395 (user_id), PRIMARY KEY(aromatheque_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE aromatheque_produit (aromatheque_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_C246E28CF6197902 (aromatheque_id), INDEX IDX_C246E28CF347EFB (produit_id), PRIMARY KEY(aromatheque_id, produit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE aromatheque_user ADD CONSTRAINT FK_FF119DDCF6197902 FOREIGN KEY (aromatheque_id) REFERENCES aromatheque (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE aromatheque_user ADD CONSTRAINT FK_FF119DDCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE aromatheque_produit ADD CONSTRAINT FK_C246E28CF6197902 FOREIGN KEY (aromatheque_id) REFERENCES aromatheque (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE aromatheque_produit ADD CONSTRAINT FK_C246E28CF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE aromatheque_user DROP FOREIGN KEY FK_FF119DDCF6197902');
        $this->addSql('ALTER TABLE aromatheque_produit DROP FOREIGN KEY FK_C246E28CF6197902');
        $this->addSql('DROP TABLE aromatheque');
        $this->addSql('DROP TABLE aromatheque_user');
        $this->addSql('DROP TABLE aromatheque_produit');
    }
}
