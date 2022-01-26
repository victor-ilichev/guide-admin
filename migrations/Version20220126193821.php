<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220126193821 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE excursion_chapter_sort (
          id INT AUTO_INCREMENT NOT NULL,
          excursion_id INT NOT NULL,
          chapter_id INT NOT NULL,
          sort SMALLINT NOT NULL,
          created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
          INDEX IDX_C0502E24AB4296F (excursion_id),
          INDEX IDX_C0502E2579F4768 (chapter_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE
          excursion_chapter_sort
        ADD
          CONSTRAINT FK_C0502E24AB4296F FOREIGN KEY (excursion_id) REFERENCES excursion (id)');
        $this->addSql('ALTER TABLE
          excursion_chapter_sort
        ADD
          CONSTRAINT FK_C0502E2579F4768 FOREIGN KEY (chapter_id) REFERENCES chapter (id)');
        $this->addSql('ALTER TABLE chapter DROP FOREIGN KEY fk_excursion__excursion_id');
        $this->addSql('DROP INDEX idx_excursion_id ON chapter');
        $this->addSql('ALTER TABLE chapter DROP excursion_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE excursion_chapter_sort');
        $this->addSql('ALTER TABLE chapter ADD excursion_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE
          chapter
        ADD
          CONSTRAINT fk_excursion__excursion_id FOREIGN KEY (excursion_id) REFERENCES excursion (id)');
        $this->addSql('CREATE INDEX idx_excursion_id ON chapter (excursion_id)');
    }
}
