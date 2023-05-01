<?php

namespace App\Controller;

use App\Services\PlayoffService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\Annotations as Router;

class PlayoffController extends AbstractController
{
    /**
     * @var PlayoffService
     */
    private $playoffService;

    public function __construct(PlayoffService $playoffService)
    {
        $this->playoffService = $playoffService;
    }

    /**
     * @Router\Post("api/generate-playoff-tables")
     *
     * @return JsonResponse
     */
    public function generateAllPlayoffGamesAction(): JsonResponse
    {
        $this->playoffService->generateFirstRoundPlayoffs();
        $response = $this->playoffService->generateRestGamesOfPlayoffs();
        return $this->json($response);
    }
}
