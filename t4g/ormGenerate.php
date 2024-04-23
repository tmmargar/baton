<?php
require_once "bootstrapGenerate.php";
// ConsoleRunner::run(new SingleManagerProvider($entityManager));
$tool = new \Doctrine\ORM\Tools\SchemaTool($entityManager);
$classes = array(
    $entityManager->getClassMetadata('Baton\T4g\Entity\Events'),
    $entityManager->getClassMetadata('Baton\T4g\Entity\EventOrganizations'),
    $entityManager->getClassMetadata('Baton\T4g\Entity\EventTypes'),
    $entityManager->getClassMetadata('Baton\T4g\Entity\EventTypeCosts'),
    $entityManager->getClassMetadata('Baton\T4g\Entity\Invoices'),
    $entityManager->getClassMetadata('Baton\T4g\Entity\InvoiceLines'),
    $entityManager->getClassMetadata('Baton\T4g\Entity\InvoicePayments'),
    $entityManager->getClassMetadata('Baton\T4g\Entity\Members'),
    $entityManager->getClassMetadata('Baton\T4g\Entity\MemberStudents'),
    $entityManager->getClassMetadata('Baton\T4g\Entity\Organizations'),
    $entityManager->getClassMetadata('Baton\T4g\Entity\Students'),
    $entityManager->getClassMetadata('Baton\T4g\Entity\StudentMemberships'),
    $entityManager->getClassMetadata('Baton\T4g\Entity\Teams'),
    $entityManager->getClassMetadata('Baton\T4g\Entity\TeamStudents')
);
$tool->dropSchema($classes);
$tool->createSchema($classes);