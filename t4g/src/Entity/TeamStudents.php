<?php
declare(strict_types=1);
namespace Baton\T4g\Entity;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Table(name: "baton_team_students")]
#[Entity(repositoryClass: TeamStudentsRepository::class)]
class TeamStudents
{
    #[Id]
    #[ManyToOne(targetEntity: Teams::class, inversedBy: "teams")]
    #[JoinColumn(name: "team_id", referencedColumnName:"team_id")]
    private Teams $teams;

    #[Id]
    #[ManyToOne(targetEntity: Students::class, inversedBy: "students")]
    #[JoinColumn(name: "student_id", referencedColumnName:"student_id")]
    private Students $students;

    /**
     * @return Teams
     */
    public function getTeams(): Teams {
        return $this->teams;
    }

    /**
     * @param Teams $members
     * @return self
     */
    public function setTeams(Teams $teams): self {
        $this->teams = $teams;
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