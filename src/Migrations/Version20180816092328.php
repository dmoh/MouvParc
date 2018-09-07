<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180816092328 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE rapport_hebdo (id INT AUTO_INCREMENT NOT NULL, rapport_conducteur_id INT NOT NULL, date_creation DATETIME NOT NULL, date_reclame DATETIME NOT NULL, travail_hors_tachy VARCHAR(255) NOT NULL, repas_midi VARCHAR(255) NOT NULL, reepas_soir TINYINT(1) NOT NULL, observations_rapport LONGTEXT DEFAULT NULL, statu_demande TINYINT(1) NOT NULL, reponse_demande VARCHAR(255) DEFAULT NULL, compteur_rapport INT NOT NULL, societe_aps_fle VARCHAR(255) DEFAULT NULL, INDEX IDX_D465185A98CD7E57 (rapport_conducteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rapport_hebdo ADD CONSTRAINT FK_D465185A98CD7E57 FOREIGN KEY (rapport_conducteur_id) REFERENCES conducteur (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE rapport_hebdo');
    }
}
