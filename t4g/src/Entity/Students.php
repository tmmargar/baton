<?php
declare(strict_types=1);
namespace Baton\T4g\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use DateTime;

#[Table(name: "baton_students")]
#[Entity(repositoryClass: StudentsRepository::class)]
class Students
{
    #[Column(name: "student_id", nullable: false)]
    #[Id]
    #[GeneratedValue(strategy: "CUSTOM")]
    #[CustomIdGenerator(class: StudentsIdGenerator::class)]
    private int $studentId;

    #[Column(name: "student_first_name", length: 30, nullable: false)]
    private string $studentFirstName;

    #[Column(name: "student_last_name", length: 30, nullable: false)]
    private string $studentLastName;

    #[Column(name: "student_email", length: 50, nullable: true)]
    private string $studentEmail;

    #[Column(name: "student_phone", type: "decimal", precision: 10, scale: 0, nullable: true)]
    private string $studentPhone; // per documentation must be string not float

    #[Column(name: "student_birthday", type: "date", nullable: true)]
    private ?DateTime $studentBirthday;

    #[Column(name: "student_registration_date", type: "date", nullable: false)]
    private DateTime $studentRegistrationDate;

    #[Column(name: "student_active_flag", length: 1, nullable: false)]
    private string $studentActiveFlag;

    #[OneToMany(mappedBy: "students", targetEntity: MemberStudents::class)]
    #[JoinColumn(name: "student_id", referencedColumnName: "student_id")]
    private Collection $memberStudents;

    #[OneToMany(mappedBy: "students", targetEntity: TeamStudents::class)]
    #[JoinColumn(name: "team_id", referencedColumnName: "team_id")]
    private Collection $teamStudents;

    #[OneToMany(mappedBy: "members", targetEntity: Invoices::class)]
    #[JoinColumn(name: "invoice_id", referencedColumnName: "invoice_id", nullable: false)]
    private Collection $invoices;

    public function __construct() {
        $this->memberStudents = new ArrayCollection();
        $this->teamStudents = new ArrayCollection();
        $this->invoices = new ArrayCollection();
    }

    /**
     * @return number
     */
    public function getStudentId(): int {
        return $this->studentId;
    }

    /**
     * @return string
     */
    public function getStudentFirstName(): string {
        return $this->studentFirstName;
    }

    /**
     * @return string
     */
    public function getStudentLastName(): string {
        return $this->studentLastName;
    }

    /**
     * @return string
     */
    public function getStudentEmail(): string {
        return $this->studentEmail;
    }

    /**
     * @return number
     */
    public function getStudentPhone(): string {
        return $this->studentPhone;
    }

    /**
     * @return DateTime
     */
    public function getStudentBirthday(): ?DateTime {
        return $this->studentBirthday;
    }

    /**
     * @return DateTime
     */
    public function getStudentRegistrationDate(): DateTime {
        return $this->studentRegistrationDate;
    }

    /**
     * @return string
     */
    public function getStudentActiveFlag(): string {
        return $this->studentActiveFlag;
    }

    /**
     * @return Collection
     */
    public function getMemberStudents(): Collection {
        return $this->memberStudents;
    }

    /**
     * @return Collection
     */
    public function getInvoices(): Collection {
        return $this->invoices;
    }

    /**
     * @param number $studentId
     */
    public function setStudentId(int $studentId): self {
        $this->studentId = $studentId;
        return $this;
    }

    /**
     * @param string $studentFirstName
     */
    public function setStudentFirstName(string $studentFirstName): self {
        $this->studentFirstName = $studentFirstName;
        return $this;
    }

    /**
     * @param string $studentLastName
     */
    public function setStudentLastName(string $studentLastName): self {
        $this->studentLastName = $studentLastName;
        return $this;
    }

    /**
     * @param string $studentEmail
     */
    public function setStudentEmail(string $studentEmail): self {
        $this->studentEmail = $studentEmail;
        return $this;
    }

    /**
     * @param number $studentPhone
     */
    public function setStudentPhone(string $studentPhone): self {
        $this->studentPhone = $studentPhone;
        return $this;
    }

    /**
     * @param DateTime $studentBirthday
     */
    public function setStudentBirthday(?DateTime $studentBirthday): self {
        $this->studentBirthday = $studentBirthday;
        return $this;
    }

    /**
     * @param DateTime $studentRegistrationDate
     */
    public function setStudentRegistrationDate(DateTime $studentRegistrationDate): self {
        $this->studentRegistrationDate = $studentRegistrationDate;
        return $this;
    }
    /**
     * @param string $studentActiveFlag
     */
    public function setStudentActiveFlag(string $studentActiveFlag): self {
        $this->studentActiveFlag = $studentActiveFlag;
        return $this;
    }

    /**
     * @param Collection $memberStudents
     * @return self
     */
    public function setMemberStudents(Collection $memberStudents): self {
        $this->memberStudents = $memberStudents;
        return $this;
    }

    /**
     * @param Collection $teamStudents
     * @return self
     */
    public function setTeamStudents(Collection $teamStudents): self {
        $this->teamStudents = $teamStudents;
        return $this;
    }

    /**
     * @param Collection $invoices
     * @return self
     */
    public function setInvoices(Collection $invoices): self {
        $this->invoices = $invoices;
        return $this;
    }

    /**
     * @return string
     */
    public function getStudentName(): string {
        return $this->studentFirstName . " " . $this->studentLastName;
    }

    /**
     * @return Collection
     */
    public function getTeamStudents(): Collection {
        return $this->teamsStudents;
    }
}