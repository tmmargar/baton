<?php
declare(strict_types=1);
namespace Baton\T4g\Entity;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Table(name: "baton_member_students")]
#[Entity(repositoryClass: MemberStudentsRepository::class)]
class MemberStudents
{
    #[Id]
    #[ManyToOne(targetEntity: Members::class, inversedBy: "members")]
    #[JoinColumn(name: "member_id", referencedColumnName:"member_id")]
    private Members $members;

    #[Id]
    #[ManyToOne(targetEntity: Students::class, inversedBy: "students")]
    #[JoinColumn(name: "student_id", referencedColumnName:"student_id")]
    private Students $students;

    /**
     * @return Members
     */
    public function getMembers(): Members {
        return $this->members;
    }

    /**
     * @param Members $members
     * @return self
     */
    public function setMembers(Members $members): self {
        $this->members = $members;
        return $this;
    }

    /**
     * @return Students
     */
    public function getStudents(): Students {
        return $this->students;
    }

    /**
     * @param Students $students
     * @return self
     */
    public function setStudents(Students $students): self {
        $this->students = $students;
        return $this;
    }

}