<?php
declare(strict_types = 1);
namespace Baton\T4g;
use Baton\T4g\Common\Calendar;
use Baton\T4g\Model\Constant;
use DateTime;
use Baton\T4g\Utility\DateTimeUtility;
// date_default_timezone_set("America/Detroit");
require_once "init.php";
// include "src/Common/Calendar.php";
// $calendar = new Calendar("2023-05-12");
// $calendar->add_event("Birthday Long Text Test", "2023-05-03", 1, "green");
// $calendar->add_event("Doctors", "2023-05-04", 1, "red");
// $calendar->add_event("Holiday", "2023-05-16", 7);
$entityManager = getEntityManager();
$now = new DateTime();
$calendar = new Calendar(DateTimeUtility::formatDatabaseDate(value: $now));
$resultListEventsByDate = $entityManager->getRepository(Constant::ENTITY_EVENTS)->getAllByDate();
$counter = 0;
foreach ($resultListEventsByDate as $event) {
    $eventOrganizations = "";
    if (0 < $event->getEventOrganizations()->count()) {
//         $counterOrganization = 0;
//         foreach($event->getEventOrganizations() as $eventOrganization) {
//             $counterOrganization++;
//         }
        foreach($event->getEventOrganizations() as $eventOrganization) {
            $resultListOrganizations = $entityManager->getRepository(Constant::ENTITY_ORGANIZATIONS)->getById(organizationId: $eventOrganization->getOrganizations()->getOrganizationId());
            $organizationName = $resultListOrganizations->getOrganizationName();
            $calendar->add_event($organizationName . "-".$event->getEventName() . "@" . DateTimeUtility::formatDisplayShortTime(value: $event->getEventStartDate()) . "-" . DateTimeUtility::formatDisplayShortTime(value: $event->getEventEndDate()), DateTimeUtility::formatDatabaseDate(value: $event->getEventStartDate()), DateTimeUtility::formatDateIntervalSignDays(value: ((int) date_diff($event->getEventStartDate(), $event->getEventEndDate()))) + 1, strtolower($organizationName));
        }
    } else {
        $calendar->add_event($event->getEventName() . "@" . DateTimeUtility::formatDisplayShortTime(value: $event->getEventStartDate()) . "-" . DateTimeUtility::formatDisplayShortTime(value: $event->getEventEndDate()), DateTimeUtility::formatDatabaseDate(value: $event->getEventStartDate()), DateTimeUtility::formatDateIntervalSignDays(value: ((int) date_diff($event->getEventStartDate(), $event->getEventEndDate()))) + 1, strtolower($event->getEventType()->getEventTypeName()));
    }
    $counter++;
}

?>
<!DOCTYPE html>
<html>
 <head>
  <meta charset="utf-8">
  <title>Event Calendar</title>
  <link href="css/style.css" rel="stylesheet" type="text/css">
  <link href="css/calendar.css" rel="stylesheet" type="text/css">
 </head>
 <body>
  <div class="content home">
   <div class="my-legend">
    <div class="legend-title">Organization</div>
    <div class="legend-scale">
     <ul class="legend-labels">
      <li><span class="usta"></span>USTA</li>
      <li><span class="wta"></span>WTA</li>
      <li><span class="tu"></span>TU</li>
      <li><span class="fusion"></span>Fusion</li>
      <li><span class="nbta"></span>NBTA</li>
     </ul>
    </div>
   </div>
   <?=$calendar?>
  </div>
 </body>
</html>