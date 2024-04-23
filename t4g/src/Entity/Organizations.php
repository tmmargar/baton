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

#[Table(name: "baton_organizations")]
#[Entity(repositoryClass: OrganizationsRepository::class)]
class Organizations
{
    #[Column(name: "organization_id", nullable: false)]
    #[Id]
    #[GeneratedValue(strategy: "CUSTOM")]
    #[CustomIdGenerator(class: OrganizationsIdGenerator::class)]
    private int $organizationId;

    #[Column(name: "organization_name", length: 30, nullable: false)]
    private string $organizationName;

    #[Column(name: "organization_description", length: 200, nullable: false)]
    private string $organizationDescription;

    #[Column(name: "organization_url", length: 200, nullable: false)]
    private string $organizationUrl;

    #[OneToMany(mappedBy: "organizations", targetEntity: EventOrganizations::class)]
    #[JoinColumn(name: "organization_id", referencedColumnName: "organization_id")]
    private Collection $eventsOrganizations;

// tournament absence is tournament and player
// player
//     #[OneToMany(targetEntity: TournamentAbsences::class, mappedBy: "players")]
//     #[JoinColumn(name: "player_id", referencedColumnName: "player_id")]
//     private Collection $tournamentAbsences;
//tournament
//     #[OneToMany(targetEntity: TournamentAbsences::class, mappedBy: "tournaments")]
//     #[JoinColumn(name: "tournament_id", referencedColumnName: "tournament_id")]
//     private Collection $tournamentAbsences;

    public function __construct() {
        $this->eventsOrganizations = new ArrayCollection();
    }

    /**
     * @return number
     */
    public function getOrganizationId(): int {
        return $this->organizationId;
    }

    /**
     * @return string
     */
    public function getOrganizationName(): string {
        return $this->organizationName;
    }

    /**
     * @return string
     */
    public function getOrganizationDescription(): string {
        return $this->organizationDescription;
    }

    /**
     * @return string
     */
    public function getOrganizationUrl(): string {
        return $this->organizationUrl;
    }

    /**
     * @param number $organizationId
     */
    public function setOrganizationId(int $organizationId): self {
        $this->organizationId = $organizationId;
        return $this;
    }

    /**
     * @param string $organizationName
     */
    public function setOrganizationName(string $organizationName): self {
        $this->organizationName = $organizationName;
        return $this;
    }

    /**
     * @param string $organizationDescription
     */
    public function setOrganizationDescription(string $organizationDescription): self {
        $this->organizationDescription = $organizationDescription;
        return $this;
    }

    /**
     * @param string $organizationUrl
     */
    public function setOrganizationUrl(string $organizationUrl): self {
        $this->organizationUrl = $organizationUrl;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getEventOrganizations(): Collection {
        return $this->eventsOrganizations;
    }

    /**
     * @param Collection $eventsOrganizations
     * @return self
     */
    public function setEventOrganizations(Collection $eventsOrganizations): self {
        $this->eventsOrganizations = $eventsOrganizations;
        return $this;
    }

//     public function addEvent(Events $events): void {
//         $events->addOrganzations($this);
//         $this->events[] = $events;
//     }
}