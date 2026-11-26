<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\CompetitionParticipationRepository;
use App\Service\PerformanceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig');
    }

    #[Route('/profil', name: 'app_profil')]
    #[IsGranted('ROLE_USER')]
    public function profil(
        PerformanceService $performanceService,
        CompetitionParticipationRepository $participationRepository,
    ): Response {
        /** @var User $sportif */
        $sportif = $this->getUser();

        return $this->render('main/profil.html.twig', [
            'sportif' => $sportif,
            'performance' => $performanceService->calculate($sportif),
            'participations' => $participationRepository->findForSportif($sportif),
        ]);
    }
}
