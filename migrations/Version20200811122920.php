<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200811122920 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE inscription (id INT AUTO_INCREMENT NOT NULL, participants_no_participant_id INT NOT NULL, date_inscription DATETIME NOT NULL, INDEX IDX_5E90F6D6ACA3F17D (participants_no_participant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inscription_sorties (inscription_id INT NOT NULL, sorties_id INT NOT NULL, INDEX IDX_615095045DAC5993 (inscription_id), INDEX IDX_6150950415DFCFB2 (sorties_id), PRIMARY KEY(inscription_id, sorties_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE inscription ADD CONSTRAINT FK_5E90F6D6ACA3F17D FOREIGN KEY (participants_no_participant_id) REFERENCES participants (id)');
        $this->addSql('ALTER TABLE inscription_sorties ADD CONSTRAINT FK_615095045DAC5993 FOREIGN KEY (inscription_id) REFERENCES inscription (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inscription_sorties ADD CONSTRAINT FK_6150950415DFCFB2 FOREIGN KEY (sorties_id) REFERENCES sorties (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inscription_sorties DROP FOREIGN KEY FK_615095045DAC5993');
        $this->addSql('DROP TABLE inscription');
        $this->addSql('DROP TABLE inscription_sorties');
    }
}
