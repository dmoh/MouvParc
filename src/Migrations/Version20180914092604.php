<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180914092604 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE demande_absence (id INT AUTO_INCREMENT NOT NULL, demande_absence_conducteur_id INT NOT NULL, date_creation DATETIME NOT NULL, date_demande DATE NOT NULL, statue_demande TINYINT(1) NOT NULL, heure_debut_abs VARCHAR(255) NOT NULL, heure_fin_abs VARCHAR(255) NOT NULL, motif_conducteur LONGTEXT NOT NULL, commentaire_direction LONGTEXT DEFAULT NULL, statue_demande_direction TINYINT(1) DEFAULT NULL, reponse_direction LONGTEXT DEFAULT NULL, cloturer TINYINT(1) NOT NULL, INDEX IDX_46AD5B661CC720FF (demande_absence_conducteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE demande_absence ADD CONSTRAINT FK_46AD5B661CC720FF FOREIGN KEY (demande_absence_conducteur_id) REFERENCES conducteur (id)');
        $this->addSql('DROP TABLE quesrions_paie');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE quesrions_paie (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE demande_absence');
    }
}
