<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220126210332 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chapter_play_list_sort (
          id INT AUTO_INCREMENT NOT NULL,
          chapter_pl_id INT NOT NULL,
          play_list_id INT NOT NULL,
          sort SMALLINT NOT NULL,
          created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
          INDEX IDX_92E897DE5749D5F4 (chapter_pl_id),
          INDEX IDX_92E897DE4BB0713B (play_list_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE
          chapter_play_list_sort
        ADD
          CONSTRAINT FK_92E897DE5749D5F4 FOREIGN KEY (chapter_pl_id) REFERENCES chapter (id)');
        $this->addSql('ALTER TABLE
          chapter_play_list_sort
        ADD
          CONSTRAINT FK_92E897DE4BB0713B FOREIGN KEY (play_list_id) REFERENCES play_list (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE chapter_play_list_sort');
    }
}
