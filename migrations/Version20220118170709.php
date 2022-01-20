<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220118170709 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE excursion (
          id INT AUTO_INCREMENT NOT NULL,
          title VARCHAR(255) NOT NULL,
          created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
          updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\',
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE chapter (
          id INT AUTO_INCREMENT NOT NULL,
          excursion_id INT DEFAULT NULL,
          title VARCHAR(255) NOT NULL,
          created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
          updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\',
          INDEX idx_excursion_id (excursion_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE play_list (
          id INT AUTO_INCREMENT NOT NULL,
          chapter_id INT DEFAULT NULL,
          title VARCHAR(255) NOT NULL,
          created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
          updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\',
          INDEX idx_chapter_id (chapter_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE track (
          id INT AUTO_INCREMENT NOT NULL,
          play_list_id INT DEFAULT NULL,
          title VARCHAR(255) NOT NULL,
          sort SMALLINT NOT NULL,
          created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
          updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\',
          INDEX idx_play_list_id (play_list_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE
          chapter
        ADD
          CONSTRAINT fk_excursion__excursion_id FOREIGN KEY (excursion_id) REFERENCES excursion (id)');

        $this->addSql('ALTER TABLE
          play_list
        ADD
          CONSTRAINT fk_chapter__chapter_id FOREIGN KEY (chapter_id) REFERENCES chapter (id)');

        $this->addSql('ALTER TABLE
          track
        ADD
          CONSTRAINT fk_play_list__play_list_id FOREIGN KEY (play_list_id) REFERENCES play_list (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE play_list DROP FOREIGN KEY fk_chapter__chapter_id');
        $this->addSql('ALTER TABLE chapter DROP FOREIGN KEY fk_excursion__excursion_id');
        $this->addSql('ALTER TABLE track DROP FOREIGN KEY fk_play_list__play_list_id');
        $this->addSql('DROP TABLE chapter');
        $this->addSql('DROP TABLE excursion');
        $this->addSql('DROP TABLE play_list');
        $this->addSql('DROP TABLE track');
    }
}
