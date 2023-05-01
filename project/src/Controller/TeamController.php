<?php

namespace App\Controller;

use App\Services\TeamService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Router;

class TeamController extends AbstractController
{
    /**
     * @var TeamService
     */
    private $teamService;

    public function __construct(TeamService $teamService)
    {
        $this->teamService = $teamService;
    }

    /**
     * @Router\Post("api/generate-team-tables")
     *
     * @return JsonResponse
     */
    public function generateTeamsTable(): JsonResponse
    {
        $response = $this->teamService->generateTeamsTable();
        return $this->json($response);
    }
}
