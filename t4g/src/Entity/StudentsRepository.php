<?php
namespace Baton\T4g\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;

class StudentsRepository extends BaseRepository {
    public function getById(?int $studentId) {
        $qb = $this->createQueryBuilder("s");
        if (isset($studentId)) {
            $qb = $qb->where("s.studentId = :studentId");
            $qb->setParameters(new ArrayCollection(array(new Parameter("studentId", $studentId))));
        }
        $qb = $qb->addOrderBy("s.studentLastName, s.studentFirstName", "ASC");
        return $qb->getQuery()->getResult();
    }

    public function getByMemberId(int $memberId) {
        $qb = $this->createQueryBuilder("s")
                   ->innerJoin("s.memberStudents", "ms")
                   ->innerJoin("ms.members", "m")
                   ->where("m.memberId = :memberId")
                   ->setParameters(new ArrayCollection(array(new Parameter("memberId", $memberId))))
                   ->addOrderBy("s.studentLastName, s.studentFirstName", "ASC");
        return $qb->getQuery()->getResult();
    }
}