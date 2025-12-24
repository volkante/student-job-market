<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251223202838 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE job ADD COLUMN description CLOB DEFAULT NULL');
        $this->addSql('ALTER TABLE job ADD COLUMN requirements CLOB DEFAULT NULL');
        $this->addSql('ALTER TABLE job ADD COLUMN benefits CLOB DEFAULT NULL');

        // Update existing records with proper descriptions
        $this->addSql('UPDATE job SET description = \'Exciting opportunity for a \' || title || \' position at \' || company || \'. More details available upon application.\' WHERE description IS NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__job AS SELECT id, title, company, location, salary, start_date, employment_type, field, email, status, created_at FROM job');
        $this->addSql('DROP TABLE job');
        $this->addSql('CREATE TABLE job (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, company VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, salary VARCHAR(500) DEFAULT NULL, start_date DATE DEFAULT NULL, employment_type VARCHAR(50) NOT NULL, field VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, status VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL)');
        $this->addSql('INSERT INTO job (id, title, company, location, salary, start_date, employment_type, field, email, status, created_at) SELECT id, title, company, location, salary, start_date, employment_type, field, email, status, created_at FROM __temp__job');
        $this->addSql('DROP TABLE __temp__job');
    }
}
