<?php
namespace Baton\T4g\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
class EventOrganizationsRepository extends BaseRepository {
//     public function getAllByDate() {
//         return $this->createQueryBuilder("eo")
//                     ->addSelect("eo, e, o")
//                     ->inerJoin("eo.events", "e")
//                     ->inerJoin("eo.organizations", "o")
//                     ->orderBy("e.eventDate, e.eventName", "ASC")
//                     ->getQuery()->getResult();
//     }
//     public function getAllByOrganization() {
//         return $this->createQueryBuilder("eo")
//                     ->addSelect("eo, e, o")
//                     ->inerJoin("eo.events", "e")
//                     ->inerJoin("eo.organizations", "o")
//                     ->orderBy("o.organizationName, e.eventDate", "ASC")
//                     ->getQuery()->getResult();
//     }
    public function getById(?int $eventId) {
        return $this->createQueryBuilder(alias: "eo")
                    ->addSelect(select: "eo, e, o")
                    ->inerJoin(join: "eo.events", alias: "e")
                    ->inerJoin(join: "eo.organizations", alias: "o")
                    ->where(predicates: "eo.eventId = :eventId")
                    ->setParameters(parameters: new ArrayCollection(elements: array(new Parameter(name: "eventId", value: $eventId))))
                    ->getQuery()->getResult();
    }

}