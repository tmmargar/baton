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
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use DateTime;

#[Table(name: "baton_events")]
#[Entity(repositoryClass: EventsRepository::class)]
class Events
{
    #[Column(name: "event_id", nullable: false)]
    #[Id]
    #[GeneratedValue(strategy: "CUSTOM")]
    #[CustomIdGenerator(class: EventsIdGenerator::class)]
    private int $eventId;

    #[ManyToOne(targetEntity: EventTypes::class, inversedBy: "events")]
    #[JoinColumn(name: "event_type_id", referencedColumnName:"event_type_id", nullable: false)]
    private EventTypes $eventType;

    #[Column(name: "event_start_date", nullable: false)]
    private DateTime $eventStartDate;

    #[Column(name: "event_end_date", nullable: false)]
    private DateTime $eventEndDate;

    #[Column(name: "event_name", length: 30, nullable: false)]
    private string $eventName;

    #[Column(name: "event_description", length: 400, nullable: false)]
    private string $eventDescription;

    #[Column(name: "event_location", length: 400, nullable: false)]
    private string $eventLocation;

    #[Column(name: "event_url", length: 200, nullable: true)]
    private string $eventUrl;

    #[OneToMany(mappedBy: "events", targetEntity: EventOrganizations::class)]
    #[JoinColumn(name: "event_id", referencedColumnName: "event_id")]
    private Collection $eventOrganizations;

    public function __construct() {
        $this->eventOrganizations = new ArrayCollection();
    }

    /**
     * @return number
     */
    public function getEventId(): int {
        return $this->eventId;
    }

    /**
     * @return EventTypes
     */
    public function getEventType(): EventTypes {
        return $this->eventType;
    }

    /**
     * @return DateTime
     */
    public function getEventStartDate(): DateTime {
        return $this->eventStartDate;
    }

    /**
     * @return DateTime
     */
    public function getEventEndDate(): DateTime {
        return $this->eventEndDate;
    }

    /**
     * @return string
     */
    public function getEventName(): string {
        return $this->eventName;
    }

    /**
     * @return string
     */
    public function getEventDescription(): string {
        return $this->eventDescription;
    }
    /**
     * @return string
     */
    public function getEventLocation(): string {
        return $this->eventLocation;
    }

    /**
     * @return string
     */
    public function getEventUrl(): string {
        return $this->eventUrl;
    }

    /**
     * @param number $eventId
     * @return self
     */
    public function setEventId(int $eventId): self {
        $this->eventId = $eventId;
        return $this;
    }

    /**
     * @param EventTypes $eventType
     * @return self
     */
    public function setEventType(EventTypes $eventType): self {
        $this->eventType = $eventType;
        return $this;
    }

    /**
     * @param DateTime $eventStartDate
     * @return self
     */
    public function setEventStartDate(DateTime $eventStartDate): self {
        $this->eventStartDate = $eventStartDate;
        return $this;
    }

    /**
     * @param DateTime $eventEndDate
     * @return self
     */
    public function setEventEndDate(DateTime $eventEndDate): self {
        $this->eventEndDate = $eventEndDate;
        return $this;
    }

    /**
     * @param string $eventName
     * @return self
     */
    public function setEventName(string $eventName): self {
        $this->eventName = $eventName;
        return $this;
    }

    /**
     * @param string $eventDescription
     * @return self
     */
    public function setEventDescription(string $eventDescription): self {
        $this->eventDescription = $eventDescription;
        return $this;
    }

    /**
     * @param string $eventLocation
     * @return self
     */
    public function setEventLocation(string $eventLocation): self {
        $this->eventLocation = $eventLocation;
        return $this;
    }
    /**
     * @param string $eventUrl
     * @return self
     */
    public function setEventUrl(string $eventUrl): self {
        $this->eventUrl = $eventUrl;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getEventOrganizations(): Collection {
        return $this->eventOrganizations;
    }

    /**
     * @param Collection $eventsOrganizations
     * @return self
     */
    public function setEventOrganizations(Collection $eventOrganizations): self {
        $this->eventOrganizations = $eventOrganizations;
        return $this;
    }

//     public function addOrganzations(Organizations $organizations): void {
//         $this->organizations[] = $organizations;
//     }
}