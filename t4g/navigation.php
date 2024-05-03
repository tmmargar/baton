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
$htmlLinkCalendar = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "calendar.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Calendar", title: NULL);
$htmlLinkOrganizations = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "organizations.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Organizations", title: NULL);
$htmlLinkEvents = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "events.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Events", title: NULL);
$levels = array($htmlLinkHome,$htmlLinkCalendar,$htmlLinkOrganizations,$htmlLinkEvents);
if (SessionUtility::getValue(SessionUtility::OBJECT_NAME_ADMINISTRATOR) != 0) {
    $htmlMenuReportAdministration = new HtmlMenu(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: NULL, text: "Administration");
    $htmlLinkNewPlayerApproval = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "manageSignupApproval.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "New member approval", title: NULL);
    $htmlLinkInvoice = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "manageInvoice.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Invoices", title: NULL);
    $htmlLinkEvent = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "manageEvent.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Events", title: NULL);
    $htmlLinkEventTypeCost = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "manageEventTypeCost.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Event Type Costs", title: NULL);
    $htmlLinkAdministrationArray = array($htmlLinkInvoice, $htmlLinkEvent, $htmlLinkEventTypeCost, $htmlLinkNewPlayerApproval);
    $htmlMenuReportAdministration->setItems($htmlLinkAdministrationArray);
    array_push($levels, $htmlMenuReportAdministration);
}
$htmlLinkLogout = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "logout.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Logout", title: NULL);
array_push($levels, $htmlLinkLogout);
$htmlMenuRoot = new HtmlMenu(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: $levels, text: NULL);
$smarty->assign("navigation", $htmlMenuRoot->getHtmlRoot());