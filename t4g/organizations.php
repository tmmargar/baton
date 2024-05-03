<?php
declare(strict_types = 1);
namespace Baton\T4g;
use Baton\T4g\Model\Constant;
use Baton\T4g\Utility\HtmlUtility;
use DateTime;
require_once "init.php";
$smarty->assign("title", "T4G Organizations");
$smarty->assign("heading", "");
$output = "";
$output .= "<div class=\"title\">Organizations</div>\n";
$output .= "<p>Below is a list of the organizations we have decided to participate in with links to the organization's website for more information.</p><br>\n";
$now = new DateTime();
$organizations = $entityManager->getRepository(Constant::ENTITY_ORGANIZATIONS)->getAll();
foreach ($organizations as $organization) {
    $output .= "<div>";
    $output .= HtmlUtility::buildLink(href: "" . $organization->getOrganizationUrl() . "", id: NULL, target: "_blank", title: $organization->getOrganizationName(), text: $organization->getOrganizationName()) . " - " . $organization->getOrganizationDescription();
    $output .= "</div>\n";
}
if (!$organizations) {
    $output .= "No organizations";
}
$smarty->assign("content", $output);
$smarty->display("organizations.tpl");