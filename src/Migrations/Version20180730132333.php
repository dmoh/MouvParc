<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180730132333 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE app_users ADD conducteur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE app_users ADD CONSTRAINT FK_C2502824F16F4AC6 FOREIGN KEY (conducteur_id) REFERENCES conducteur (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C2502824F16F4AC6 ON app_users (conducteur_id)');
        $this->addSql('ALTER TABLE conducteur ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE conducteur ADD CONSTRAINT FK_23677143A76ED395 FOREIGN KEY (user_id) REFERENCES app_users (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_23677143A76ED395 ON conducteur (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE app_users DROP FOREIGN KEY FK_C2502824F16F4AC6');
        $this->addSql('DROP INDEX UNIQ_C2502824F16F4AC6 ON app_users');
        $this->addSql('ALTER TABLE app_users DROP conducteur_id');
        $this->addSql('ALTER TABLE conducteur DROP FOREIGN KEY FK_23677143A76ED395');
        $this->addSql('DROP INDEX UNIQ_23677143A76ED395 ON conducteur');
        $this->addSql('ALTER TABLE conducteur DROP user_id');
    }
}
