<?php
require_once "bootstrap.php";
use Doctrine\Common\Collections\ArrayCollection;
use Baton\T4g\Entity\Events;
use Baton\T4g\Entity\EventOrganizations;
use Baton\T4g\Entity\EventTypes;
use Baton\T4g\Entity\Invoices;
use Baton\T4g\Entity\InvoiceLines;
use Baton\T4g\Entity\Members;
use Baton\T4g\Entity\MemberStudents;
use Baton\T4g\Entity\Organizations;
use Baton\T4g\Entity\Students;
use Baton\T4g\Entity\Teams;
use Baton\T4g\Entity\TeamStudents;
use Baton\T4g\Model\Constant;
use Baton\T4g\Utility\DateTimeUtility;
use Baton\T4g\Entity\InvoicePayments;
date_default_timezone_set(timezoneId: "America/New_York");
$entityManager = getEntityManager();

$et = new EventTypes();
$et->setEventTypeName("orm event type name");
$et->setEventTypeDescription("orm event type desc");
$entityManager->persist($et);
$entityManager->flush();
echo "<br>Created EventTypes with ID=" . $et->getEventTypeId() . " and name=" . $et->getEventTypeName();
$et->setEventTypeName("orm updated et");
$entityManager->persist($et);
$entityManager->flush();
echo "<br>Updated EventTypes with ID=" . $et->getEventTypeId() . " and name=" . $et->getEventTypeName();
$idDeleted = $et->getEventTypeId();
$entityManager->remove($et);
$entityManager->flush();
echo "<br>Deleted EventTypes with ID=" . $idDeleted;

$or = new Organizations();
$or->setOrganizationName("orm organization name");
$or->setOrganizationDescription("orm organization desc");
$or->setOrganizationUrl("http://www.google.com/");
$entityManager->persist($or);
$entityManager->flush();
echo "<br>Created Organizations with ID=" . $or->getOrganizationId() . " and name=" . $or->getOrganizationName();
$or->setOrganizationName("orm updated or");
$entityManager->persist($or);
$entityManager->flush();
echo "<br>Updated Organizations with ID=" . $or->getOrganizationId() . " and name=" . $or->getOrganizationName();

$ev = new Events();
$etFind = $entityManager->find(Constant::ENTITY_EVENT_TYPES, 1);
$ev->setEventType($etFind);
$ev->setEventStartDate(new DateTime("2024-04-01"));
$ev->setEventEndDate(new DateTime("2024-04-03"));
$ev->setEventName("orm event name");
$ev->setEventDescription("orm event desc");
$ev->setEventLocation("Katrina's house in Brighton");
$ev->setEventUrl("http://www.amazon.com/");
$entityManager->persist($ev);
$entityManager->flush();
echo "<br>Created Events with ID=" . $ev->getEventId() . " and name=" . $ev->getEventName();
$ev->setEventName("orm updated ev");
$entityManager->persist($ev);
$entityManager->flush();
echo "<br>Updated Events with ID=" . $ev->getEventId() . " and name=" . $ev->getEventName();

$eo = new EventOrganizations();
$evFind = $entityManager->find(Constant::ENTITY_EVENTS, $ev->getEventId());
$eo->setEvents($evFind);
$orFind = $entityManager->find(Constant::ENTITY_ORGANIZATIONS, $or->getOrganizationId());
$eo->setOrganizations($orFind);
$eo->setUrl("http://www.eo.com/");
$entityManager->persist($eo);
$entityManager->flush();
echo "<br>Created EventOrganizations with ID=" . $eo->getEvents()->getEventName() . " and name=" . $eo->getOrganizations()->getOrganizationName();
$eo->setUrl("orm updated eo");
$entityManager->persist($eo);
$entityManager->flush();
echo "<br>Updated EventOrganizations with ID=" . $eo->getEvents()->getEventName() . " and name=" . $eo->getOrganizations()->getOrganizationName();
$idEventDeleted = $eo->getEvents()->getEventId();
$idOrganizationDeleted = $eo->getOrganizations()->getOrganizationId();
$entityManager->remove($eo);
$entityManager->flush();
echo "<br>Deleted EventOrganizations with ID=" . $idEventDeleted . "/" . $idOrganizationDeleted;

$idDeleted = $or->getOrganizationId();
$entityManager->remove($or);
$entityManager->flush();
echo "<br>Deleted Organizations with ID=" . $idDeleted;

$idDeleted = $ev->getEventId();
$entityManager->remove($ev);
$entityManager->flush();
echo "<br>Deleted Events with ID=" . $idDeleted;

$st = new Students();
$st->setStudentActiveFlag(1);
$st->setStudentEmail("orm@email.com");
$st->setStudentFirstName("orm first");
$st->setStudentLastName("orm last");
$st->setStudentPhone(8105551212);
$st->setStudentRegistrationDate(new DateTime());
$entityManager->persist($st);
$entityManager->flush();
echo "<br>Created Students with ID=" . $st->getStudentId() . " and name=" . $st->getStudentName();
$st->setStudentEmail("orm updated st");
$entityManager->persist($st);
$entityManager->flush();
echo "<br>Updated Students with ID=" . $st->getStudentId() . " and name=" . $st->getStudentName();

