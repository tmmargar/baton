<?php
namespace Baton\T4g\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;

class TeamsRepository extends BaseRepository {
    public function getById(?int $teamId) {
        $qb = $this->createQueryBuilder("t");
        if (isset($teamId)) {
            $qb = $qb->where("t.teamId = :teamId");
            $qb->setParameters(new ArrayCollection(array(new Parameter("teamId", $teamId))));
        }
        return $qb->getQuery()->getResult();
    }

}