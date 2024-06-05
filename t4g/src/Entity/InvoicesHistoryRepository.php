<?php
namespace Baton\T4g\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;

class InvoicesHistoryRepository extends BaseRepository {
    public function getById(?int $invoiceId) {
        $qb = $this->createQueryBuilder(alias: "ih")
                   ->addSelect(select: "ilh, iph, m")
                   ->innerJoin(join: "ih.members", alias: "m")
                   ->innerJoin(join: "ih.invoiceLines", alias: "ilh")
                   ->leftJoin(join: "ih.invoicePayments", alias: "iph");
        if (isset($invoiceId)) {
            $qb = $qb->where("ih.invoiceId = :invoiceId")
                     ->setParameters(parameters: new ArrayCollection(elements: array(new Parameter(name: "invoiceId", value: $invoiceId))));
        }
        //$qb->addOrderBy("ih.invoiceId, ilh.invoiceLineId, ih.revision, ilh.revision, iph.invoicePaymentId, iph.revision");
        $qb->addOrderBy(sort: "ih.revision, ilh.revision, iph.revision");
        return $qb->getQuery()->getResult();
    }
}