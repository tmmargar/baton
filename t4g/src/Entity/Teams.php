<?php
declare(strict_types=1);
namespace Baton\T4g\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Table(name: "baton_teams")]
#[Entity(repositoryClass: TeamsRepository::class)]
class Teams
{
    #[Column(name: "team_id", nullable: false)]
    #[Id]
    #[GeneratedValue(strategy: "CUSTOM")]
    #[CustomIdGenerator(class: TeamsIdGenerator::class)]
    private int $teamId;

    #[Column(name: "team_name", length: 30, nullable: false)]
    private string $teamName;

    #[Column(name: "team_description", length: 200, nullable: false)]
    private string $teamDescription;

    #[OneToMany(mappedBy: "teams", targetEntity: TeamStudents::class)]
    #[JoinColumn(name: "team_id", referencedColumnName: "team_id")]
    private Collection $teamStudents;

    public function __construct() {
        $this->teamStudents = new ArrayCollection();
    }
    /**
     * @return number
     */
    public function getTeamId(): int {
        return $this->teamId;
    }

    /**
     * @return string
     */
    public function getTeamName(): string {
        return $this->teamName;
    }

    /**
     * @return string
     */
    public function getTeamDescription(): string {
        return $this->teamDescription;
    }

    /**
     * @param number $teamId
     */
    public function setTeamId(int $teamId): self {
        $this->teamId = $teamId;
        return $this;
    }

    /**
     * @param string $teamName
     */
    public function setTeamName(string $teamName): self {
        $this->teamName = $teamName;
        return $this;
    }

    /**
     * @param string $teamDescription
     */
    public function setTeamDescription(string $teamDescription): self {
        $this->teamDescription = $teamDescription;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getTeamStudents(): Collection {
        return $this->teamStudents;
    }

    /**
     * @param Collection $teamStudents
     * @return self
     */
    public function setTeamStudents(Collection $teamStudents): self {
        $this->teamStudents = $teamStudents;
        return $this;
    }
}