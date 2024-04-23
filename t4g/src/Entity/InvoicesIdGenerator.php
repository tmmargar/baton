<?php
declare(strict_types=1);
namespace Baton\T4g\Entity;
use Baton\T4g\Model\Constant;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AbstractIdGenerator;
class InvoicesIdGenerator extends AbstractIdGenerator
{
  public function generateId(EntityManagerInterface $em, $entity)
  {
    $queryBuilder = $em->createQueryBuilder();
    $queryBuilder->select("IFNULL(MAX(i.invoiceId), 0) + 1")->from(Constant::ENTITY_INVOICES, "i");
    $query = $queryBuilder->getQuery();
    $result = $query->getSingleScalarResult();
    return $result;
  }
}