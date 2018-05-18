<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180517155518 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cars ADD regulateur_vitesse TINYINT(1) DEFAULT NULL, ADD gps TINYINT(1) DEFAULT NULL, ADD ralentisseur VARCHAR(255) DEFAULT NULL, ADD abs TINYINT(1) DEFAULT NULL, ADD esp TINYINT(1) DEFAULT NULL, ADD asr TINYINT(1) DEFAULT NULL, ADD ceinture_securite VARCHAR(255) DEFAULT NULL, ADD repose_mollet TINYINT(1) DEFAULT NULL, ADD tachygraphe VARCHAR(255) DEFAULT NULL, ADD video VARCHAR(255) DEFAULT NULL, ADD micro_conducteur TINYINT(1) DEFAULT NULL, ADD camera VARCHAR(255) DEFAULT NULL, ADD radio VARCHAR(255) DEFAULT NULL, ADD micro_guide TINYINT(1) DEFAULT NULL, ADD chauffage_independant TINYINT(1) DEFAULT NULL, ADD buses_individuelles TINYINT(1) DEFAULT NULL, ADD tablettes TINYINT(1) DEFAULT NULL, ADD sieges_decalables TINYINT(1) DEFAULT NULL, ADD frigo TINYINT(1) DEFAULT NULL, ADD girouette TINYINT(1) DEFAULT NULL, ADD rideaux TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cars DROP regulateur_vitesse, DROP gps, DROP ralentisseur, DROP abs, DROP esp, DROP asr, DROP ceinture_securite, DROP repose_mollet, DROP tachygraphe, DROP video, DROP micro_conducteur, DROP camera, DROP radio, DROP micro_guide, DROP chauffage_independant, DROP buses_individuelles, DROP tablettes, DROP sieges_decalables, DROP frigo, DROP girouette, DROP rideaux');
    }
}
