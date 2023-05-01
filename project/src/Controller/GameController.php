<?php

namespace App\Controller;

use App\Services\GameService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Router;
use Symfony\Component\HttpFoundation\Response;

class GameController extends AbstractController
{
    private $gameService;

    public function __construct(GameService $gameService)
    {
        $this->gameService = $gameService;
    }

    /**
     * @Router\Post("api/generate-games-tables")
     *
     * @return Response
     */
    public function generateAllGamesAction(): Response
    {
        $this->gameService->generateGames();
        $gameResults = $this->gameService->getAllGamesByDivision();

        return $this->json($gameResults);
    }
}
