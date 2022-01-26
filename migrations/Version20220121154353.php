<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220121154353 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE play_list_track');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE play_list_track (
          play_list_id INT NOT NULL,
          track_id INT NOT NULL,
          INDEX IDX_1A9D52C04BB0713B (play_list_id),
          INDEX IDX_1A9D52C05ED23C43 (track_id),
          PRIMARY KEY(play_list_id, track_id)
        ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\'');
        $this->addSql('ALTER TABLE
          play_list_track
        ADD
          CONSTRAINT FK_1A9D52C05ED23C43 FOREIGN KEY (track_id) REFERENCES track (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE
          play_list_track
        ADD
          CONSTRAINT FK_1A9D52C04BB0713B FOREIGN KEY (play_list_id) REFERENCES play_list (id) ON DELETE CASCADE');
    }
}
