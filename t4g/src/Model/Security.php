<?php
declare(strict_types = 1);
namespace Baton\T4g\Model;
use Baton\T4g\Entity\Members;
use Baton\T4g\Utility\SessionUtility;
class Security extends Base
{
    private Members $members;

    /**
     * @param bool $debug
     * @param string|int|NULL $id
     * @param string $username
     * @param string $password
     */
    public function __construct(protected bool $debug, protected string|int|NULL $id, protected string $username, protected string $password) {
        parent::__construct(debug: $debug, id: $id);
    }

    /**
     * @return string
     */
    public function getUsername(): string {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword(): string {
        return $this->password;
    }

    /**
     * @return bool
     */
    public function login(): bool {
        if ($this->validatePassword()) {
            $this->loginSuccess();
            return true;
        } else {
            return false;
        }
    }

    private function loginSuccess() {
        $entityManager = getEntityManager();
        $member = $entityManager->getRepository(Constant::ENTITY_MEMBERS)->getByUsername(username: $this->getUsername());
        SessionUtility::setValue(name: SessionUtility::OBJECT_NAME_SECURITY, value: $member[0]);
    }

    /**
     * @param string $username
     * @return \Baton\T4g\Model\Security
     */
    public function setUsername(string $username) {
        $this->username = $username;
        return $this;
    }

    /**
     * @param string $password
     * @return \Baton\T4g\Model\Security
     */
    public function setPassword(string $password) {
        $this->password = $password;
        return $this;
    }

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
     * {@inheritDoc}
     * @see \Baton\T4g\Model\Base::__toString()
     */
    public function __toString(): string {
        $output = parent::__toString();
        $output .= ", username = '" . $this->username;
        $output .= "', password = '" . $this->username . "'";
        return $output;
    }

    /**
     * @return bool
     */
    private function validatePassword(): bool {
        $found = false;
        $entityManager = getEntityManager();
        $member = $entityManager->getRepository(Constant::ENTITY_MEMBERS)->getByUsername(username: $this->getUsername());
        if (password_verify(password: $this->getPassword(), hash: $member[0]->getMemberPassword())) {
            $found = true;
        }
        return $found;
    }
}