<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241116003311 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__game_stats AS SELECT id, player_id, game_id, minutes, points, fgm2, fga2, fgm3, fga3, ftm, fta, offensive_rebound, defensive_rebound, foul, foul_provoked, steal, turnover, assist, block, block_received, efficiency, plus_minus FROM game_stats');
        $this->addSql('DROP TABLE game_stats');
        $this->addSql('CREATE TABLE game_stats (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, player_id INTEGER NOT NULL, game_id INTEGER NOT NULL, minutes DOUBLE PRECISION NOT NULL, points INTEGER NOT NULL, fgm2 INTEGER NOT NULL, fga2 INTEGER NOT NULL, fgm3 INTEGER NOT NULL, fga3 INTEGER NOT NULL, ftm INTEGER NOT NULL, fta INTEGER NOT NULL, offensive_rebound INTEGER NOT NULL, defensive_rebound INTEGER NOT NULL, foul INTEGER NOT NULL, foul_provoked INTEGER NOT NULL, steal INTEGER NOT NULL, turnover INTEGER NOT NULL, assist INTEGER NOT NULL, block INTEGER NOT NULL, block_received INTEGER NOT NULL, efficiency INTEGER NOT NULL, plus_minus INTEGER NOT NULL, CONSTRAINT FK_65741E2599E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_65741E25E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO game_stats (id, player_id, game_id, minutes, points, fgm2, fga2, fgm3, fga3, ftm, fta, offensive_rebound, defensive_rebound, foul, foul_provoked, steal, turnover, assist, block, block_received, efficiency, plus_minus) SELECT id, player_id, game_id, minutes, points, fgm2, fga2, fgm3, fga3, ftm, fta, offensive_rebound, defensive_rebound, foul, foul_provoked, steal, turnover, assist, block, block_received, efficiency, plus_minus FROM __temp__game_stats');
        $this->addSql('DROP TABLE __temp__game_stats');
        $this->addSql('CREATE INDEX IDX_65741E25E48FD905 ON game_stats (game_id)');
        $this->addSql('CREATE INDEX IDX_65741E2599E6F5DF ON game_stats (player_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__game_stats AS SELECT id, player_id, game_id, minutes, points, fgm2, fga2, fgm3, fga3, ftm, fta, offensive_rebound, defensive_rebound, foul, foul_provoked, steal, turnover, assist, block, block_received, efficiency, plus_minus FROM game_stats');
        $this->addSql('DROP TABLE game_stats');
        $this->addSql('CREATE TABLE game_stats (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, player_id INTEGER NOT NULL, game_id INTEGER NOT NULL, minutes INTEGER NOT NULL, points INTEGER NOT NULL, fgm2 INTEGER NOT NULL, fga2 INTEGER NOT NULL, fgm3 INTEGER NOT NULL, fga3 INTEGER NOT NULL, ftm INTEGER NOT NULL, fta INTEGER NOT NULL, offensive_rebound INTEGER NOT NULL, defensive_rebound INTEGER NOT NULL, foul INTEGER NOT NULL, foul_provoked INTEGER NOT NULL, steal INTEGER NOT NULL, turnover INTEGER NOT NULL, assist INTEGER NOT NULL, block INTEGER NOT NULL, block_received INTEGER NOT NULL, efficiency INTEGER NOT NULL, plus_minus INTEGER NOT NULL, CONSTRAINT FK_65741E2599E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_65741E25E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO game_stats (id, player_id, game_id, minutes, points, fgm2, fga2, fgm3, fga3, ftm, fta, offensive_rebound, defensive_rebound, foul, foul_provoked, steal, turnover, assist, block, block_received, efficiency, plus_minus) SELECT id, player_id, game_id, minutes, points, fgm2, fga2, fgm3, fga3, ftm, fta, offensive_rebound, defensive_rebound, foul, foul_provoked, steal, turnover, assist, block, block_received, efficiency, plus_minus FROM __temp__game_stats');
        $this->addSql('DROP TABLE __temp__game_stats');
        $this->addSql('CREATE INDEX IDX_65741E2599E6F5DF ON game_stats (player_id)');
        $this->addSql('CREATE INDEX IDX_65741E25E48FD905 ON game_stats (game_id)');
    }
}
