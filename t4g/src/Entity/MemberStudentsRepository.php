<?php
namespace Baton\T4g\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;

class MemberStudentsRepository extends BaseRepository {
    public function getByMemberId(int $memberId) {
        $qb = $this->createQueryBuilder(alias: "sm")
                   ->innerJoin(join: "sm.students", alias: "s")
                   ->innerJoin(join: "sm.members", alias: "m")
                   ->where(predicates: "m.memberId = :memberId")
                   ->setParameters(parameters: new ArrayCollection(elements: array(new Parameter(name: "memberId", value: $memberId))))
                   ->addOrderBy(sort: "s.studentLastName, s.studentFirstName", order: "ASC");
        return $qb->getQuery()->getResult();
    }

}