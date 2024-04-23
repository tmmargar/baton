<?php
declare(strict_types=1);
namespace Baton\T4g\Entity;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Table(name: "baton_student_memberships")]
#[Entity(repositoryClass: StudentMembershipsRepository::class)]
class StudentMemberships
{
    #[Id]
    #[ManyToOne(targetEntity: Students::class, inversedBy: "students")]
    #[JoinColumn(name: "student_id", referencedColumnName: "student_id")]
    private Students $students;

    #[Id]
    #[ManyToOne(targetEntity: Organizations::class, inversedBy: "organizations")]
    #[JoinColumn(name: "organization_id", referencedColumnName: "organization_id")]
    private Organizations $organizations;

    #[Column(name: "student_membership_number", length: 12, nullable: false)]
    private string $studentMembershipNumber;

    /**
     * @return Students
     */
    public function getStudents(): Students {
        return $this->students;
    }

    /**
     * @return Organizations
     */
    public function getOrganizations(): Organizations {
        return $this->organizations;
    }

    /**
     * @return string
     */
    public function getStudentMembershipNumber(): string {
        return $this->studentMembershipNumber;
    }

    /**
     * @param Students $students
     * @return self
     */
    public function setStudents(Students $students): self {
        $this->students = $students;
        return $this;
    }

    /**
     * @param Organizations $organizations
     * @return self
     */
    public function setOrganizations(Organizations $organizations): self {
        $this->organizations = $organizations;
        return $this;
    }

    /**
     * @param string $studentMembershipNumber
     * @return self
     */
    public function setStudentMembershipNumber(string $studentMembershipNumber): self {
        $this->studentMembershipNumber = $studentMembershipNumber;
        return $this;
    }
}