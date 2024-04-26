<?php
namespace Baton\T4g\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;

class EventTypeCostsRepository extends BaseRepository {
    public function getById(?EventTypes $eventType, ?int $eventTypeTimeLength, ?int $eventTypeStudentCount) {
        $qb = $this->createQueryBuilder("etc")
                   ->innerJoin("etc.eventTypes", "et");
        $params = array();
        $index = 0;
        if (isset($eventType)) {
            $params[$index] = new Parameter("eventTypeId", $eventType);
            $index++;
            $qb = $qb->where("et.eventTypeId = :eventTypeId");
        }
        if (isset($eventTypeTimeLength) && 0 != $eventTypeTimeLength) {
            $params[$index] = new Parameter("eventTypeTimeLength", $eventTypeTimeLength);
            $index++;
            if (isset($eventType)) {
                $qb = $qb->andWhere("etc.eventTypeTimeLength = :eventTypeTimeLength");
            } else {
                $qb = $qb->where("etc.eventTypeTimeLength = :eventTypeTimeLength");
            }
        }
        if (isset($eventTypeStudentCount) && 0 != $eventTypeStudentCount) {
            $params[$index] = new Parameter("eventTypeStudentCount", $eventTypeStudentCount);
            $index++;
            if (isset($eventType) || isset($eventTypeTimeLength)) {
                $qb = $qb->andWhere("etc.eventTypeStudentCount = :eventTypeStudentCount");
            } else {
                $qb = $qb->where("etc.eventTypeStudentCount = :eventTypeStudentCount");
            }
        }
        return $qb->setParameters(new ArrayCollection($params))
                  ->getQuery()->getResult();
    }

}