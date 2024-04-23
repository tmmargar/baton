<?php
namespace Baton\T4g\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;

class MemberStudentsRepository extends BaseRepository {
    public function getByMemberId(int $memberId) {
        $qb = $this->createQueryBuilder("sm")
                   ->innerJoin("sm.students", "s")
                   ->innerJoin("sm.members", "m")
                   ->where("m.memberId = :memberId")
                   ->setParameters(new ArrayCollection(array(new Parameter("memberId", $memberId))))
                   ->addOrderBy("s.studentLastName, s.studentFirstName", "ASC");
        return $qb->getQuery()->getResult();
    }

}