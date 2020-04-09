<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200122165013 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE aromatheque_produit');
        $this->addSql('DROP TABLE aromatheque_user');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE aromatheque_produit (aromatheque_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_C246E28CF6197902 (aromatheque_id), INDEX IDX_C246E28CF347EFB (produit_id), PRIMARY KEY(aromatheque_id, produit_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE aromatheque_user (aromatheque_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_FF119DDCF6197902 (aromatheque_id), INDEX IDX_FF119DDCA76ED395 (user_id), PRIMARY KEY(aromatheque_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE aromatheque_produit ADD CONSTRAINT FK_C246E28CF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE aromatheque_produit ADD CONSTRAINT FK_C246E28CF6197902 FOREIGN KEY (aromatheque_id) REFERENCES aromatheque (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE aromatheque_user ADD CONSTRAINT FK_FF119DDCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE aromatheque_user ADD CONSTRAINT FK_FF119DDCF6197902 FOREIGN KEY (aromatheque_id) REFERENCES aromatheque (id) ON DELETE CASCADE');
    }
}
