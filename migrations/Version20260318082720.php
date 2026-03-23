<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260318082720 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rating (id INT AUTO_INCREMENT NOT NULL, score INT NOT NULL, comment LONGTEXT DEFAULT NULL, movie_id INT NOT NULL, movie_lover_id INT NOT NULL, INDEX IDX_D88926228F93B6FC (movie_id), INDEX IDX_D889262297F1AFC8 (movie_lover_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D88926228F93B6FC FOREIGN KEY (movie_id) REFERENCES movies (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D889262297F1AFC8 FOREIGN KEY (movie_lover_id) REFERENCES movie_lover (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D88926228F93B6FC');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D889262297F1AFC8');
        $this->addSql('DROP TABLE rating');
    }
}
