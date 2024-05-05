<?php
declare(strict_types = 1);
namespace Baton\T4g;
use Baton\T4g\Model\Constant;
require_once "init.php";
$smarty->assign("title", "Twirling for Grace Member Home");
$smarty->assign("heading", "Twirling for Grace Member Home");
$smarty->assign("action", $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"]);
$outputHome = "";
$outputHome =
    "<h2 class=\"center\">Pricing</h2>" .
    "<p class=\"center\">*Prices do not include any potential gym rental fee and must be paid even if student misses the event.</p>\n" .
    "<br />\n";
$eventTypes = $entityManager->getRepository(Constant::ENTITY_EVENT_TYPES)->getById(eventTypeId: NULL);
foreach($eventTypes as $eventType) {
    $outputHome .= "<h3 class=\"center\">" . $eventType->getEventTypeName() . "</h3>\n";
    $eventTypeCostTimeLengthPrevious = "";
    foreach($eventType->getEventTypeCosts() as $eventTypeCost) {
        if ($eventTypeCostTimeLengthPrevious != $eventTypeCost->getEventTypeTimeLength()) {
            $outputHome .= "<h4 class=\"center\">" . $eventTypeCost->getEventTypeTimeLength() . " minutes</h3>\n";
        }
        $outputHome .=
          "<div class=\"center\" style=\"width: 30%;\">\n" .
          " <ul class=\"two-columns\">\n";
        if (0 < $eventTypeCost->getEventTypeStudentCount()) {
            $outputHome .= "  <li>" . $eventTypeCost->getEventTypeStudentCount() . " student(s)</li>";
        } else {
            $outputHome .= "  <li></li>";
        }
        $outputHome .=
          "<li>$" . $eventTypeCost->getEventTypeCost() . "</li>\n" .
          " </ul>\n" .
          "</div>\n";
        $eventTypeCostTimeLengthPrevious = $eventTypeCost->getEventTypeTimeLength();
    }
}
$smarty->assign("content", $outputHome);
$smarty->display("home.tpl");