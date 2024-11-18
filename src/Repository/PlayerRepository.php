<?php

namespace App\Repository;

use App\Entity\Player;
use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Player>
 */
class PlayerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Player::class);
    }

    public function findAllPlayers(): array
    {
        return $this->findBy([], ['name' => 'ASC']);
    }

    public function findOneOrInsert(string $playerName, Team $team): Player
    {
        $player = $this->findOneBy(['name' => $playerName]);
        if (!$player) {
            $player = new Player();
            $player->setName($playerName);
            $player->setTeam($team);
            $this->getEntityManager()->persist($player);
            $this->getEntityManager()->flush();
            try {
                $this->getEntityManager()->refresh($player);
            } catch (ORMException $e) {
                dd($e->getMessage());
            }
        }
        return $player;
    }


}
