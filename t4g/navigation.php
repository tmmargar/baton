<?php
declare(strict_types = 1);
use Baton\T4g\Model\Constant;
use Baton\T4g\Model\HtmlLink;
use Baton\T4g\Model\HtmlMenu;
use Baton\T4g\Utility\DateTimeUtility;
use Baton\T4g\Utility\HtmlUtility;
use Baton\T4g\Utility\SessionUtility;
$entityManager = getEntityManager();
$htmlLinkHome = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "home.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Home", title: NULL);
$htmlLinkOrganizations = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "organizations.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Organizations", title: NULL);
// $htmlMenuOrganization = new HtmlMenu(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: NULL, text: "Organizations");
// $resultListOrganizations = $entityManager->getRepository(Constant::ENTITY_ORGANIZATIONS)->getAll();
// $counter = 0;
// foreach ($resultListOrganizations as $organization) {
//     $htmlLinkOrganization = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "organizations.php", id: NULL, paramName: array("organizationId"), paramValue: array($organization->getOrganizationId()), tabIndex: - 1, text: $organization->getOrganizationName(), title: NULL);
//     $htmlLinkOrganizationArray[$counter] = $htmlLinkOrganization;
//     $htmlMenuOrganization->setItems($htmlLinkOrganizationArray);
//     $counter++;
// }
$htmlLinkEvents = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "events.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Events", title: NULL);
// $htmlMenuEvent = new HtmlMenu(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: NULL, text: "Events");
// $htmlMenuEventByDate = new HtmlMenu(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: NULL, text: "By Date By Name");
// $resultListEventsByDate = $entityManager->getRepository(Constant::ENTITY_EVENTS)->getAllByDate();
// $counter = 0;
// foreach ($resultListEventsByDate as $event) {
//     $eventOrganizations = "";
//     if (0 < $event->getEventOrganizations()->count()) {
//         $counterOrganization = 0;
//         foreach($event->getEventOrganizations() as $eventOrganization) {
//             if (0 == $counterOrganization) {
//                 $eventOrganizations .= " - ";
//             } else {
//                 $eventOrganizations .= "/";
//             }
//             $eventOrganizations .= $eventOrganization->getOrganizations()->getOrganizationName();
//             $counterOrganization++;
//         }
//     }
//     $htmlLinkEvent = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "events.php", id: NULL, paramName: array("eventId"), paramValue: array($event->getEventId()), tabIndex: - 1, text: $event->getEventStartDate()->format("m/d/Y") . " - " . $event->getEventName() . $eventOrganizations,  title: NULL);
//     $htmlLinkEventArray[$counter] = $htmlLinkEvent;
//     $htmlMenuEventByDate->setItems($htmlLinkEventArray);
//     $counter++;
// }
// $htmlMenuEventByOrgByDate = new HtmlMenu(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: NULL, text: "By Organization By Date");
// $counterOrganization = 0;
// foreach ($resultListOrganizations as $organization) {
//     $htmlMenuEventOrganization = new HtmlMenu(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: NULL, text: $organization->getOrganizationName());
//     $htmlLinkOrganizationArray[$counterOrganization] = $htmlMenuEventOrganization;
//     if (0 < $organization->getEventOrganizations()->count()) {
//         $counterOrganizationEvent = 0;
//         foreach($organization->getEventOrganizations() as $eventOrganization) {
//             $htmlLinkEventByOrg = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "events.php", id: NULL, paramName: array("eventId"), paramValue: array($eventOrganization->getEvents()->getEventId()), tabIndex: - 1, text: $eventOrganization->getEvents()->getEventStartDate()->format("m/d/Y") . " - " . $eventOrganization->getEvents()->getEventName(),  title: NULL);
//             $htmlLinkEventByOrgArray[$counterOrganizationEvent] = $htmlLinkEventByOrg;
//             $counterOrganizationEvent++;
//         }
//         $htmlMenuEventOrganization->setItems($htmlLinkEventByOrgArray);
//     }
//     $counterOrganization++;
// }
// $htmlMenuEventByOrgByDate->setItems($htmlLinkOrganizationArray);
// $htmlMenuEvent->setItems(array($htmlMenuEventByDate, $htmlMenuEventByOrgByDate));
// $htmlLinkEdit = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "managePlayer.php", id: NULL, paramName: array("mode","playerId"), paramValue: array("modify",SessionUtility::getValue(SessionUtility::OBJECT_NAME_MEMBERID)), tabIndex: - 1, text: "Edit my profile", title: NULL);
// $htmlLinkLogout = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "logout.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Logout", title: NULL);
// $htmlLinkMyProfileArray = array($htmlLinkEdit,$htmlLinkLogout);
// $htmlMenuReportMyProfile->setItems($htmlLinkMyProfileArray);
// array_push($levels, $htmlMenuReportMyProfile);
//$htmlLinkEvents = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "registrationList.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Events", title: NULL);
// $levels = array($htmlLinkHome,$htmlMenuOrganization,$htmlMenuEvent);
$levels = array($htmlLinkHome,$htmlLinkOrganizations,$htmlLinkEvents);
if (SessionUtility::getValue(SessionUtility::OBJECT_NAME_ADMINISTRATOR) != 0) {
    $htmlMenuReportAdministration = new HtmlMenu(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: NULL, text: "Administration");
    $htmlLinkNewPlayerApproval = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "manageSignupApproval.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "New member approval", title: NULL);
    $htmlLinkInvoice = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "manageInvoice.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Invoices", title: NULL);
    $htmlLinkAdministrationArray = array($htmlLinkInvoice, $htmlLinkNewPlayerApproval);
    $htmlMenuReportAdministration->setItems($htmlLinkAdministrationArray);
    array_push($levels, $htmlMenuReportAdministration);
}
// $htmlMenuReportMyProfile = new HtmlMenu(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: NULL, text: "My profile");
// $htmlLinkEdit = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "managePlayer.php", id: NULL, paramName: array("mode","playerId"), paramValue: array("modify",SessionUtility::getValue(SessionUtility::OBJECT_NAME_MEMBERID)), tabIndex: - 1, text: "Edit my profile", title: NULL);
$htmlLinkLogout = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "logout.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Logout", title: NULL);
// $htmlLinkMyProfileArray = array($htmlLinkEdit,$htmlLinkLogout);
// $htmlMenuReportMyProfile->setItems($htmlLinkMyProfileArray);
// array_push($levels, $htmlMenuReportMyProfile);
array_push($levels, $htmlLinkLogout);
$htmlMenuRoot = new HtmlMenu(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: $levels, text: NULL);
$smarty->assign("navigation", $htmlMenuRoot->getHtmlRoot());