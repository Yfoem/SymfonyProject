<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200811100653 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE etats (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lieux DROP FOREIGN KEY FK_9E44A8AE15DFCFB2');
        $this->addSql('DROP INDEX IDX_9E44A8AE15DFCFB2 ON lieux');
        $this->addSql('ALTER TABLE lieux DROP sorties_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE etats');
        $this->addSql('ALTER TABLE lieux ADD sorties_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE lieux ADD CONSTRAINT FK_9E44A8AE15DFCFB2 FOREIGN KEY (sorties_id) REFERENCES sorties (id)');
        $this->addSql('CREATE INDEX IDX_9E44A8AE15DFCFB2 ON lieux (sorties_id)');
    }
}
