<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200811114630 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sorties (id INT AUTO_INCREMENT NOT NULL, lieux_id_id INT NOT NULL, etats_id_id INT NOT NULL, nom VARCHAR(255) NOT NULL, datedebut DATETIME NOT NULL, duree INT DEFAULT NULL, datecloture DATETIME NOT NULL, nbinscriptionsmax INT NOT NULL, descriptioninfos VARCHAR(500) DEFAULT NULL, etatsortie INT DEFAULT NULL, url_photo VARCHAR(255) DEFAULT NULL, organisateur INT NOT NULL, INDEX IDX_488163E875C68C92 (lieux_id_id), INDEX IDX_488163E84F9ABD27 (etats_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sorties ADD CONSTRAINT FK_488163E875C68C92 FOREIGN KEY (lieux_id_id) REFERENCES lieux (id)');
        $this->addSql('ALTER TABLE sorties ADD CONSTRAINT FK_488163E84F9ABD27 FOREIGN KEY (etats_id_id) REFERENCES etats (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE sorties');
    }
}
