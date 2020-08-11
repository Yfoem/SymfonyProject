<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200811074707 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lieux (id INT AUTO_INCREMENT NOT NULL, villes_no_ville_id INT NOT NULL, nom_lieu VARCHAR(255) NOT NULL, rue VARCHAR(255) DEFAULT NULL, latitude INT DEFAULT NULL, longitude INT DEFAULT NULL, INDEX IDX_9E44A8AE27E30153 (villes_no_ville_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lieux ADD CONSTRAINT FK_9E44A8AE27E30153 FOREIGN KEY (villes_no_ville_id) REFERENCES villes (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE lieux');
    }
}
