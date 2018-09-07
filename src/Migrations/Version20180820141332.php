<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180820141332 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE demande_conges (id INT AUTO_INCREMENT NOT NULL, demande_conge_conducteur_id INT NOT NULL, date_creation DATETIME NOT NULL, date_demande DATETIME NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, type_de_conge VARCHAR(255) NOT NULL, du_date_conge DATETIME NOT NULL, au_date_conge DATETIME NOT NULL, statue_demande TINYINT(1) NOT NULL, reponse_direction VARCHAR(255) DEFAULT NULL, accord_refus_direction VARCHAR(255) DEFAULT NULL, demande_cloturer TINYINT(1) NOT NULL, INDEX IDX_216398D5A14B6E73 (demande_conge_conducteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE demande_conges ADD CONSTRAINT FK_216398D5A14B6E73 FOREIGN KEY (demande_conge_conducteur_id) REFERENCES conducteur (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE demande_conges');
    }
}
