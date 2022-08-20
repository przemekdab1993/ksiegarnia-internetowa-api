<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220818171248 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book DROP CONSTRAINT fk_cbe5a33114d45bbe');
        $this->addSql('DROP INDEX idx_cbe5a33114d45bbe');
        $this->addSql('ALTER TABLE book ADD author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE book DROP autor_id');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A331F675F31B FOREIGN KEY (author_id) REFERENCES author (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_CBE5A331F675F31B ON book (author_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE book DROP CONSTRAINT FK_CBE5A331F675F31B');
        $this->addSql('DROP INDEX IDX_CBE5A331F675F31B');
        $this->addSql('ALTER TABLE book ADD autor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE book DROP author_id');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT fk_cbe5a33114d45bbe FOREIGN KEY (autor_id) REFERENCES author (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_cbe5a33114d45bbe ON book (autor_id)');
    }
}