$te = new Teams();
$te->setTeamName("orm team name");
$te->setTeamDescription("orm team desc");
$entityManager->persist($te);
$entityManager->flush();
echo "<br>Created Teams with ID=" . $te->getTeamId() . " and name=" . $te->getTeamName();
$te->setTeamName("orm updated te");
$entityManager->persist($te);
$entityManager->flush();
echo "<br>Updated Teams with ID=" . $te->getTeamId() . " and name=" . $te->getTeamName();

$ts = new TeamStudents();
$teFind = $entityManager->find(Constant::ENTITY_TEAMS, $te->getTeamId());
$stFind = $entityManager->find(Constant::ENTITY_STUDENTS, $st->getStudentId());
$ts->setTeams($teFind);
$ts->setStudents($stFind);
$entityManager->persist($ts);
$entityManager->flush();
echo "<br>Created TeamStudents with ID=" . $ts->getTeams()->getTeamId() . " and name=" . $ts->getStudents()->getStudentId();
$idTeamDeleted = $ts->getTeams()->getTeamId();
$idStudentDeleted = $ts->getStudents()->getStudentId();
$entityManager->remove($ts);
$entityManager->flush();
echo "<br>Deleted TeamStudents with ID=" . $idTeamDeleted . "/" . $idStudentDeleted;

$idDeleted = $te->getTeamId();
$entityManager->remove($te);
$entityManager->flush();
echo "<br>Deleted Teams with ID=" . $idDeleted;

$me = new Members();
$me->setMemberActiveFlag(1);
$me->setMemberAdministratorFlag(0);
$me->setMemberApproval(NULL);
$me->setMemberApprovalDate(NULL);
$me->setMemberEmail("member@email.com");
$me->setMemberFirstName("orm member fn");
$me->setMemberLastName("orm member ln");
$me->setMemberPassword("orm member pwd");
$me->setMemberPhone(2485551212);
$me->setMemberRegistrationDate(new DateTime());
$me->setMemberRejection(NULL);
$me->setMemberRejectionDate(NULL);
$me->setMemberUsername("a@b.c");
$entityManager->persist($me);
$entityManager->flush();
echo "<br>Created Members with ID=" . $me->getMemberId() . " and name=" . $me->getMemberName();
$me->setMemberFirstName("orm updated me");
$entityManager->persist($me);
$entityManager->flush();
echo "<br>Updated Members with ID=" . $me->getMemberId() . " and name=" . $me->getMemberName();

$ms = new MemberStudents();
$meFind = $entityManager->find(Constant::ENTITY_MEMBERS, $me->getMemberId());
$stFind = $entityManager->find(Constant::ENTITY_STUDENTS, $st->getStudentId());
$ms->setMembers($meFind);
$ms->setStudents($stFind);
$entityManager->persist($ms);
$entityManager->flush();
echo "<br>Created MemberStudents with ID=" . $ms->getMembers()->getMemberId() . " and name=" . $ms->getStudents()->getStudentId();
$idMemberDeleted = $ms->getMembers()->getMemberId();
$idStudentDeleted = $ms->getStudents()->getStudentId();
$entityManager->remove($ms);
$entityManager->flush();
echo "<br>Deleted MemberStudents with ID=" . $idMemberDeleted . "/" . $idStudentDeleted;

$in = new Invoices();
$in->setInvoiceAmount(10);
$in->setInvoiceComment("test first invoice");
$in->setInvoiceDate(new DateTime("2024-03-22"));
$in->setInvoiceDueDate($in->getInvoiceDate()->add(new DateInterval("P14D")));
$meFind = $entityManager->find(Constant::ENTITY_MEMBERS, $me->getMemberId());
$in->setMembers($meFind);
$entityManager->persist($in);
$entityManager->flush();
echo "<br>Created Invoices with ID=" . $in->getInvoiceId() . " for member=" . $ms->getMembers()->getMemberId() . " and student=" . $ms->getStudents()->getStudentId();

$il = new InvoiceLines();
$etFind = $entityManager->find(Constant::ENTITY_EVENT_TYPES, 1);
$il->setEventTypes($etFind);
$il->setInvoiceLineAmount(4);
$stFind = $entityManager->find(Constant::ENTITY_STUDENTS, $st->getStudentId());
$il->setStudents($stFind);
$il->setInvoiceLineComment("test first inv line");
$inFind = $entityManager->find(Constant::ENTITY_INVOICES, $in->getInvoiceId());
$il->setInvoices($inFind);
$entityManager->persist($il);
$entityManager->flush();
echo "<br>Created InvoiceLines with ID=" . $il->getInvoiceLineId() . "/amt=" . $il->getInvoiceLineAmount() . " for invoice=" . $inFind->getInvoiceId();

