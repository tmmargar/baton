<?php
declare(strict_types = 1);
namespace Baton\T4g;
use Baton\T4g\Model\Constant;
use Baton\T4g\Model\FormControl;
use Baton\T4g\Model\Login;
use Baton\T4g\Model\Security;
use Baton\T4g\Model\Player;
use Baton\T4g\Utility\HtmlUtility;
use Baton\T4g\Utility\SessionUtility;
use DateTime;
use tidy;
require_once "init.php";
define("NAME_FIELD_PLAYERNAME", "username");
define("NAME_FIELD_PASSWORD", "password");
define("NAME_FIELD_REMEMBER_ME", "rememberMe");
define("NAME_FIELD_LOGIN", "login");
$smarty->assign("title", "Twirling for Grace Member Login");
$smarty->assign("heading", "Login");
$smarty->assign("action", $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"]);
$smarty->assign("formName", "frmLogin");
$output = "";
$mode = isset($_POST[Constant::FIELD_NAME_MODE]) ? $_POST[Constant::FIELD_NAME_MODE] : "";
if (Constant::MODE_LOGIN == $mode) {
  $security = new Security(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, username: $_POST["username"], password: $_POST["password"]);
  if ($security->login()) {
    $pageName = "home.php";
    if (! empty($_SERVER["QUERY_STRING"])) {
      $pageName = $_SERVER["QUERY_STRING"];
    }
    header("Location:" . $pageName);
    exit();
  } else {
    $output .=
      "<script type=\"module\">" .
      "  import { dataTable, display, input } from \"./scripts/import.js\";\n" .
      "  display.showErrors({errors: [\"Login failed. Please try again\"]});" .
    "</script>";
  }
}
$output .= "<div class=\"responsive responsive--2cols responsive--collapse\">";
$textBoxUsername = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_USERNAME, autoComplete: "username", autoFocus: true, checked: NULL, class: NULL, cols: NULL, disabled: false, id: NAME_FIELD_PLAYERNAME, maxLength: 30, name: NAME_FIELD_PLAYERNAME, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 10, suffix: NULL, type: FormControl::TYPE_INPUT_TEXTBOX, value: NULL, wrap: NULL);
$output .= " <div class=\"responsive-cell responsive-cell--head\"><label class=\"label\" for=\"" . $textBoxUsername->getId() . "\">Username:</label></div>";
$output .= " <div class=\"responsive-cell\">" . $textBoxUsername->getHtml() . "</div>";
$textBoxPassword = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_PASSWORD, autoComplete: "current-password", autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: NAME_FIELD_PASSWORD, maxLength: NULL, name: NAME_FIELD_PASSWORD, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 10, suffix: NULL, type: FormControl::TYPE_INPUT_PASSWORD, value: NULL, wrap: NULL);
$output .= " <div class=\"responsive-cell responsive-cell--head\"><label class=\"label\" for=\"" . $textBoxPassword->getId() . "\">Password:</label></div>";
$output .= " <div class=\"responsive-cell\">" . $textBoxPassword->getHtml() . "</div>";
// $output .= "<div class=\"label\">Remember Me:</div>";
// $output .= "<div class=\"input\">";
// $output .= HtmlUtility::buildCheckbox(false, false, false, NULL, false, Base::build(NAME_FIELD_REMEMBER_ME, NULL), Base::build(NAME_FIELD_REMEMBER_ME, NULL), NULL, NULL);
// $output .= "</div>";
// $output .= "<div class=\"clear\"></div>";
$buttonLogin = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_LOGIN, autoComplete: NULL, autoFocus: false, checked: NULL, class: array("button-icon button-icon-separator icon-border-caret-right"), cols: NULL, disabled: false, id: NAME_FIELD_LOGIN, maxLength: NULL, name: NAME_FIELD_LOGIN, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: ucwords(NAME_FIELD_LOGIN), wrap: NULL);
$output .= $buttonLogin->getHtml();
$output .= " <div class=\"responsive-cell\"></div>";
$output .= " <div class=\"responsive-cell\">" . HtmlUtility::buildLink(href: "resetPassword.php", id: NULL, target: NULL, title: "Forgot Password", text: "Forgot Password") . "&nbsp;&nbsp;&nbsp;" . HtmlUtility::buildLink(href: "signup.php", id: NULL, target: NULL, title: "New Member Signup", text: "New Member Signup");
$hiddenMode = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::FIELD_NAME_MODE, maxLength: NULL, name: Constant::FIELD_NAME_MODE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $mode, wrap: NULL);
$output .= $hiddenMode->getHtml();
$output .= "</div>";
$smarty->assign("content", $output);
$outputTemplate = $smarty->fetch("login.tpl");
$outputTidy = new tidy;
$outputTidy->parseString($outputTemplate, $configTidy, "utf8");
$outputTidy->cleanRepair();
echo $outputTidy;