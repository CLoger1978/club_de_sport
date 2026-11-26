<?php

namespace App\Entity;

use App\Repository\CompetitionParticipationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompetitionParticipationRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_SPORTIF_COMPETITION', fields: ['sportif', 'competition'])]
class CompetitionParticipation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'competitionsParticipations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $sportif = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Competition $competition = null;

    #[ORM\Column]
    private int $progression = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSportif(): ?User
    {
        return $this->sportif;
    }

    public function setSportif(?User $sportif): static
    {
        $this->sportif = $sportif;

        return $this;
    }

    public function getCompetition(): ?Competition
    {
        return $this->competition;
    }

    public function setCompetition(?Competition $competition): static
    {
        $this->competition = $competition;

        return $this;
    }

    public function getProgression(): int
    {
        return $this->progression;
    }

    public function setProgression(int $progression): static
    {
        $this->progression = max(0, min(100, $progression));

        return $this;
    }
}
