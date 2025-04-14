<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250129201547 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game (id SERIAL NOT NULL, home_team_id INT NOT NULL, away_team_id INT DEFAULT NULL, number INT NOT NULL, phase INT NOT NULL, home_score INT NOT NULL, away_score INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_232B318C9C4C13F6 ON game (home_team_id)');
        $this->addSql('CREATE INDEX IDX_232B318C45185D02 ON game (away_team_id)');
        $this->addSql('CREATE TABLE game_stats (id SERIAL NOT NULL, player_id INT NOT NULL, game_id INT NOT NULL, minutes DOUBLE PRECISION NOT NULL, points INT NOT NULL, fgm2 INT NOT NULL, fga2 INT NOT NULL, fgm3 INT NOT NULL, fga3 INT NOT NULL, ftm INT NOT NULL, fta INT NOT NULL, offensive_rebound INT NOT NULL, defensive_rebound INT NOT NULL, foul INT NOT NULL, foul_provoked INT NOT NULL, steal INT NOT NULL, turnover INT NOT NULL, assist INT NOT NULL, block INT NOT NULL, block_received INT NOT NULL, efficiency INT NOT NULL, plus_minus INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_65741E2599E6F5DF ON game_stats (player_id)');
        $this->addSql('CREATE INDEX IDX_65741E25E48FD905 ON game_stats (game_id)');
        $this->addSql('CREATE TABLE player (id SERIAL NOT NULL, team_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_98197A65296CD8AE ON player (team_id)');
        $this->addSql('CREATE TABLE team (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C9C4C13F6 FOREIGN KEY (home_team_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C45185D02 FOREIGN KEY (away_team_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE game_stats ADD CONSTRAINT FK_65741E2599E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE game_stats ADD CONSTRAINT FK_65741E25E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A65296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE game DROP CONSTRAINT FK_232B318C9C4C13F6');
        $this->addSql('ALTER TABLE game DROP CONSTRAINT FK_232B318C45185D02');
        $this->addSql('ALTER TABLE game_stats DROP CONSTRAINT FK_65741E2599E6F5DF');
        $this->addSql('ALTER TABLE game_stats DROP CONSTRAINT FK_65741E25E48FD905');
        $this->addSql('ALTER TABLE player DROP CONSTRAINT FK_98197A65296CD8AE');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE game_stats');
        $this->addSql('DROP TABLE player');
        $this->addSql('DROP TABLE team');
    }
}
