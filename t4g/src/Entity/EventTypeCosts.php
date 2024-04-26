<?php
declare(strict_types=1);
namespace Baton\T4g\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Table(name: "baton_event_type_costs")]
#[Entity(repositoryClass: EventTypeCostsRepository::class)]
class EventTypeCosts
{
    #[Id]
    #[ManyToOne(targetEntity: EventTypes::class, inversedBy: "eventTypes")]
    #[JoinColumn(name: "event_type_id", referencedColumnName: "event_type_id")]
    private EventTypes $eventTypes;

    #[Column(name: "event_type_time_length", precision: 5, scale: 0, nullable: false)]
    #[Id]
    private int $eventTypeTimeLength;

    #[Column(name: "event_type_student_count", precision: 3, scale: 0, nullable: false)]
    #[Id]
    private int $eventTypeStudentCount;

    #[Column(name: "event_type_cost", precision: 4, scale: 2, nullable: false)]
    private float $eventTypeCost;

    #[OneToMany(mappedBy: "eventType", targetEntity: Events::class)]
    #[JoinColumn(name: "event_type_id", referencedColumnName: "event_type_id")]
    private Collection $events;

    public function __construct() {
        $this->events = new ArrayCollection();
    }

    /**
     * @return EventTypes
     */
    public function getEventTypes(): EventTypes {
        return $this->eventTypes;
    }

    /**
     * @return int
     */
    public function getEventTypeTimeLength(): int {
        return $this->eventTypeTimeLength;
    }

    /**
     * @return int
     */
    public function getEventTypeStudentCount(): int {
        return $this->eventTypeStudentCount;
    }

    /**
     * @return float
     */
    public function getEventTypeCost(): float {
        return $this->eventTypeCost;
    }

    /**
     * @param EventTypes $eventTypes
     * @return self
     */
    public function setEventTypes(EventTypes $eventTypes): self {
        $this->eventTypes = $eventTypes;
        return $this;
    }

    /**
     * @param int $eventTypeTimeLength
     * @return self
     */
    public function setEventTypeTimeLength(int $eventTypeTimeLength): self {
        $this->eventTypeTimeLength = $eventTypeTimeLength;
        return $this;
    }

    /**
     * @param int $eventTypeStudentCount
     * @return self
     */
    public function setEventTypeStudentCount(int $eventTypeStudentCount): self {
        $this->eventTypeStudentCount = $eventTypeStudentCount;
        return $this;
    }

    /**
     * @param float $eventTypeCost
     * @return self
     */
    public function setEventTypeCost(float $eventTypeCost): self {
        $this->eventTypeCost = $eventTypeCost;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEvents(): Collection {
        return $this->events;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $events
     */
    public function setEvents(Collection $events): self {
        $this->events = $events;
        return $this;
    }
}