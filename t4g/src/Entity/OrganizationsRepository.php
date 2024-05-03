<?php
namespace Baton\T4g\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
class OrganizationsRepository extends BaseRepository {
    public function getAll() {
        return $this->createQueryBuilder("o")
                    ->addSelect("eo")
                    ->innerJoin("o.eventsOrganizations", "eo")
                    ->innerJoin("eo.events", "e")
                    ->orderBy("o.organizationName, e.eventStartDate, e.eventEndDate", "ASC")
                    ->getQuery()->getResult();
    }

    public function getById(?int $organizationId) {
        $qb = $this->createQueryBuilder("o")
                   ->addSelect("eo")
                   ->innerJoin("o.eventsOrganizations", "eo");
       if (isset($organizationId)) {
            $qb = $qb->where("o.organizationId = :organizationId");
            $qb->setParameters(new ArrayCollection(array(new Parameter("organizationId", $organizationId))));
        }
        return $qb->getQuery()->getResult();
    }

}