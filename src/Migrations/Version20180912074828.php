<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180912074828 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE quesrions_paie (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE questions_paie (id INT AUTO_INCREMENT NOT NULL, questions_paie_conducteur_id INT NOT NULL, date_creation DATETIME NOT NULL, date_demande DATETIME NOT NULL, objet_demande VARCHAR(255) NOT NULL, statue_demande TINYINT(1) NOT NULL, statue_demande_direction TINYINT(1) DEFAULT NULL, reponse_direction VARCHAR(255) DEFAULT NULL, INDEX IDX_97293FFC486BFAC6 (questions_paie_conducteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE questions_paie ADD CONSTRAINT FK_97293FFC486BFAC6 FOREIGN KEY (questions_paie_conducteur_id) REFERENCES conducteur (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE quesrions_paie');
        $this->addSql('DROP TABLE questions_paie');
    }
}
