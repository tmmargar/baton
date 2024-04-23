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
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use DateTime;

#[Table(name: "baton_members")]
#[Entity(repositoryClass: MembersRepository::class)]
class Members
{
    #[Column(name: "member_id", nullable: false)]
    #[Id]
    #[GeneratedValue(strategy: "CUSTOM")]
    #[CustomIdGenerator(class: MembersIdGenerator::class)]
    private int $memberId;

    #[Column(name: "member_first_name", length: 30, nullable: false)]
    private string $memberFirstName;

    #[Column(name: "member_last_name", length: 30, nullable: false)]
    private string $memberLastName;

    #[Column(name: "member_username", length: 30, nullable: false)]
    private string $memberUsername;

    #[Column(name: "member_password", length: 100, nullable: false)]
    private string $memberPassword;

    #[Column(name: "member_email", length: 50, nullable: false)]
    private string $memberEmail;

    #[Column(name: "member_phone", type: "decimal", precision: 10, scale: 0, nullable: false)]
    private string $memberPhone; // per documentation must be string not float

    #[Column(name: "member_administrator_flag", length: 1, nullable: false)]
    private string $memberAdministratorFlag;

    #[Column(name: "member_registration_date", type: "date", nullable: false)]
    private DateTime $memberRegistrationDate;

    #[Column(name: "member_approval_date", type: "date", nullable: true)]
    private ?DateTime $memberApprovalDate;

    #[ManyToOne(targetEntity: Members::class, inversedBy: "memberApproval")]
    #[JoinColumn(name: "member_approval_member_id", referencedColumnName: "member_id")]
    private ?Members $memberApproval;

    #[Column(name: "member_rejection_date", type: "date", nullable: true)]
    private ?DateTime $memberRejectionDate;

    #[ManyToOne(targetEntity: Members::class, inversedBy: "memberRejection")]
    #[JoinColumn(name: "member_rejection_member_id", referencedColumnName: "member_id")]
    private ?Members $memberRejection;

    #[Column(name: "member_active_flag", length: 1, nullable: false)]
    private string $memberActiveFlag;

    #[Column(name: "member_selector", length: 16, nullable: true)]
    private ?string $memberSelector;

    #[Column(name: "member_token", length: 64, nullable: true)]
    private ?string $memberToken;

    #[Column(name: "member_expires", type: "bigint", nullable: true)]
    private ?string $memberExpires;

    #[OneToMany(mappedBy: "members", targetEntity: MemberStudents::class)]
    #[JoinColumn(name: "member_id", referencedColumnName: "member_id", nullable: false)]
    private Collection $membersStudents;

    #[OneToMany(mappedBy: "members", targetEntity: Invoices::class)]
    #[JoinColumn(name: "invoice_id", referencedColumnName: "invoice_id", nullable: false)]
    private Collection $invoices;

    public function __construct() {
        $this->membersStudents = new ArrayCollection();
        $this->invoices = new ArrayCollection();
    }

    /**
     * @return number
     */
    public function getMemberId(): int {
        return $this->memberId;
    }

    /**
     * @return string
     */
    public function getMemberFirstName(): string {
        return $this->memberFirstName;
    }

    /**
     * @return string
     */
    public function getMemberLastName(): string {
        return $this->memberLastName;
    }

    /**
     * @return string
     */
    public function getMemberUsername(): string {
        return $this->memberUsername;
    }

    /**
     * @return string
     */
    public function getMemberPassword(): string {
        return $this->memberPassword;
    }

    /**
     * @return string
     */
    public function getMemberEmail(): string {
        return $this->memberEmail;
    }

    /**
     * @return number
     */
    public function getMemberPhone(): string {
        return $this->memberPhone;
    }

    /**
     * @return string
     */
    public function getMemberAdministratorFlag(): string {
        return $this->memberAdministratorFlag;
    }

    /**
     * @return DateTime
     */
    public function getMemberRegistrationDate(): DateTime {
        return $this->memberRegistrationDate;
    }

    /**
     * @return DateTime
     */
    public function getMemberApprovalDate(): ?DateTime {
        return $this->memberApprovalDate;
    }

    /**
     * @return Members|NULL
     */
    public function getMemberApproval(): ?Members {
        return $this->memberApproval;
    }

    /**
     * @return DateTime
     */
    public function getMemberRejectionDate(): ?DateTime {
        return $this->memberRejectionDate;
    }

    /**
     * @return Members|NULL
     */
    public function getMemberRejection(): ?Members {
        return $this->memberRejection;
    }

