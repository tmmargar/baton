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

#[Table(name: "baton_event_types")]
#[Entity(repositoryClass: EventTypesRepository::class)]
class EventTypes
{
    #[Column(name: "event_type_id", nullable: false)]
    #[Id]
    #[GeneratedValue(strategy: "CUSTOM")]
    #[CustomIdGenerator(class: EventTypesIdGenerator::class)]
    private int $eventTypeId;

    #[Column(name: "event_type_name", length: 30, nullable: false)]
    private string $eventTypeName;

    #[Column(name: "event_type_description", length: 400, nullable: false)]
    private string $eventTypeDescription;

    #[OneToMany(mappedBy: "eventType", targetEntity: Events::class)]
    #[JoinColumn(name: "event_type_id", referencedColumnName: "event_type_id")]
    private Collection $events;

    #[OneToMany(targetEntity: EventTypeCosts::class, mappedBy: "eventTypes")]
    #[JoinColumn(name: "event_type_id", referencedColumnName: "event_type_id")]
    private Collection $eventTypeCosts;

    public function __construct() {
        $this->events = new ArrayCollection();
        $this->eventTypeCosts = new ArrayCollection();
    }

    /**
     * @return number
     */
    public function getEventTypeId(): int {
        return $this->eventTypeId;
    }

    /**
     * @return string
     */
    public function getEventTypeName(): string {
        return $this->eventTypeName;
    }

    /**
     * @return string
     */
    public function getEventTypeDescription(): string {
        return $this->eventTypeDescription;
    }

    /**
     * @param number $eventTypeId
     * @return self
     */
    public function setEventTypeId(int $eventTypeId): self {
        $this->eventTypeId = $eventTypeId;
        return $this;
    }

    /**
     * @param string $eventTypeName
     * @return self
     */
    public function setEventTypeName(string $eventTypeName): self {
        $this->eventTypeName = $eventTypeName;
        return $this;
    }

    /**
     * @param string $eventTypeDescription
     * @return self
     */
    public function setEventTypeDescription(string $eventTypeDescription): self {
        $this->eventTypeDescription = $eventTypeDescription;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEvents(): Collection {
        return $this->events;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEventTypeCosts(): Collection {
        return $this->eventTypeCosts;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $events
     */
    public function setEvents(Collection $events): self {
        $this->events = $events;
        return $this;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $eventTypeCosts
     */
    public function setEventTypeCosts(Collection $eventTypeCosts): self {
        $this->eventTypeCosts = $eventTypeCosts;
        return $this;
    }
}