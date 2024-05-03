<?php
declare(strict_types = 1);
namespace Baton\T4g;
use Baton\T4g\Common\Calendar;
use Baton\T4g\Model\Constant;
use Baton\T4g\Utility\DateTimeUtility;
use Baton\T4g\Utility\HtmlUtility;
use Baton\T4g\Utility\SessionUtility;
use DateInterval;
use DateTime;
use Exception;
require_once "init.php";
$smarty->assign("title", "Calendar");
$smarty->assign("heading", "Calendar");
$smarty->assign("style", "<link href=\"css/calendar.css\" rel=\"stylesheet\">");
$smarty->assign("script", "");
$errors = NULL;
$output = "";
$outputAdditional = "";
$now = new DateTime();
$entityManager = getEntityManager();
$date = (isset($_GET["date"]) ? new DateTime($_GET["date"]) : $now);
$calendar = new Calendar(DateTimeUtility::formatDatabaseDate(value: $date));
$resultListEventsByDate = $entityManager->getRepository(Constant::ENTITY_EVENTS)->getAllByDate();
$counter = 0;
foreach ($resultListEventsByDate as $event) {
    $eventOrganizations = "";
    if (0 < $event->getEventOrganizations()->count()) {
        foreach($event->getEventOrganizations() as $eventOrganization) {
            $resultListOrganizations = $entityManager->getRepository(Constant::ENTITY_ORGANIZATIONS)->getById(organizationId: $eventOrganization->getOrganizations()->getOrganizationId());
            $organizationName = $resultListOrganizations[0]->getOrganizationName();
            $calendar->add_event($organizationName . "-".$event->getEventName() . "@" . DateTimeUtility::formatDisplayShortDateTime(value: $event->getEventStartDate()) . "-" . DateTimeUtility::formatDisplayShortDateTime(value: $event->getEventEndDate()), DateTimeUtility::formatDatabaseDate(value: $event->getEventStartDate()), DateTimeUtility::formatDateIntervalSignDays(value: date_diff($event->getEventStartDate(), $event->getEventEndDate())) + 1, strtolower($organizationName));
        }
    } else {
        $calendar->add_event($event->getEventName() . "@" . DateTimeUtility::formatDisplayShortDateTime(value: $event->getEventStartDate()) . "-" . DateTimeUtility::formatDisplayShortDateTime(value: $event->getEventEndDate()), DateTimeUtility::formatDatabaseDate(value: $event->getEventStartDate()), DateTimeUtility::formatDateIntervalSignDays(value: date_diff($event->getEventStartDate(), $event->getEventEndDate())) + 1, strtolower($event->getEventType()->getEventTypeName()));
    }
    $counter++;
}
$output .= "<div class=\"content home\">\n";
$output .= " <div class=\"my-legend\">\n";
$output .= "  <div class=\"legend-title\">Legend</div>\n";
$output .= "  <div class=\"legend-scale\">\n";
$organizations = $entityManager->getRepository(Constant::ENTITY_ORGANIZATIONS)->getById(organizationId: NULL);
$counter = 1;
foreach ($organizations as $organization) {
 if ($counter == 1 || $counter % 4 == 0) {
   $output .= "   <ul class=\"legend-labels\">\n";
}
$output .= "<li><span class=\"" . strtolower($organization->getOrganizationName()) . "\"></span>" . $organization->getOrganizationName() . "</li>\n";
if ($counter % 3 == 0) {
   $output .= "   </ul>\n";
}
$counter++;
}
if ($counter % 4 != 0) {
 $output .= "   </ul>\n";
}
$eventTypes = $entityManager->getRepository(Constant::ENTITY_EVENT_TYPES)->getById(eventTypeId: NULL);
$counter = 1;
foreach ($eventTypes as $eventType) {
 if ($counter == 1 || $counter % 4 == 0) {
     $output .= "   <ul class=\"legend-labels\">\n";
 }
 $output .= "    <li><span class=\"" . strtolower($eventType->getEventTypeName()) . "\"></span>" . $eventType->getEventTypeName() . "</li>\n";
 if ($counter % 3 == 0) {
     $output .= "   </ul>\n";
 }
 $counter++;
}
if ($counter % 4 != 0) {
 $output .= "   </ul>\n";
}
$output .= "  </div>\n";
$output .= " </div>\n";
$output .= $calendar;
$output .= "</div>\n";
$smarty->assign("content", $output);
$smarty->assign("contentAdditional", $outputAdditional);
$smarty->display("calendar.tpl");