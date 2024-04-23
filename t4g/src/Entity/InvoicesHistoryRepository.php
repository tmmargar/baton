<?php
namespace Baton\T4g\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;

class InvoicesHistoryRepository extends BaseRepository {
    public function getById(?int $invoiceId) {
        $qb = $this->createQueryBuilder("ih")
                   ->addSelect("ilh, iph, m")
                   ->innerJoin("ih.members", "m")
                   ->innerJoin("ih.invoiceLines", "ilh")
                   ->leftJoin("ih.invoicePayments", "iph");
        if (isset($invoiceId)) {
            $qb = $qb->where("ih.invoiceId = :invoiceId")
                     ->setParameters(new ArrayCollection(array(new Parameter("invoiceId", $invoiceId))));
        }
        //$qb->addOrderBy("ih.invoiceId, ilh.invoiceLineId, ih.revision, ilh.revision, iph.invoicePaymentId, iph.revision");
        $qb->addOrderBy("ih.revision, ilh.revision, iph.revision");
        return $qb->getQuery()->getResult();
    }
}