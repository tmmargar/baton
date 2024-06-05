<?php
namespace Baton\T4g\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
class EventTypesRepository extends BaseRepository {
    public function getById(?int $eventTypeId) {
        $qb = $this->createQueryBuilder(alias: "et")
                   ->addSelect(select: "etc")
                   ->innerJoin(join: "et.eventTypeCosts", alias: "etc");
        if (isset($eventTypeId)) {
            $qb = $qb->where(predicates: "et.eventTypeId = :eventTypeId");
            $qb->setParameters(parameters: new ArrayCollection(elements: array(new Parameter(name: "eventTypeId", value: $eventTypeId))));
        }
        $qb = $qb->addOrderBy(sort: "et.eventTypeName", order: "ASC")
                 ->addOrderBy(sort: "etc.eventTypeTimeLength", order: "DESC")
                 ->addOrderBy(sort: "etc.eventTypeStudentCount", order: "ASC");
        return $qb->getQuery()->getResult();
    }
}