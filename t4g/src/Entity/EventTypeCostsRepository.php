<?php
namespace Baton\T4g\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;

class EventTypeCostsRepository extends BaseRepository {
    public function getById(?EventTypes $eventType, ?int $eventTypeTimeLength, ?int $eventTypeStudentCount) {
        $qb = $this->createQueryBuilder(alias: "etc")
                   ->innerJoin(join: "etc.eventTypes", alias: "et");
        $params = array();
        $index = 0;
        if (isset($eventType)) {
            $params[$index] = new Parameter(name: "eventTypeId", value: $eventType);
            $index++;
            $qb = $qb->where(predicates: "et.eventTypeId = :eventTypeId");
        }
        if (isset($eventTypeTimeLength) && 0 != $eventTypeTimeLength) {
            $params[$index] = new Parameter(name: "eventTypeTimeLength", value: $eventTypeTimeLength);
            $index++;
            if (isset($eventType)) {
                $qb = $qb->andWhere("etc.eventTypeTimeLength = :eventTypeTimeLength");
            } else {
                $qb = $qb->where(predicates: "etc.eventTypeTimeLength = :eventTypeTimeLength");
            }
        }
        if (isset($eventTypeStudentCount) && 0 != $eventTypeStudentCount) {
            $params[$index] = new Parameter(name: "eventTypeStudentCount", value: $eventTypeStudentCount);
            $index++;
            if (isset($eventType) || isset($eventTypeTimeLength)) {
                $qb = $qb->andWhere("etc.eventTypeStudentCount = :eventTypeStudentCount");
            } else {
                $qb = $qb->where(predicates: "etc.eventTypeStudentCount = :eventTypeStudentCount");
            }
        }
        return $qb->setParameters(parameters: new ArrayCollection(elements: $params))->getQuery()->getResult();
    }

}