<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Game>
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function findOneOrInsert(int $gameNumber, Team $homeTeam, Team $awayTeam, int $homeScore, int $awayScore): Game
    {
        $game = $this->findOneBy(['number' => $gameNumber]);
        if (!$game) {
            $game = new Game();
            $game->setNumber($gameNumber);
            $game->setHomeTeam($homeTeam);
            $game->setAwayTeam($awayTeam);
            $game->setHomeScore($homeScore);
            $game->setAwayScore($awayScore);
            $this->getEntityManager()->persist($game);
            $this->getEntityManager()->flush();
            try {
                $this->getEntityManager()->refresh($game);
            } catch (ORMException $e) {
                dd($e->getMessage());
            }
        }
        return $game;
    }

    //    /**
    //     * @return Game[] Returns an array of Game objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('g.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Game
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
