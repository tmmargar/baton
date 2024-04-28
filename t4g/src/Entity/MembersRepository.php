<?php
namespace Baton\T4g\Entity;

use Baton\T4g\Utility\DateTimeUtility;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use DateInterval;
use DateTime;
use PDO;

class MembersRepository extends BaseRepository {
    public function getById(?int $memberId) {
        $qb = $this->createQueryBuilder("m");
        if (isset($memberId)) {
            $qb = $qb->where("m.memberId = :memberId");
            $qb->setParameters(new ArrayCollection(array(new Parameter("memberId", $memberId))));
        }
        $qb = $qb->addOrderBy("m.memberLastName, m.memberFirstName", "ASC");
        return $qb->getQuery()->getResult();
    }

    public function getByUsername(string $username) {
        return $this->createQueryBuilder("m")
                    ->addSelect("ma, mr")
                    ->leftJoin("m.memberApproval", "ma")
                    ->leftJoin("m.memberRejection", "mr")
                    ->where("m.memberUsername = :username")
                    ->setParameters(new ArrayCollection(array(new Parameter("username", $username))))
                    ->getQuery()->getResult();
    }

    public function getByEmail(string $email) {
        return $this->createQueryBuilder("m")
                    ->addSelect("ma, mr")
                    ->leftJoin("m.memberApproval", "ma")
                    ->leftJoin("m.memberRejection", "mr")
                    ->where("m.memberEmail = :email")
                    ->setParameters(new ArrayCollection(array(new Parameter("email", $email))))
                    ->getQuery()->getResult();
    }

    public function getByUsernameAndEmail(string $username, string $email) {
        return $this->createQueryBuilder("m")
                    ->addSelect("ma, mr")
                    ->leftJoin("m.memberApproval", "ma")
                    ->leftJoin("m.memberRejection", "mr")
                    ->where("m.memberUsername = :username")
                    ->andWhere("m.memberEmail = :email")
                    ->setParameters(new ArrayCollection(array(new Parameter("username", $username), new Parameter("email", $email))))
                    ->getQuery()->getResult();
    }

    public function getForApproval(bool $indexed) {
        $sql =
            "SELECT m.member_id, CONCAT(m.member_first_name, ' ', m.member_last_name) AS name, m.member_email AS email, m.member_username AS username, m.member_rejection_date AS 'Rejection Date', CONCAT(m2.member_first_name, ' ', m2.member_last_name) AS 'Rejection Name' " .
            "FROM baton_members m " .
            "LEFT JOIN baton_members m2 ON m.member_rejection_member_id = m2.member_id " .
            "WHERE m.member_approval_date IS NULL AND m.member_rejection_date IS NULL";
        //         return $this->createQueryBuilder("m")
        //                     ->where("m.memberApprovalDate IS NULL")
        //                     ->andWhere("m.memberRejectionDate IS NULL")
        //                     ->getQuery()->getResult();
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
    }

    public function updateForReset(?string $password, bool $updateToken, string $username, string $email) {
        if (isset($password)) {
            $selector = NULL;
            $hash = NULL;
            $expiresFormatted = NULL;
        } else {
            $selector = bin2hex(random_bytes(length: 8));
            $token = random_bytes(length: 32);
            $hash = hash(algo: 'sha256', data: $token);
            $expires = new DateTime();
            $expires->add(new DateInterval("P1D")); // 1 day
            $expiresFormatted = DateTimeUtility::formatSecondsSinceEpoch(value: $expires);
        }
        $sql =
            "UPDATE baton_members " .
            "SET " . (isset($password) ? "member_password = ?, " : "") .
            "member_selector = ?, member_token = ?, member_expires = ? " .
            "WHERE member_username = ? " .
            "AND member_email = ?";
        $params = array();
        $paramTypes = array();
        if (isset($password)) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            array_push($params, $hash);
            array_push($paramTypes, PDO::PARAM_STR);
        }
        array_push($params, $selector, $updateToken ? $hash : NULL, $expiresFormatted, $username, $email);
        array_push($paramTypes, PDO::PARAM_STR, PDO::PARAM_STR, PDO::PARAM_STR, PDO::PARAM_STR, PDO::PARAM_STR);
        $recordCount = $this->getEntityManager()->getConnection()->executeStatement($sql, $params, $paramTypes);
        if (isset($password)) {
            $returnValue = $recordCount;
        } else {
            if (1 == $recordCount) {
                $returnValue = array($selector, bin2hex($token), DateTimeUtility::formatSecondsSinceEpoch(value: $expires));
            } else {
                $returnValue = "More than 1 record found!";
            }
        }
        return $returnValue;
    }
}