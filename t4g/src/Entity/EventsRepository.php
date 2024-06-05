<?php
namespace Baton\T4g\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use DateTime;
class EventsRepository extends BaseRepository {
    public function getAllByDate() {
        return $this->createQueryBuilder(alias: "e")
                    ->addSelect(select: "et, eo")
                    ->innerJoin(join: "e.eventType", alias: "et")
                    ->leftJoin(join: "e.eventOrganizations", alias: "eo")
                    ->orderBy(sort: "e.eventStartDate, e.eventEndDate, e.eventName", order: "ASC")
                    ->getQuery()->getResult();
    }
    public function getAllByOrganization() {
        return $this->createQueryBuilder(alias: "e")
                    ->addSelect(select: "et, eo")
                    ->innerJoin(join: "e.eventType", alias: "et")
                    ->leftJoin(join: "e.eventOrganizations", alias: "eo")
                    ->orderBy(sort: "o.organizationName, e.eventStartDate, e.eventEndDate", order: "ASC")
                    ->getQuery()->getResult();
    }
    public function getByDate(?DateTime $eventDate, ?int $eventTypeId, ?int $timeLength, ?int $studentCount) {
        $qb = $this->createQueryBuilder(alias: "e")
                    ->addSelect(select: "et, eo, etc")
                    ->innerJoin(join: "e.eventType", alias: "et")
                    ->leftJoin(join: "e.eventOrganizations", alias: "eo")
                    ->join(join: "et.eventTypeCosts", alias: "etc");
        $params = array();
        $paramIndex = -1;
        if (isset($eventDate)) {
            $paramIndex++;
            $params[$paramIndex] = new Parameter(name: "eventDate", value: $eventDate);
            $qb = $qb->where(predicates: "e.eventStartDate <= :eventDate");
        }
        if (isset($eventTypeId)) {
            $paramIndex++;
            $params[$paramIndex] = new Parameter(name: "eventTypeId", value: $eventTypeId);
            if (isset($eventDate)) {
                $qb = $qb->andWhere("et.eventTypeId = :eventTypeId");
            } else {
                $qb = $qb->where(predicates: "et.eventTypeId = :eventTypeId");
            }
        }
        if (isset($timeLength)) {
            $paramIndex++;
            $params[$paramIndex] = new Parameter(name: "timeLength", value: $timeLength);
            if (isset($eventDate) || isset($eventTypeId)) {
                $qb = $qb->andWhere("etc.eventTypeTimeLength = :timeLength");
            } else {
                $qb = $qb->where(predicates: "etc.eventTypeTimeLength = :timeLength");
            }
        }
        if (isset($studentCount)) {
            $paramIndex++;
            $params[$paramIndex] = new Parameter(name: "studentCount", value: $studentCount);
            if (isset($eventDate) || isset($eventTypeId) || isset($timeLength)) {
                $qb = $qb->andWhere("etc.eventTypeStudentCount = :studentCount");
            } else {
                $qb = $qb->where(predicates: "etc.eventTypeStudentCount = :studentCount");
            }
        }
        return $qb->setParameters(parameters: new ArrayCollection(elements: $params))->getQuery()->getResult();
    }
    public function getById(?int $eventId) {
        $qb = $this->createQueryBuilder(alias: "e")
                   ->addSelect(select: "et, eo")
                   ->innerJoin(join: "e.eventType", alias: "et")
                   ->leftJoin(join: "e.eventOrganizations", alias: "eo");
        if (isset($eventId)) {
            $qb = $qb->where(predicates: "e.eventId = :eventId")
                     ->setParameters(parameters: new ArrayCollection(elements: array(new Parameter(name: "eventId", value: $eventId))));
        }
        return $qb->getQuery()->getResult();
    }
}