<?php
namespace Baton\T4g\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
class EventTypesRepository extends BaseRepository {
    public function getById(?int $eventTypeId) {
        $qb = $this->createQueryBuilder("et")
                   ->addSelect("etc")
                   ->innerJoin("et.eventTypeCosts", "etc");
        if (isset($eventTypeId)) {
            $qb = $qb->where("et.eventTypeId = :eventTypeId");
            $qb->setParameters(new ArrayCollection(array(new Parameter("eventTypeId", $eventTypeId))));
        }
        $qb = $qb->addOrderBy("et.eventTypeName", "ASC")
                 ->addOrderBy("etc.eventTypeTimeLength", "DESC")
                 ->addOrderBy("etc.eventTypeStudentCount", "ASC");
        return $qb->getQuery()->getResult();
    }
}