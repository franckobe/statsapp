<?php

namespace App\Controller;

use App\Repository\GameRepository;
use App\Repository\PlayerRepository;
use App\Repository\TeamRepository;
use App\Tool\GameFileTool;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class FileController extends AbstractController
{
    #[Route('/games/add', name: 'addGameFile', methods: ['POST'])]
    public function add(
        Request $request,
        TeamRepository $teamRepository,
        GameRepository $gameRepository,
        PlayerRepository $playerRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $files = $request->files->get('files');
        foreach ($files as $file) {
            $gameFileTool = new GameFileTool($file->getRealPath());

            $homeTeam = $teamRepository->findOneOrInsert($gameFileTool->getHomeTeam());
            $awayTeam = $teamRepository->findOneOrInsert($gameFileTool->getAwayTeam());
            $game = $gameRepository->findOneOrInsert($gameFileTool->getGameNumber(), $homeTeam, $awayTeam, $gameFileTool->getHomeScore(), $gameFileTool->getAwayScore());
            $teamByName = [
                $homeTeam->getName() => $homeTeam,
                $awayTeam->getName() => $awayTeam
            ];
            foreach ($gameFileTool->getPlayersGameStats() as $stats) {
                $player = $playerRepository->findOneOrInsert($stats->getPlayerName(), $teamByName[$stats->getTeamName()]);
                $gameStats = GameFileTool::convertGameStatObjectToEntity($game, $player, $stats);
                try {
                    $entityManager->persist($gameStats);
                    $entityManager->flush();
                } catch (ORMException $e) {
                    dd($e->getMessage());
                }
            }
        }

        return new JsonResponse([
            'status' => true,
            'message' => count($files) . ' fichier(s) intégré(s)'
        ]);
    }
}