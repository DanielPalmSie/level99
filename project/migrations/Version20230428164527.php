<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230428164527 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, team1_id INT NOT NULL, team2_id INT NOT NULL, winner_id INT NOT NULL, division VARCHAR(255) NOT NULL, INDEX IDX_232B318CE72BCFA4 (team1_id), INDEX IDX_232B318CF59E604A (team2_id), INDEX IDX_232B318C5DFCD4B8 (winner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE playoffs (id INT AUTO_INCREMENT NOT NULL, team1_id INT NOT NULL, team2_id INT DEFAULT NULL, winner_id INT NOT NULL, round INT NOT NULL, INDEX IDX_67BA8A49E72BCFA4 (team1_id), INDEX IDX_67BA8A49F59E604A (team2_id), INDEX IDX_67BA8A495DFCD4B8 (winner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, division VARCHAR(255) NOT NULL, score INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CE72BCFA4 FOREIGN KEY (team1_id) REFERENCES team (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CF59E604A FOREIGN KEY (team2_id) REFERENCES team (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C5DFCD4B8 FOREIGN KEY (winner_id) REFERENCES team (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE playoffs ADD CONSTRAINT FK_67BA8A49E72BCFA4 FOREIGN KEY (team1_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE playoffs ADD CONSTRAINT FK_67BA8A49F59E604A FOREIGN KEY (team2_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE playoffs ADD CONSTRAINT FK_67BA8A495DFCD4B8 FOREIGN KEY (winner_id) REFERENCES team (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318CE72BCFA4');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318CF59E604A');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C5DFCD4B8');
        $this->addSql('ALTER TABLE playoffs DROP FOREIGN KEY FK_67BA8A49E72BCFA4');
        $this->addSql('ALTER TABLE playoffs DROP FOREIGN KEY FK_67BA8A49F59E604A');
        $this->addSql('ALTER TABLE playoffs DROP FOREIGN KEY FK_67BA8A495DFCD4B8');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE playoffs');
        $this->addSql('DROP TABLE team');
    }
}
