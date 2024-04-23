<?php
declare(strict_types = 1);
namespace Baton\T4g;
use Baton\T4g\Entity\Members;
use Baton\T4g\Model\Constant;
use Baton\T4g\Model\Email;
use Baton\T4g\Model\FormControl;
use Baton\T4g\Utility\DateTimeUtility;
use Baton\T4g\Utility\SessionUtility;
use DateTime;
use Exception;
require_once "init.php";
define("APPROVE_FIELD_NAME", "approve");
define("REJECT_FIELD_NAME", "reject");
define("SAVE_FIELD_NAME", "save");
define("SAVE_TEXT", "Save");
$smarty->assign("title", "Member Approval");
$smarty->assign("heading", "Member Approval");
$smarty->assign("style", "<link href=\"css/manageSignupApproval.css\" rel=\"stylesheet\">");
$errors = NULL;
if (Constant::MODE_SAVE_VIEW == $mode) {
    $member = array();
    $emailAddress = array();
    $approval = array();
    $rejection = array();
    foreach ($_POST as $key => $value) {
        $memberId = count(explode("_", $key)) > 1 ? explode("_", $key)[1] : "";
        if (strpos($key, 'member_') !== false) {
            $member[$memberId] = $value;
        } else if (strpos($key, 'email_') !== false) {
            $emailAddress[$memberId] = $value;
        } else if (strpos($key, 'approveMember_') !== false) {
            $approval[$memberId] = $member[$memberId];
        } else if (strpos($key, 'rejectMember_') !== false) {
            $rejection[$memberId] = $member[$memberId];
        }
    }
    $output .=
        "<script type=\"module\">\n" .
        "  import { dataTable, display, input } from \"./scripts/import.js\";\n" .
        "  let aryMessages = [];\n";
    // update approval date or rejection date and set active flag
    foreach ($approval as $key => $value) {
        $member = $entityManager->find(Constant::ENTITY_MEMBERS, (int) $key);
        if (!isset($memberApproval)) {
            $memberApproval = $entityManager->find(Constant::ENTITY_MEMBERS, (int) SessionUtility::getValue(SessionUtility::OBJECT_NAME_MEMBERID));
        }
        $member->setMemberApproval($memberApproval);
        $member->setMemberApprovalDate(new DateTime());
        $member->setMemberActiveFlag(Constant::FLAG_YES_DATABASE);
        $entityManager->persist($member);
        try {
            $entityManager->flush();
        } catch (Exception $e) {
            $errors = $e->getMessage();
        }
        $output .= "  aryMessages.push(\"Successfully approved " . $value . "\");\n";
        $email = new Email(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), fromName: array(Constant::NAME_ADMIN), fromEmail: array(Constant::EMAIL_ADMIN()), toName: array($value), toEmail: array($emailAddress[$key]), ccName: NULL, ccEmail: NULL, bccName: NULL, bccEmail: NULL, subject: NULL, body: NULL);
        $output .= "  aryMessages.push(\"" . $email->sendApprovedEmail() . "\");\n";
    }
    foreach ($rejection as $key => $value) {
        $member = $entityManager->find(Constant::ENTITY_MEMBERS, (int) $key);
        if (!isset($memberRejection)) {
            $memberRejection = $entityManager->find(Constant::ENTITY_MEMBERS, (int) SessionUtility::getValue(SessionUtility::OBJECT_NAME_MEMBERID));
        }
        $member->setMemberRejection($memberRejection);
        $member->setMemberRejectionDate(new DateTime());
        $entityManager->persist($member);
        try {
            $entityManager->flush();
        } catch (Exception $e) {
            $errors = $e->getMessage();
        }
        $output .= "  aryMessages.push(\"Successfully rejected " . $value . "\");\n";
        $email = new Email(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), fromName: array(Constant::NAME_ADMIN), fromEmail: array(Constant::EMAIL_ADMIN()), toName: array($value), toEmail: array($emailAddress[$key]), ccName: NULL, ccEmail: NULL, bccName: NULL, bccEmail: NULL, subject: NULL, body: NULL);
        $output .= "  aryMessages.push(\"" . $email->sendRejectedEmail() . "\");\n";
    }
    $output .= "  if (aryMessages.length > 0) {display.showMessages({messages: aryMessages});}\n</script>\n";
}
$result = $entityManager->getRepository(Constant::ENTITY_MEMBERS)->getForApproval(indexed: true);
$resultHeaders = $entityManager->getRepository(Constant::ENTITY_MEMBERS)->getForApproval(indexed: false);
if (0 < count($result)) {
    $count = count($result[0]);
    $output .= "<div class=\"buttons center\">\n";
    $buttonSave = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_SAVE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: true, id: Constant::TEXT_SAVE . "_2", maxLength: NULL, name: Constant::TEXT_SAVE . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_SAVE, wrap: NULL);
    $output .= $buttonSave->getHtml();
    $output .= "</div>\n";
    $output .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"dataTbl display\" id=\"dataTblSignupApproval\">\n";
    $headerRow = true;
    $ctr = 0;
    while ($ctr < count($result)) {
        $row = $result[$ctr];
        if ($headerRow) {
            $output .= " <thead>\n";
            $output .= "  <tr>\n";
            for ($index = 1; $index < $count; $index++) {
                $headers = array_keys($resultHeaders[$ctr]);
                $output .= "   <th>" . ucwords($headers[$index]) . "</th>\n";
            }
            $output .= "   <th class=\"center\">Approve\n<br />\n<input id=\"approveMemberCheckAll\" name=\"approveMemberCheckAll\" type=\"checkbox\" /></th>\n";
            $output .= "   <th class=\"center\">Reject\n<br />\n<input id=\"rejectMemberCheckAll\" name=\"rejectMemberCheckAll\" type=\"checkbox\" /></th>\n";
            $output .= "  </tr>\n";
            $output .= " </thead>\n";
            $output .= " <tbody>\n";
            $headerRow = false;
        }
        $output .= "  <tr>\n";
        for ($index = 1; $index < $count; $index ++) {
            $hiddenMember = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::FIELD_NAME_MEMBER . "_" . $row[0], maxLength: NULL, name: Constant::FIELD_NAME_MEMBER . "_" . $row[0], onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $row[1], wrap: NULL);
            $hiddenEmail = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::FIELD_NAME_EMAIL . "_" . $row[0], maxLength: NULL, name: Constant::FIELD_NAME_EMAIL . "_" . $row[0], onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $row[2], wrap: NULL);
            $hiddenUsername = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::FIELD_NAME_USERNAME . "_" . $row[0], maxLength: NULL, name: Constant::FIELD_NAME_USERNAME . "_" . $row[0], onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $row[3], wrap: NULL);
            $output .= "   <td>" . $row[$index] . ($index == 1 ? $hiddenMember->getHtml() : ($index == 2 ? $hiddenEmail->getHtml() : ($index == 3 ? $hiddenUsername->getHtml() : ""))) . "</td>\n";
        }
        $output .= "   <td class=\"center\"><input id=\"approveMember_" . $row[0] . "\" name=\"approveMember_" . $row[0] . "\" type=\"checkbox\" value=\"1\" /></td>\n";
        $output .= "   <td class=\"center\"><input id=\"rejectMember_" . $row[0] . "\" name=\"rejectMember_" . $row[0] . "\" type=\"checkbox\" value=\"1\" /></td>\n";
        $output .= "  </tr>\n";
        $ctr++;
    }
    $output .= " </tbody>\n";
    $output .= "</table>\n";
    $output .= "<div class=\"buttons center\">\n";
    $buttonSave = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_SAVE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: true, id: Constant::TEXT_SAVE, maxLength: NULL, name: Constant::TEXT_SAVE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_SAVE, wrap: NULL);
    $output .= $buttonSave->getHtml();
    $output .= "</div>\n";
} else {
    $output .= "<br />\nNo users require approval";
}
$hiddenMode = new FormControl(SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), NULL, NULL, false, NULL, NULL, NULL, false, Constant::FIELD_NAME_MODE, NULL, Constant::FIELD_NAME_MODE, NULL, NULL, false, NULL, NULL, NULL, NULL, FormControl::TYPE_INPUT_HIDDEN, $mode, NULL);
$output .= $hiddenMode->getHtml();
$smarty->assign("content", $output);
$smarty->assign("contentAdditional", "");
$smarty->display("manage.tpl");