<?php
declare(strict_types = 1);
namespace Baton\T4g\Model;
use Baton\T4g\Entity\Members;
use Baton\T4g\Utility\DateTimeUtility;
use DateTime;
class Member extends Base {
    private string $firstName;
    private string $lastName;
    private Members $members;
    public function createFromEntity(bool $debug, Members $members): Member {
        $this->members = $members;
        return $this->create(debug: $debug, id: $members->getMemberId(), name: $members->getMemberName(), username: $members->getMemberUsername(), password: $members->getMemberPassword(), email: $members->getMemberEmail(), administrator: $members->getMemberAdministratorFlag(), registrationDate: $members->getMemberRegistrationDate(), approvalDate: $members->getMemberApprovalDate(), approvalUserid: $members->getMemberApproval()?->getMemberId(), approvalName: $members->getMemberApproval()?->getMemberName(), rejectionDate: $members->getMemberRejectionDate(), rejectionUserid: $members->getMemberRejection()?->getMemberId(), rejectionName: $members->getMemberRejection()?->getMemberName(), active: $members->getMemberActiveFlag());
    }
    public function __construct(protected bool $debug, protected string|int $id, protected string $name, protected ?string $username, protected ?string $password, protected ?string $email, protected string $administrator, protected ?DateTime $registrationDate, protected ?DateTime $approvalDate, protected ?int $approvalUserid, protected ?string $approvalName, protected ?DateTime $rejectionDate, protected ?int $rejectionUserid, protected ?string $rejectionName, protected string $active) {
        return $this->create(debug: $debug, id: $id, name: $name, username: $username, password: $password, email: $email, administrator: $administrator, registrationDate: $registrationDate, approvalDate: $approvalDate, approvalUserid: $approvalUserid, approvalName: $approvalName, rejectionDate: $rejectionDate, rejectionUserid: $rejectionUserid, rejectionName: $rejectionName, active: $active);
    }
    private function create(bool $debug, string|int $id, string $name, ?string $username, ?string $password, ?string $email, string $administrator, DateTime $registrationDate, ?DateTime $approvalDate, ?int $approvalUserid, ?string $approvalName, ?DateTime $rejectionDate, ?int $rejectionUserid, ?string $rejectionName, string $active): Member {
        parent::__construct(debug: $debug, id: $id);
        $nameFull = explode(separator: " ", string: $name);
        $this->setFirstName(firstName: $nameFull[0]);
        $this->setLastName(lastName: implode(separator: " ", array: array_slice(array: $nameFull, offset: 1)));
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->administrator = $administrator;
        $this->registrationDate = $registrationDate;
        $this->approvalDate = $approvalDate;
        $this->approvalUserid = $approvalUserid;
        $this->approvalName = $approvalName;
        $this->rejectionDate = $rejectionDate;
        $this->rejectionUserid = $rejectionUserid;
        $this->rejectionName = $rejectionName;
        $this->active = $active;
        return $this;
    }
    public function getFirstName(): string {
        return $this->firstName;
    }
    public function getLastName(): string {
        return $this->lastName;
    }
    public function getEmail(): ?string {
        return $this->email;
    }
    public function getUsername(): ?string {
        return $this->username;
    }
    public function getPassword(): ?string {
        return $this->password;
    }
    public function getAdministrator(): string {
        return $this->administrator;
    }
    public function getRegistrationDate(): ?DateTime {
        return $this->registrationDate;
    }
    public function getApprovalDate(): ?DateTime {
        return $this->approvalDate;
    }
    public function getApprovalUserid(): ?int {
        return $this->approvalUserid;
    }
    public function getApprovalName(): ?string {
        return $this->approvalName;
    }
    public function getRejectionDate(): ?DateTime {
        return $this->rejectionDate;
    }
    public function getRejectionUserid(): ?int {
        return $this->rejectionUserid;
    }
    public function getRejectionName(): ?string {
        return $this->rejectionName;
    }
    public function getActive(): string {
        return $this->active;
    }
    public function getName(): string {
        return $this->firstName . (isset($this->lastName) ? (" " . $this->lastName) : "");
    }
    public function getMembers(): Members {
        return $this->members;
    }
    public function setFirstName(string $firstName): Member {
        $this->firstName = $firstName;
        return $this;
    }
    public function setLastName(string $lastName): Member {
        $this->lastName = $lastName;
        return $this;
    }
    public function setEmail(string $email): Member {
        $this->email = $email;
        return $this;
    }
    public function setUsername(string $username): Member {
        $this->username = $username;
        return $this;
    }
    public function setPassword(string $password): Member {
        $this->password = $password;
        return $this;
    }
    public function setAdministrator(string $administrator): Member {
        $this->administrator = $administrator;
        return $this;
    }
    public function setRegistrationDate(DateTime $registrationDate): Member {
        $this->registrationDate = $registrationDate;
        return $this;
    }
    public function setApprovalDate(?DateTime $approvalDate): Member {
        $this->approvalDate = $approvalDate;
        return $this;
    }
    public function setApprovalUserid(?int $approvalUserid): Member {
        $this->approvalUserid = $approvalUserid;
        return $this;
    }
    public function setApprovalName(?string $approvalName): Member {
        $this->approvalName = $approvalName;
        return $this;
    }
    public function setRejectionDate(?DateTime $rejectionDate): Member {
        $this->rejectionDate = $rejectionDate;
        return $this;
    }
    public function setRejectionUserid(?int $rejectionUserid): Member {
        $this->rejectionUserid = $rejectionUserid;
        return $this;
    }
    public function setRejectionName(?string $rejectionName): Member {
        $this->rejectionName = $rejectionName;
        return $this;
    }
    public function setActive(string $active): Member {
        $this->active = $active;
        return $this;
    }
    public function setName(string $name): Member {
        $names = explode(separator: " ", string: $name);
        $this->firstName = $names[0];
        if (1 < count(value: $names)) {
            $this->lastName = implode(separator: " ", array: array_slice(array: $names, offset: 1));
        }
        return $this;
    }
    public function setMembers(Members $members): Member {
        $this->members = $members;
        return $this;
    }
    public function __toString(): string {
        $output = parent::__toString();
        $output .= ", firstName = '";
        $output .= $this->firstName;
        $output .= "', lastName = '";
        $output .= $this->lastName;
        $output .= "', email = '";
        $output .= $this->email;
        $output .= "', username = '";
        $output .= $this->username;
        $output .= "', password = '";
        $output .= $this->password;
        $output .= "', administrator = '";
        $output .= $this->administrator;
        $output .= "', registrationDate = '";
        $output .= NULL !== $this->registrationDate ? DateTimeUtility::formatDisplayDateTime(value: $this->registrationDate) : "";
        $output .= "', approvalDate = '";
        $output .= NULL !== $this->approvalDate ? DateTimeUtility::formatDisplayDateTime(value: $this->approvalDate) : "";
        $output .= "', approvalUserId = '";
        $output .= $this->approvalUserid;
        $output .= "', approvalName = '";
        $output .= $this->approvalName;
        $output .= "', rejectionDate = '";
        $output .= NULL !== $this->rejectionDate ? DateTimeUtility::formatDisplayDateTime(value: $this->rejectionDate) : "";
        $output .= "', rejectionUserId = '";
        $output .= $this->rejectionUserid;
        $output .= "', rejectionName = '";
        $output .= $this->rejectionName;
        $output .= "', active = '";
        $output .= $this->active;
        $output .= "'";
        return $output;
    }
}