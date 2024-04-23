<?php
namespace Baton\T4g\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
class EventsRepository extends BaseRepository {
    public function getAllByDate() {
        return $this->createQueryBuilder("e")
                    ->addSelect("et, eo")
                    ->innerJoin("e.eventType", "et")
                    ->leftJoin("e.eventOrganizations", "eo")
                    ->orderBy("e.eventStartDate, e.eventEndDate, e.eventName", "ASC")
                    ->getQuery()->getResult();
    }
    public function getAllByOrganization() {
        return $this->createQueryBuilder("e")
                    ->addSelect("et, eo")
                    ->innerJoin("e.eventType", "et")
                    ->leftJoin("e.eventOrganizations", "eo")
                    ->orderBy("o.organizationName, e.eventStartDate, e.eventEndDate", "ASC")
                    ->getQuery()->getResult();
    }
    public function getById(?int $eventId) {
        return $this->createQueryBuilder("e")
                    ->addSelect("et, eo")
                    ->innerJoin("e.eventType", "et")
                    ->leftJoin("e.eventOrganizations", "eo")
                    ->where("e.eventId = :eventId")
                    ->setParameters(new ArrayCollection(array(new Parameter("eventId", $eventId))))
                    ->getQuery()->getResult();
    }

}