<?php

namespace App\Repository;

use App\Entity\CompetitionParticipation;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CompetitionParticipation>
 */
class CompetitionParticipationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompetitionParticipation::class);
    }

    /**
     * @return CompetitionParticipation[]
     */
    public function findForSportif(User $sportif): array
    {
        return $this->createQueryBuilder('participation')
            ->andWhere('participation.sportif = :sportif')
            ->setParameter('sportif', $sportif)
            ->leftJoin('participation.competition', 'competition')
            ->addSelect('competition')
            ->orderBy('competition.dateDebut', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
