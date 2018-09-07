<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180829155738 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE demande_accompte (id INT AUTO_INCREMENT NOT NULL, demande_accompte_conducteur_id INT NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, date_creation DATETIME NOT NULL, date_demande DATETIME NOT NULL, montant_accompte INT NOT NULL, statue_demande TINYINT(1) NOT NULL, obs_accompte_conducteur LONGTEXT DEFAULT NULL, reponse_direction VARCHAR(255) DEFAULT NULL, obs_direction LONGTEXT DEFAULT NULL, INDEX IDX_FB5093354E747516 (demande_accompte_conducteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE demande_accompte ADD CONSTRAINT FK_FB5093354E747516 FOREIGN KEY (demande_accompte_conducteur_id) REFERENCES conducteur (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE demande_accompte');
    }
}
