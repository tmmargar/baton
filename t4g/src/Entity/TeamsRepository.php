<?php
namespace Baton\T4g\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;

class TeamsRepository extends BaseRepository {
    public function getById(?int $teamId) {
        $qb = $this->createQueryBuilder(alias: "t");
        if (isset($teamId)) {
            $qb = $qb->where(predicates: "t.teamId = :teamId");
            $qb->setParameters(parameters: new ArrayCollection(elements: array(new Parameter(name: "teamId", value: $teamId))));
        }
        return $qb->getQuery()->getResult();
    }

}