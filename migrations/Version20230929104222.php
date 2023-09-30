<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230929104222 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE approve (id INT AUTO_INCREMENT NOT NULL, aplly_to_approve_id INT NOT NULL, UNIQUE INDEX UNIQ_845DDA8C1E051788 (aplly_to_approve_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE approve ADD CONSTRAINT FK_845DDA8C1E051788 FOREIGN KEY (aplly_to_approve_id) REFERENCES apply (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE approve DROP FOREIGN KEY FK_845DDA8C1E051788');
        $this->addSql('DROP TABLE approve');
    }
}