    /**
     * @return string
     */
    public function getMemberActiveFlag(): string {
        return $this->memberActiveFlag;
    }

    /**
     * @return string
     */
    public function getMemberSelector(): ?string {
        return $this->memberSelector;
    }

    /**
     * @return string
     */
    public function getMemberToken(): ?string {
        return $this->memberToken;
    }

    /**
     * @return number
     * @return self
     */
    public function getMemberExpires(): ?string {
        return $this->memberExpires;
    }

    /**
     * @param number $memberId
     * @return self
     */
    public function setMemberId(int $memberId): self {
        $this->memberId = $memberId;
        return $this;
    }

    /**
     * @param string $memberFirstName
     * @return self
     */
    public function setMemberFirstName(string $memberFirstName): self {
        $this->memberFirstName = $memberFirstName;
        return $this;
    }

    /**
     * @param string $memberLastName
     * @return self
     */
    public function setMemberLastName(string $memberLastName): self {
        $this->memberLastName = $memberLastName;
        return $this;
    }

    /**
     * @param string $memberUsername
     * @return self
     */
    public function setMemberUsername(string $memberUsername): self {
        $this->memberUsername = $memberUsername;
        return $this;
    }

    /**
     * @param string $memberPassword
     * @return self
     */
    public function setMemberPassword(string $memberPassword): self {
        $this->memberPassword = $memberPassword;
        return $this;
    }

    /**
     * @param string $memberEmail
     * @return self
     */
    public function setMemberEmail(string $memberEmail): self {
        $this->memberEmail = $memberEmail;
        return $this;
    }

    /**
     * @param number $memberPhone
     * @return self
     */
    public function setMemberPhone(string $memberPhone): self {
        $this->memberPhone = $memberPhone;
        return $this;
    }

    /**
     * @param string $memberAdministratorFlag
     * @return self
     */
    public function setMemberAdministratorFlag(string $memberAdministratorFlag): self {
        $this->memberAdministratorFlag = $memberAdministratorFlag;
        return $this;
    }

    /**
     * @param DateTime $memberRegistrationDate
     * @return self
     */
    public function setMemberRegistrationDate(DateTime $memberRegistrationDate): self {
        $this->memberRegistrationDate = $memberRegistrationDate;
        return $this;
    }

    /**
     * @param DateTime $memberApprovalDate
     * @return self
     */
    public function setMemberApprovalDate(?DateTime $memberApprovalDate): self {
        $this->memberApprovalDate = $memberApprovalDate;
        return $this;
    }

    /**
     * @param Members $memberApproval
     * @return self
     */
    public function setMemberApproval(?Members $memberApproval): self {
        $this->memberApproval = $memberApproval;
        return $this;
    }

    /**
     * @param DateTime $memberRejectionDate
     * @return self
     */
    public function setMemberRejectionDate(?DateTime $memberRejectionDate): self {
        $this->memberRejectionDate = $memberRejectionDate;
        return $this;
    }

    /**
     * @param Members $memberRejection
     * @return self
     */
    public function setMemberRejection(?Members $memberRejection): self {
        $this->memberRejection = $memberRejection;
        return $this;
    }
    /**
     * @param string $memberActiveFlag
     * @return self
     */
    public function setMemberActiveFlag(string $memberActiveFlag): self {
        $this->memberActiveFlag = $memberActiveFlag;
        return $this;
    }

    /**
     * @param Collection $membersStudents
     * @return self
     */
    public function setMemberStudents(Collection $membersStudents): self {
        $this->membersStudents = $membersStudents;
        return $this;
    }

    /**
     * @param string $memberSelector
     * @return self
     */
    public function setMemberSelector(?string $memberSelector): self {
        $this->memberSelector = $memberSelector;
        return $this;
    }

    /**
     * @param string $memberToken
     * @return self
     */
    public function setMemberToken(?string $memberToken): self {
        $this->memberToken = $memberToken;
        return $this;
    }

    /**
     * @param number $memberExpires
     * @return self
     */
    public function setMemberExpires(?string $memberExpires): self {
        $this->memberExpires = $memberExpires;
        return $this;
    }

    public function getMemberName(): string {
        return $this->memberFirstName . " " . $this->memberLastName;
    }

    /**
     * @return Collection
     */
    public function getMemberStudents(): Collection {
        return $this->membersStudents;
    }

    /**
     * @return Collection
     */
    public function getInvoices(): Collection {
        return $this->invoices;
    }

    /**
     * @param Collection $invoices
     * @return self
     */
    public function setInvoices(Collection $invoices): self {
        $this->invoices = $invoices;
        return $this;
    }
}