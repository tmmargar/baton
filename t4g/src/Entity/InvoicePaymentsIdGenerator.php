<?php
declare(strict_types=1);
namespace Baton\T4g\Entity;
use Baton\T4g\Model\Constant;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AbstractIdGenerator;
class InvoicePaymentsIdGenerator extends AbstractIdGenerator
{
  public function generateId(EntityManagerInterface $em, $entity)
  {
    $queryBuilder = $em->createQueryBuilder();
    $queryBuilder->addSelect("MAX(SUBSTRING_INDEX(IFNULL(ip.invoicePaymentId, '0-0'), '-', -1)) + 1")
                 ->from(Constant::ENTITY_INVOICES, "i")
                 ->leftJoin("i.invoicePayments", "ip")
                 ->andWhere("i.invoiceId = " . $entity->getInvoices()->getInvoiceId());
    $query = $queryBuilder->getQuery();
    $result = $entity->getInvoices()->getInvoiceId() . "-" . $query->getSingleScalarResult();
    return $result;
  }
}