$il2 = new InvoiceLines();
$etFind2 = $entityManager->find(Constant::ENTITY_EVENT_TYPES, 1);
$il2->setEventTypes($etFind2);
$il2->setInvoiceLineAmount(6);
$stFind2 = $entityManager->find(Constant::ENTITY_STUDENTS, $st->getStudentId());
$il2->setStudents($stFind2);
$il2->setInvoiceLineComment("test first inv line");
$il2->setInvoices($inFind);
$entityManager->persist($il2);
$entityManager->flush();
echo "<br>Created InvoiceLines with ID=" . $il2->getInvoiceLineId() . "/amt=" . $il2->getInvoiceLineAmount() . " for invoice=" . $inFind->getInvoiceId();

$ip = new InvoicePayments();
$ip->setInvoicePaymentAmount(5);
$ip->setInvoicePaymentComment("first payment");
$ip->setInvoicePaymentDate(new DateTime());
$ip->setInvoices($inFind);
$entityManager->persist($ip);
$entityManager->flush();
echo "<br>Created InvoicePayments with ID=" . $ip->getInvoicePaymentId() . "/amt=" . $ip->getInvoicePaymentAmount();

$ip2 = new InvoicePayments();
$ip2->setInvoicePaymentAmount(3);
$ip2->setInvoicePaymentComment("second payment");
$ip2->setInvoicePaymentDate(new DateTime());
$ip2->setInvoices($inFind);
$entityManager->persist($ip2);
$entityManager->flush();
echo "<br>Created InvoicePayments with ID=" . $ip2->getInvoicePaymentId() . "/amt=" . $ip2->getInvoicePaymentAmount();

$ip3 = new InvoicePayments();
$ip3->setInvoicePaymentAmount(2);
$ip3->setInvoicePaymentComment("third payment");
$ip3->setInvoicePaymentDate(new DateTime());
$ip3->setInvoices($inFind);
$entityManager->persist($ip3);
$entityManager->flush();
echo "<br>Created InvoicePayments with ID=" . $ip3->getInvoicePaymentId() . "/amt=" . $ip3->getInvoicePaymentAmount();

$idDeleted = $ip->getInvoicePaymentId();
$entityManager->remove($ip);
$entityManager->flush();
echo "<br>Deleted InvoicePayments with ID=" . $idDeleted;

$idDeleted2 = $ip2->getInvoicePaymentId();
$entityManager->remove($ip2);
$entityManager->flush();
echo "<br>Deleted InvoicePayments with ID=" . $idDeleted2;

$idDeleted3 = $ip3->getInvoicePaymentId();
$entityManager->remove($ip3);
$entityManager->flush();
echo "<br>Deleted InvoicePayments with ID=" . $idDeleted3;

$idDeleted = $il->getInvoiceLineId();
$entityManager->remove($il);
$entityManager->flush();
echo "<br>Deleted InvoiceLines with ID=" . $idDeleted;
$idDeleted2 = $il2->getInvoiceLineId();
$entityManager->remove($il2);
$entityManager->flush();
echo "<br>Deleted InvoiceLines with ID=" . $idDeleted2;

$idDeleted = $in->getInvoiceId();
$entityManager->remove($in);
$entityManager->flush();
echo "<br>Deleted Invoices with ID=" . $idDeleted;

$idDeleted = $me->getMemberId();
$entityManager->remove($me);
$entityManager->flush();
echo "<br>Deleted Members with ID=" . $idDeleted;

$idDeleted = $st->getStudentId();
$entityManager->remove($st);
$entityManager->flush();
echo "<br>Deleted Students with ID=" . $idDeleted;

$teams = $entityManager->getRepository(Constant::ENTITY_TEAMS)->getById(teamId: NULL);
echo "<br>teams count = " . count($teams);
foreach($teams as $team) {
    echo "<BR>name = " . $team->getTeamName();
    foreach($team->getTeamStudents() as $teamStudent) {
        echo "<BR>stud = " . $teamStudent->getStudents()->getStudentName();
    }
}

$invoicesHistory = $entityManager->getRepository(Constant::ENTITY_INVOICES_HISTORY)->getById(invoiceId: NULL);
echo "<br>invoice history count = " . count($invoicesHistory);
foreach($invoicesHistory as $invoiceHistory) {
    echo "<BR>id=" . $invoiceHistory->getInvoiceId() . "/revision=" . $invoiceHistory->getRevision() . "/action=" . $invoiceHistory->getAction();
    foreach($invoiceHistory->getInvoiceLines() as $invoiceLine) {
        echo "<BR>line id=" . $invoiceLine->getInvoiceLineId() . "/revision=" . $invoiceLine->getRevision() . "/action=" . $invoiceLine->getAction();
    }
    foreach($invoiceHistory->getInvoicePayments() as $invoicePayment) {
        echo "<BR>pmt id=" . $invoicePayment->getInvoicePaymentId() . "/revision=" . $invoicePayment->getRevision() . "/action=" . $invoicePayment->getAction();
    }
}
