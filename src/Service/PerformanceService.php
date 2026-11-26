<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\CompetitionParticipationRepository;

final class PerformanceService
{
    public function __construct(
        private readonly CompetitionParticipationRepository $participationRepository,
    ) {
    }

    /**
     * @return array{
     *   indice: int,
     *   competences: int,
     *   objectifsAcquis: int,
     *   objectifsTotaux: int,
     *   progressionCompetitions: int,
     *   nombreCompetitions: int,
     *   participationScore: int
     * }
     */
    public function calculate(User $sportif): array
    {
        $objectifs = $sportif->getObjectifs();
        $objectifsTotaux = $objectifs->count();
        $objectifsAcquis = 0;

        foreach ($objectifs as $objectif) {
            $statut = mb_strtolower(trim($objectif->getStatut()), 'UTF-8');
            $statut = str_replace(['é', 'è', 'ê'], 'e', $statut);
            if (in_array($statut, ['acquis', 'acquise', 'valide', 'validee', 'termine', 'terminee', 'atteint', 'atteinte'], true)) {
                $objectifsAcquis++;
            }
        }

        $competences = $objectifsTotaux > 0
            ? (int) round(($objectifsAcquis / $objectifsTotaux) * 100)
            : 0;

        $participations = $this->participationRepository->findForSportif($sportif);
        $nombreCompetitions = count($participations);
        $progressionCompetitions = 0;

        if ($nombreCompetitions > 0) {
            $totalProgression = array_sum(array_map(
                static fn ($participation) => $participation->getProgression(),
                $participations,
            ));
            $progressionCompetitions = (int) round($totalProgression / $nombreCompetitions);
        }

        $participationScore = (int) round(min(100, ($nombreCompetitions / 10) * 100));
        $indice = (int) round(
            ($competences * 0.50)
            + ($progressionCompetitions * 0.30)
            + ($participationScore * 0.20)
        );

        return [
            'indice' => $indice,
            'competences' => $competences,
            'objectifsAcquis' => $objectifsAcquis,
            'objectifsTotaux' => $objectifsTotaux,
            'progressionCompetitions' => $progressionCompetitions,
            'nombreCompetitions' => $nombreCompetitions,
            'participationScore' => $participationScore,
        ];
    }
}
