<?php

namespace App\Controller;

use App\Entity\CompetitionParticipation;
use App\Entity\User;
use App\Form\CompetitionParticipationType;
use App\Repository\CompetitionParticipationRepository;
use App\Repository\UserRepository;
use App\Service\PerformanceService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/performance')]
#[IsGranted('ROLE_USER')]
final class PerformanceController extends AbstractController
{
    #[Route('', name: 'app_performance', methods: ['GET'])]
    public function index(
        PerformanceService $performanceService,
        CompetitionParticipationRepository $participationRepository,
        UserRepository $userRepository,
    ): Response {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if ($this->isGranted('ROLE_ANIMATEUR')) {
            $sportifs = $userRepository->findAll();
            $performances = [];

            foreach ($sportifs as $sportif) {
                if (in_array('ROLE_ANIMATEUR', $sportif->getRoles(), true)
                    || in_array('ROLE_ADMIN', $sportif->getRoles(), true)
                ) {
                    continue;
                }
                $performances[] = [
                    'sportif' => $sportif,
                    'details' => $performanceService->calculate($sportif),
                ];
            }

            return $this->render('performance/index.html.twig', [
                'performances' => $performances,
                'participations' => $participationRepository->findAll(),
            ]);
        }

        return $this->render('performance/show.html.twig', [
            'sportif' => $currentUser,
            'performance' => $performanceService->calculate($currentUser),
            'participations' => $participationRepository->findForSportif($currentUser),
        ]);
    }

    #[Route('/{id}', name: 'app_performance_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(
        User $sportif,
        PerformanceService $performanceService,
        CompetitionParticipationRepository $participationRepository,
    ): Response {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if (!$this->isGranted('ROLE_ANIMATEUR') && $currentUser !== $sportif) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('performance/show.html.twig', [
            'sportif' => $sportif,
            'performance' => $performanceService->calculate($sportif),
            'participations' => $participationRepository->findForSportif($sportif),
        ]);
    }

    #[Route('/participation/new', name: 'app_performance_participation_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ANIMATEUR')]
    public function newParticipation(
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response {
        $participation = new CompetitionParticipation();
        $form = $this->createForm(CompetitionParticipationType::class, $participation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($participation);
            $entityManager->flush();

            return $this->redirectToRoute('app_performance_show', ['id' => $participation->getSportif()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('performance/participation_form.html.twig', [
            'form' => $form,
            'participation' => $participation,
        ]);
    }

    #[Route('/participation/{id}/edit', name: 'app_performance_participation_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ANIMATEUR')]
    public function editParticipation(
        Request $request,
        CompetitionParticipation $participation,
        EntityManagerInterface $entityManager,
    ): Response {
        $form = $this->createForm(CompetitionParticipationType::class, $participation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_performance_show', ['id' => $participation->getSportif()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('performance/participation_form.html.twig', [
            'form' => $form,
            'participation' => $participation,
        ]);
    }
}
