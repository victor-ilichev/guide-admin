<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220120125045 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE play_list_track (
          play_list_id INT NOT NULL,
          track_id INT NOT NULL,
          INDEX IDX_1A9D52C04BB0713B (play_list_id),
          INDEX IDX_1A9D52C05ED23C43 (track_id),
          PRIMARY KEY(play_list_id, track_id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE
          play_list_track
        ADD
          CONSTRAINT FK_1A9D52C04BB0713B FOREIGN KEY (play_list_id) REFERENCES play_list (id) ON DELETE CASCADE');

        $this->addSql('ALTER TABLE
          play_list_track
        ADD
          CONSTRAINT FK_1A9D52C05ED23C43 FOREIGN KEY (track_id) REFERENCES track (id) ON DELETE CASCADE');

        $this->addSql('ALTER TABLE track DROP FOREIGN KEY fk_play_list__play_list_id');
        $this->addSql('DROP INDEX idx_play_list_id ON track');
        $this->addSql('ALTER TABLE track DROP play_list_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE play_list_track');
        $this->addSql('ALTER TABLE track ADD play_list_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE
          track
        ADD
          CONSTRAINT fk_play_list__play_list_id FOREIGN KEY (play_list_id) REFERENCES play_list (id)');
        $this->addSql('CREATE INDEX idx_play_list_id ON track (play_list_id)');
    }
}
