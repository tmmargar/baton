<?php
namespace Baton\T4g\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;

class InvoicesRepository extends BaseRepository {
//     public function getAll() {
//         return $this->createQueryBuilder("i")
//                     ->addSelect("il, ip")
//                     ->leftJoin("i.invoiceLines", "il")
//                     ->leftJoin("i.invoicePayments", "ip")
//                     ->orderBy("il.invoiceLineId, ip.invoicePaymentId", "ASC")
//                     ->getQuery()->getResult();
//     }

    public function getById(?int $invoiceId, ?int $memberId) {
        $qb = $this->createQueryBuilder(alias: "i")
                   ->addSelect(select: "il, ip, m")
                   ->innerJoin(join: "i.members", alias: "m")
                   ->innerJoin(join: "i.invoiceLines", alias: "il")
                   ->leftJoin(join: "i.invoicePayments", alias: "ip");
        if (isset($invoiceId)) {
            $qb = $qb->where(predicates: "i.invoiceId = :invoiceId")
            ->setParameters(parameters: new ArrayCollection(elements: array(new Parameter(name: "invoiceId", value: $invoiceId))));
        }
        if (isset($memberId)) {
            $qb = $qb->where("m.memberId = :memberId")
                     ->setParameters(parameters: new ArrayCollection(elements: array(new Parameter(name: "memberId", value: $memberId))));
        }
        return $qb->getQuery()->getResult();
    }

//     public function getTableOutput(?int $invoiceId, bool $indexed) {
//         $sql =
//             "SELECT invoice_id AS id, invoice_date AS date, invoice_due_date AS 'due date', invoice_comment AS comment " .
//             "FROM baton_invoices ";
//         if (isset($invoiceId)) {
//             $sql .= "WHERE invoice_id = :invoiceId";
//         }
//         $statement = $this->getEntityManager()->getConnection()->prepare($sql);
//         if (isset($invoiceId)) {
//             $statement->bindValue("invoiceId", $invoiceId, PDO::PARAM_INT);
//         }
//         if ($indexed) {
//             return $statement->executeQuery()->fetchAllNumeric();
//         } else {
//             return $statement->executeQuery()->fetchAllAssociative();
//         }
//     }
}