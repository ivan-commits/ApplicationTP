<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200309134753 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user ADD surname VARCHAR(255) DEFAULT NULL, ADD actif TINYINT(1) NOT NULL, ADD date_naissance DATETIME DEFAULT NULL, ADD firstname_parent1 VARCHAR(255) DEFAULT NULL, ADD surname_parent1 VARCHAR(255) DEFAULT NULL, ADD firstname_parent2 VARCHAR(255) DEFAULT NULL, ADD surname_parent2 VARCHAR(255) DEFAULT NULL, ADD firstname_educateur VARCHAR(255) DEFAULT NULL, ADD surname_educateur VARCHAR(255) DEFAULT NULL, ADD firstname_orthophoniste VARCHAR(255) DEFAULT NULL, ADD surname_orthophoniste VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP surname, DROP actif, DROP date_naissance, DROP firstname_parent1, DROP surname_parent1, DROP firstname_parent2, DROP surname_parent2, DROP firstname_educateur, DROP surname_educateur, DROP firstname_orthophoniste, DROP surname_orthophoniste');
    }
}
