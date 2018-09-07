<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180814141925 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE demandes_conducteurs (id INT AUTO_INCREMENT NOT NULL, conducteur_id INT NOT NULL, date_demande DATETIME NOT NULL, datej1 DATETIME DEFAULT NULL, datej2 DATETIME DEFAULT NULL, datej3 DATETIME DEFAULT NULL, datej4 DATETIME DEFAULT NULL, datej5 DATETIME DEFAULT NULL, datej6 DATETIME DEFAULT NULL, travail_hors_tachy VARCHAR(255) DEFAULT NULL, repas_midi TINYINT(1) DEFAULT NULL, repas_soir TINYINT(1) DEFAULT NULL, observations_conducteur LONGTEXT DEFAULT NULL, demande_status TINYINT(1) DEFAULT NULL, INDEX IDX_D4486ADAF16F4AC6 (conducteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE demandes_conducteurs ADD CONSTRAINT FK_D4486ADAF16F4AC6 FOREIGN KEY (conducteur_id) REFERENCES conducteur (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE demandes_conducteurs');
    }
}
