<?php
declare(strict_types=1);
namespace Baton\T4g\Entity;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Table(name: "baton_event_organizations")]
#[Entity(repositoryClass: EventOrganizationsRepository::class)]
class EventOrganizations
{
    #[Id]
    #[ManyToOne(targetEntity: Events::class, inversedBy: "events")]
    #[JoinColumn(name: "event_id", referencedColumnName:"event_id")]
    private Events $events;

    #[Id]
    #[ManyToOne(targetEntity: Organizations::class, inversedBy: "organizations", fetch: "EAGER")]
    #[JoinColumn(name: "organization_id", referencedColumnName:"organization_id")]
    private Organizations $organizations;

    #[Column(name: "url", length: 200, nullable: true)]
    private string $url;

    /**
     * @return Events
     */
    public function getEvents(): Events {
        return $this->events;
    }

    /**
     * @param Events $events
     * @return self
     */
    public function setEvents(Events $events): self {
        $this->events = $events;
        return $this;
    }

    /**
     * @return Organizations
     */
    public function getOrganizations(): Organizations {
        return $this->organizations;
    }

    /**
     * @param Organizations $organizations
     * @return self
     */
    public function setOrganizations(Organizations $organizations): self {
        $this->organizations = $organizations;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string {
        return $this->url;
    }

    /**
     * @param string $url
     * @return self
     */
    public function setUrl(string $url): self {
        $this->url = $url;
        return $this;
    }
}