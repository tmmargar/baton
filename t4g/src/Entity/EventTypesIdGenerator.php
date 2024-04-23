<?php
declare(strict_types=1);
namespace Baton\T4g\Entity;
use Baton\T4g\Model\Constant;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AbstractIdGenerator;
class EventTypesIdGenerator extends AbstractIdGenerator
{
  public function generateId(EntityManagerInterface $em, $entity)
  {
    $queryBuilder = $em->createQueryBuilder();
    $queryBuilder->select("IFNULL(MAX(et.eventTypeId), 0) + 1")->from(Constant::ENTITY_EVENT_TYPES, "et");
    $query = $queryBuilder->getQuery();
    $result = $query->getSingleScalarResult();
    return $result;
  }
}