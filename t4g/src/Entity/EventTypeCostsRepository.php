<?php
namespace Baton\T4g\Entity;
class EventTypeCostsRepository extends BaseRepository {
//     public function getAllByDate() {
//         return $this->createQueryBuilder("e")
//                     ->addSelect("eo")
//                     ->innerJoin("e.eventsOrganizations", "eo")
//                     ->orderBy("e.eventDate, e.eventName", "ASC")
//                     ->getQuery()->getResult();
//     }
//     public function getAllByOrganization() {
//         return $this->createQueryBuilder("e")
//                     ->addSelect("eo")
//                     ->innerJoin("e.eventsOrganizations", "eo")
//                     ->innerJoin("eo.organizations", "o")
//                     ->orderBy("o.organizationName, e.eventDate", "ASC")
//                     ->getQuery()->getResult();
//     }
//     public function getById(?int $eventId) {
//         return $this->createQueryBuilder("e")
//                     ->addSelect("eo, o")
//                     ->innerJoin("e.eventsOrganizations", "eo")
//                     ->innerJoin("eo.organizations", "o")
//                     ->where("e.eventId = :eventId")
//                     ->setParameters(new ArrayCollection(array(new Parameter("eventId", $eventId))))
//                     ->getQuery()->getResult();
//     }

}