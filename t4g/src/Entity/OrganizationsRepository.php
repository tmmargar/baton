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
        return $this->createQueryBuilder("o")
                    ->addSelect("eo")
                    ->innerJoin("o.eventsOrganizations", "eo")
                    ->where("o.organizationId = :organizationId")
                    ->setParameters(new ArrayCollection(array(new Parameter("organizationId", $organizationId))))
                    ->getQuery()->getSingleResult();
    }

}