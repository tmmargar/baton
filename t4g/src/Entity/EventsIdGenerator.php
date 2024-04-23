<?php
declare(strict_types=1);
namespace Baton\T4g\Entity;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AbstractIdGenerator;
class EventsIdGenerator extends AbstractIdGenerator
{
  public function generateId(EntityManagerInterface $em, $entity)
  {
    $queryBuilder = $em->createQueryBuilder();
    $queryBuilder->select("IFNULL(MAX(e.eventId), 0) + 1")->from("Baton\T4g\Entity\Events", "e");
    $query = $queryBuilder->getQuery();
    $result = $query->getSingleScalarResult();
    return $result;
  }
}