<?php
namespace Baton\T4g\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
class OrganizationsRepository extends BaseRepository {
    public function getAll() {
        return $this->createQueryBuilder(alias: "o")
                    ->addSelect(select: "eo")
                    ->innerJoin(join: "o.eventsOrganizations", alias: "eo")
                    ->innerJoin(join: "eo.events", alias: "e")
                    ->orderBy(sort: "o.organizationName, e.eventStartDate, e.eventEndDate", order: "ASC")
                    ->getQuery()->getResult();
    }

    public function getById(?int $organizationId) {
        $qb = $this->createQueryBuilder(alias: "o")
                   ->addSelect(select: "eo")
                   ->innerJoin(join: "o.eventsOrganizations", alias: "eo");
       if (isset($organizationId)) {
            $qb = $qb->where(predicates: "o.organizationId = :organizationId");
            $qb->setParameters(parameters: new ArrayCollection(elements: array(new Parameter(name: "organizationId", value: $organizationId))));
        }
        return $qb->getQuery()->getResult();
    }

}