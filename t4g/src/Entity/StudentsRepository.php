<?php
namespace Baton\T4g\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;

class StudentsRepository extends BaseRepository {
    public function getById(?int $studentId) {
        $qb = $this->createQueryBuilder(alias: "s");
        if (isset($studentId)) {
            $qb = $qb->where(predicates: "s.studentId = :studentId");
            $qb->setParameters(parameters: new ArrayCollection(array(new Parameter("studentId", $studentId))));
        }
        $qb = $qb->addOrderBy("s.studentLastName, s.studentFirstName", "ASC");
        return $qb->getQuery()->getResult();
    }

    public function getByMemberId(int $memberId) {
        $qb = $this->createQueryBuilder(alias: "s")
                   ->innerJoin(join: "s.memberStudents", alias: "ms")
                   ->innerJoin(join: "ms.members", alias: "m")
                   ->where(predicates: "m.memberId = :memberId")
                   ->setParameters(parameters: new ArrayCollection(elements: array(new Parameter(name: "memberId", value: $memberId))))
                   ->addOrderBy(sort: "s.studentLastName, s.studentFirstName", order: "ASC");
        return $qb->getQuery()->getResult();
    }
}