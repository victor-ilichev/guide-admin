<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220113204452 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE excursion (
          id INT AUTO_INCREMENT NOT NULL,
          title VARCHAR(255) NOT NULL,
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE track (
          id INT AUTO_INCREMENT NOT NULL,
          excursion_id INT DEFAULT NULL,
          title VARCHAR(255) NOT NULL,
          sort SMALLINT NOT NULL,
          INDEX IDX_D6E3F8A64AB4296F (excursion_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE
          track
        ADD
          CONSTRAINT FK_D6E3F8A64AB4296F FOREIGN KEY (excursion_id) REFERENCES excursion (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE track DROP FOREIGN KEY FK_D6E3F8A64AB4296F');
        $this->addSql('DROP TABLE excursion');
        $this->addSql('DROP TABLE track');
    }
}
