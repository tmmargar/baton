<?php
declare(strict_types = 1);
namespace Baton\T4g;
use Baton\T4g\Model\Constant;
use DateTime;
use Baton\T4g\Utility\DateTimeUtility;
use Baton\T4g\Utility\HtmlUtility;
require_once "init.php";
$smarty->assign("title", "T4G Events");
$smarty->assign("heading", "");
$output = "";
$output .= "<div class=\"title\">Events</div>\n";
$output .= "<p>Below is a list of the events we have decided to participate in with links to the event's website for more information.</p><br>\n";
$now = new DateTime();
$events = $entityManager->getRepository(Constant::ENTITY_EVENTS)->getAllByDate();
$counter = 0;
foreach ($events as $event) {
    $eventOrganizations = "";
    if (0 < $event->getEventOrganizations()->count()) {
        $counterOrganization = 0;
        foreach($event->getEventOrganizations() as $eventOrganization) {
            $eventOrganizations .= $eventOrganization->getOrganizations()->getOrganizationName();
            $counterOrganization++;
        }
    }
    //     DateTimeUtility::formatDisplayDate(value: $event->getEventStartDate()) . " - " . $event->getEventName() . $eventOrganizations
    $output .= "<div>";
    $output .= "<strong><u>" . $event->getEventType()->getEventTypeName() . "</u></strong><br>";
    $output .= $event->getEventLocation() . " - " . DateTimeUtility::formatDisplayDate(value: $event->getEventStartDate()) . " to " . DateTimeUtility::formatDisplayDate(value: $event->getEventEndDate()) . "<br>";
    $output .= "   <td>" . (NULL !== $event->getEventUrl() ? HtmlUtility::buildLink(href: $event->getEventUrl(), id: NULL, target: "_blank", title: $event->getEventName(), text: $event->getEventName()) . " - " . $event->getEventDescription() : $event->getEventName() . " - " . $event->getEventDescription());
    $output .= "</div>\n";
    $counter++;
}
if (!$eventOrganizations) {
    $output .= "No events";
}
$smarty->assign("content", $output);
$smarty->display("events.tpl");