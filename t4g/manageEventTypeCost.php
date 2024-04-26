<?php
declare(strict_types = 1);
namespace Baton\T4g;
use Baton\T4g\Entity\EventTypes;
use Baton\T4g\Entity\EventTypeCosts;
use Baton\T4g\Model\Constant;
use Baton\T4g\Model\FormControl;
use Baton\T4g\Model\FormOption;
use Baton\T4g\Model\FormSelect;
use Baton\T4g\Utility\DateTimeUtility;
use Baton\T4g\Utility\SessionUtility;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
require_once "init.php";
define("EVENT_TYPE_COST_FIELD_LABEL", "Cost");
define("EVENT_TYPE_COST_FIELD_NAME", "eventTypeCost");
define("EVENT_TYPE_FIELD_LABEL", "Event type");
define("EVENT_TYPE_FIELD_NAME", "eventType");
define("EVENT_TYPE_STUDENT_COUNT_FIELD_LABEL", "Student Count");
define("EVENT_TYPE_STUDENT_COUNT_FIELD_NAME", "eventTypeStudentCount");
define("EVENT_TYPE_TIME_LENGTH_FIELD_LABEL", "Time Length");
define("EVENT_TYPE_TIME_LENGTH_FIELD_NAME", "eventTypeTimeLength");
define("EVENT_TYPE_ID_FIELD_LABEL", "Id");
$smarty->assign("title", "Manage Event Type Cost");
$smarty->assign("heading", "Manage Event Type Cost");
$smarty->assign("style", "<link href=\"css/manageEventTypeCost.css\" rel=\"stylesheet\">");
$errors = NULL;
$outputAdditional = "";
$now = new DateTime();
if (Constant::MODE_CREATE == $mode || Constant::MODE_MODIFY == $mode) {
    $idValues = NULL == $ids ? array(NULL, NULL, NULL) : explode("::", $ids);
    $etFind = NULL == $ids ? NULL : $entityManager->find(Constant::ENTITY_EVENT_TYPES, $idValues[0]);
    $eventTypeCosts = Constant::MODE_CREATE == $mode ? array() : $entityManager->getRepository(Constant::ENTITY_EVENT_TYPE_COSTS)->getById(eventType: $etFind, eventTypeTimeLength: (int) $idValues[1], eventTypeStudentCount: (int) $idValues[2]);
    $output .= " <div class=\"buttons center\">\n";
    $buttonSave = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_SAVE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_SAVE . "_2", maxLength: NULL, name: Constant::TEXT_SAVE . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_SAVE, wrap: NULL);
    $output .= $buttonSave->getHtml();
    $buttonReset = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_RESET, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_RESET . "_2", maxLength: NULL, name: Constant::TEXT_RESET . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_RESET, wrap: NULL, noValidate: true);
    $output .= $buttonReset->getHtml();
    $buttonCancel = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CANCEL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CANCEL . "_2", maxLength: NULL, name: Constant::TEXT_CANCEL . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CANCEL, wrap: NULL, noValidate: true);
    $output .= $buttonCancel->getHtml();
    $output .= " </div>\n";
    $output .= "<div class=\"responsive responsive--2cols responsive--collapse\">";
    if (Constant::MODE_CREATE == $mode || (Constant::MODE_MODIFY == $mode && DEFAULT_VALUE_BLANK != $ids)) {
        $ctr = 0;
        $ary = explode(Constant::DELIMITER_DEFAULT, $ids);
        foreach ($ary as $idTemp) {
            $idTempValues = explode("::", $idTemp);
            $id = $idTempValues[0];
            $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\">" . EVENT_TYPE_ID_FIELD_LABEL . ": </div>";
            $output .= " <div class=\"responsive-cell responsive-cell-value\">" . ($id == "" ? "NEW" : $id) . "</div>";
            $eventTypesSelect = $entityManager->getRepository(Constant::ENTITY_EVENT_TYPES)->getById(eventTypeId: NULL);
            if (NULL !== $eventTypesSelect) {
                $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . EVENT_TYPE_FIELD_NAME . "_" . $id . "\">" . EVENT_TYPE_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>\n";
                $selectEventType = new FormSelect(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_EVENT_TYPE, class: NULL, disabled: Constant::MODE_MODIFY == $mode ? true : false, id: EVENT_TYPE_FIELD_NAME . "_" . $id, multiple: false, name: EVENT_TYPE_FIELD_NAME . "_" . $id, onClick: NULL, readOnly: false, size: 1, suffix: NULL, value: NULL);
                $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $selectEventType->getHtml();
                $eventTypeIdTemp = isset($_POST[EVENT_TYPE_FIELD_NAME . "_" . $id]) ? $_POST[EVENT_TYPE_FIELD_NAME . "_" . $id] : (0 < count($eventTypeCosts) ? $eventTypeCosts[$ctr]->getEventTypes()->getEventTypeId() : "");
                $option = new FormOption(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: $eventTypeIdTemp, suffix: NULL, text: Constant::TEXT_NONE, value: DEFAULT_VALUE_BLANK);
                $output .= $option->getHtml();
                foreach ($eventTypesSelect as $eventTypeSelect) {
                    $option = new FormOption(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: $eventTypeIdTemp, suffix: NULL, text: $eventTypeSelect->getEventTypeName(), value: $eventTypeSelect->getEventTypeId());
                    $output .= $option->getHtml();
                }
                $output .= "     </select>\n";
                $output .= "    </div>\n";
            }
            $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . EVENT_TYPE_TIME_LENGTH_FIELD_NAME . "_" . $id . "\">" . EVENT_TYPE_TIME_LENGTH_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>";
            $textBoxTimeLength = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: true, checked: NULL, class: NULL, cols: NULL, disabled: Constant::MODE_MODIFY == $mode ? true : false, id: EVENT_TYPE_TIME_LENGTH_FIELD_NAME . "_" . $id, maxLength: NULL, name: EVENT_TYPE_TIME_LENGTH_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 4, suffix: NULL, type: FormControl::TYPE_INPUT_NUMBER, value: (int) ((0 < count($eventTypeCosts)) ? $eventTypeCosts[0]->getEventTypeTimeLength() : 0), wrap: NULL);
            $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxTimeLength->getHtml() . "</div>";
            $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . EVENT_TYPE_STUDENT_COUNT_FIELD_NAME . "_" . $id . "\">" . EVENT_TYPE_STUDENT_COUNT_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>";
            $textBoxStudentCount = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: true, checked: NULL, class: NULL, cols: NULL, disabled: Constant::MODE_MODIFY == $mode ? true : false, id: EVENT_TYPE_STUDENT_COUNT_FIELD_NAME . "_" . $id, maxLength: NULL, name: EVENT_TYPE_STUDENT_COUNT_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 4, suffix: NULL, type: FormControl::TYPE_INPUT_NUMBER, value: (int) ((0 < count($eventTypeCosts)) ? $eventTypeCosts[0]->getEventTypeStudentCount() : 0), wrap: NULL);
            $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxStudentCount->getHtml() . "</div>";
            $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . EVENT_TYPE_STUDENT_COUNT_FIELD_NAME . "_" . $id . "\">" . EVENT_TYPE_COST_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>";
            $textBoxCost = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: true, checked: NULL, class: NULL, cols: NULL, disabled: false, id: EVENT_TYPE_COST_FIELD_NAME . "_" . $id, maxLength: NULL, name: EVENT_TYPE_COST_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 4, suffix: NULL, type: FormControl::TYPE_INPUT_NUMBER, value: (int) ((0 < count($eventTypeCosts)) ? $eventTypeCosts[0]->getEventTypeCost() : 0), wrap: NULL);
            $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxCost->getHtml() . "</div>";
            $ctr++;
        }
        $output .= "<div class=\"buttons center\">\n";
        $buttonSave = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_SAVE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_SAVE, maxLength: NULL, name: Constant::TEXT_SAVE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_SAVE, wrap: NULL);
        $output .= $buttonSave->getHtml();
        $buttonReset = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_RESET, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_RESET, maxLength: NULL, name: Constant::TEXT_RESET, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_RESET, wrap: NULL, noValidate: true);
        $output .= $buttonReset->getHtml();
    }
    $buttonCancel = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CANCEL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CANCEL, maxLength: NULL, name: Constant::TEXT_CANCEL, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CANCEL, wrap: NULL, noValidate: true);
    $output .= $buttonCancel->getHtml();
    $output .= "</div>\n";
    $hiddenMode = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::FIELD_NAME_MODE, maxLength: NULL, name: Constant::FIELD_NAME_MODE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $mode, wrap: NULL);
    $output .= $hiddenMode->getHtml();
    $hiddenSelectedRows = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: SELECTED_ROWS_FIELD_NAME, maxLength: NULL, name: SELECTED_ROWS_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $ids, wrap: NULL);
    $output .= $hiddenSelectedRows->getHtml();
    $output .= "</div>\n";
} elseif (Constant::MODE_SAVE_CREATE == $mode || Constant::MODE_SAVE_MODIFY == $mode || Constant::MODE_SAVE_PAYMENT == $mode) {
    $ary = explode(Constant::DELIMITER_DEFAULT, $ids);
    foreach ($ary as $idTemp) {
        $idTempValues = explode("::", $idTemp);
        $id = $idTempValues[0];
        $eventTypeCost = (isset($_POST[EVENT_TYPE_COST_FIELD_NAME . "_" . $id])) ? $_POST[EVENT_TYPE_COST_FIELD_NAME . "_" . $id] : 0;
        $eventTypeId = Constant::MODE_SAVE_CREATE == $mode ? $_POST[EVENT_TYPE_FIELD_NAME . "_" . $id] : $id;
        $eventTypeTimeLength = Constant::MODE_SAVE_CREATE == $mode ? $_POST[EVENT_TYPE_TIME_LENGTH_FIELD_NAME . "_" . $id] :  $idTempValues[1];
        $eventTypeStudentCount = Constant::MODE_SAVE_CREATE == $mode ? $_POST[EVENT_TYPE_STUDENT_COUNT_FIELD_NAME . "_" . $id] :  $idTempValues[2];
        if (Constant::MODE_SAVE_CREATE == $mode) {
            $etc = new EventTypeCosts();
            $etc->setEventTypeCost((float) $eventTypeCost);
            $etc->setEventTypeStudentCount((int) $eventTypeStudentCount);
            $etc->setEventTypeTimeLength((int) $eventTypeTimeLength);
            $et = $entityManager->find(Constant::ENTITY_EVENT_TYPES, $eventTypeId);
            $etc->setEventTypes($et);
            $entityManager->persist($etc);
            try {
                $entityManager->flush();
            } catch (Exception $e) {
                $errors = $e->getMessage();
            }
        } elseif (Constant::MODE_SAVE_MODIFY == $mode) {
            $etFind = $entityManager->find(Constant::ENTITY_EVENT_TYPES, $eventTypeId);
            $etc = $entityManager->getRepository(Constant::ENTITY_EVENT_TYPE_COSTS)->findOneBy(array("eventTypes" => $etFind, "eventTypeTimeLength" => $eventTypeTimeLength, "eventTypeStudentCount" => $eventTypeStudentCount));
            $etc->setEventTypeCost((float) $eventTypeCost);
            $entityManager->persist($etc);
            try {
                $entityManager->flush();
            } catch (Exception $e) {
                $errors = $e->getMessage();
            }
        }
        if (isset($errors)) {
            $output .=
                "<script type=\"module\">\n" .
                "  import { dataTable, display, input } from \"./scripts/import.js\";\n" .
                "  display.showErrors({errors: [ \"" . $errors . "\" ]});\n" .
                "</script>\n";
        }
//         $ids = DEFAULT_VALUE_BLANK;
    }
    $ids = DEFAULT_VALUE_BLANK;
    $mode = Constant::MODE_VIEW;
}
if (Constant::MODE_VIEW == $mode || Constant::MODE_DELETE == $mode || Constant::MODE_CONFIRM == $mode) {
    if (Constant::MODE_CONFIRM == $mode) {
        if ($ids != DEFAULT_VALUE_BLANK) {
            $ary = explode(Constant::DELIMITER_DEFAULT, $ids);
//             foreach ($ary as $idTemp) {
            $idTempValues = explode("::", $ary[0]);
                $id = $idTempValues[0];
//             }
            $eventTypeId = $id;
            $eventTypeTimeLength = $idTempValues[1];
            $eventTypeStudentCount = $idTempValues[2];
            $etFind = $entityManager->find(Constant::ENTITY_EVENT_TYPES, $eventTypeId);
            $etc = $entityManager->getRepository(Constant::ENTITY_EVENT_TYPE_COSTS)->findOneBy(array("eventTypes" => $etFind, "eventTypeTimeLength" => $eventTypeTimeLength, "eventTypeStudentCount" => $eventTypeStudentCount));
            $entityManager->remove($etc);
            try {
                $entityManager->flush();
            } catch (Exception $e) {
                $errors = $e->getMessage();
            }
            if (isset($errors)) {
                $output .=
                    "<script type=\"module\">\n" .
                    "  import { dataTable, display, input } from \"./scripts/import.js\";\n" .
                    "  display.showErrors({errors: [ \"" . $errors . "\" ]});\n" .
                    "</script>\n";
            }
            $ids = DEFAULT_VALUE_BLANK;
        }
        $mode = Constant::MODE_VIEW;
    }
    $output .= "<div class=\"buttons center\">\n";
    if (Constant::MODE_VIEW == $mode) {
        $buttonCreate = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CREATE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CREATE, maxLength: NULL, name: Constant::TEXT_CREATE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CREATE, wrap: NULL);
        $output .= $buttonCreate->getHtml();
        $buttonModify = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_MODIFY, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: true, id: Constant::TEXT_MODIFY, maxLength: NULL, name: Constant::TEXT_MODIFY, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_MODIFY, wrap: NULL);
        $output .= $buttonModify->getHtml();
        $buttonDelete = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_DELETE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: true, id: Constant::TEXT_DELETE, maxLength: NULL, name: Constant::TEXT_DELETE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_DELETE, wrap: NULL);
        $output .= $buttonDelete->getHtml();
    } else if (Constant::MODE_DELETE == $mode) {
        $buttonDelete = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CONFIRM_DELETE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CONFIRM_DELETE, maxLength: NULL, name: Constant::TEXT_CONFIRM_DELETE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CONFIRM_DELETE, wrap: NULL);
        $output .= $buttonDelete->getHtml();
        $buttonDelete = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CANCEL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CANCEL, maxLength: NULL, name: Constant::TEXT_CANCEL, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CANCEL, wrap: NULL, noValidate: true);
        $output .= $buttonDelete->getHtml();
    }
    $output .= "</div>\n";
    $hiddenMode = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::FIELD_NAME_MODE, maxLength: NULL, name: Constant::FIELD_NAME_MODE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $mode, wrap: NULL);
    $output .= $hiddenMode->getHtml();
    $hiddenSelectedRows = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: SELECTED_ROWS_FIELD_NAME, maxLength: NULL, name: SELECTED_ROWS_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $ids, wrap: NULL);
    $output .= $hiddenSelectedRows->getHtml();
    $id = ("" == $ids) ? NULL : (int) $ids;
    $idValues = NULL == $ids ? array(NULL, NULL, NULL) : explode("::", $ids);
    $etFind = NULL == $ids ? NULL : $entityManager->find(Constant::ENTITY_EVENT_TYPES, $idValues[0]);
    $results = $entityManager->getRepository(Constant::ENTITY_EVENT_TYPE_COSTS)->getById(eventType: $etFind, eventTypeTimeLength: (int) $idValues[1], eventTypeStudentCount: (int) $idValues[2]);
    $output .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"display\" id=\"" . Constant::ID_TABLE_DATA . "\" style=\"width: 100%;\">\n";
    $output .= " <thead>\n";
    $output .= "  <th>#</th>\n";
    $output .= "  <th>Name</th>\n";
    $output .= "  <th>Time Length</th>\n";
    $output .= "  <th>Student Count</th>\n";
    $output .= "  <th>Cost</th>\n";
    $output .= "  <th>Id</th>\n";
    $output .= " </thead>\n";
    $output .= " <tbody>\n";
    foreach($results as $eventTypeCost) {
        $output .= "  <tr>\n";
        $output .= "   <td>" . $eventTypeCost->getEventTypes()->getEventTypeId() . "</td>\n";
        $output .= "   <td>" . $eventTypeCost->getEventTypes()->getEventTypeName() . "</td>\n";
        $output .= "   <td>" . $eventTypeCost->getEventTypeTimeLength() . "</td>\n";
        $output .= "   <td>" . $eventTypeCost->getEventTypeStudentCount() . "</td>\n";
        $output .= "   <td class=\"positive\">$" . $eventTypeCost->getEventTypeCost() . "</td>\n";
        $output .= "   <td>" . $eventTypeCost->getEventTypes()->getEventTypeId() . "::" . $eventTypeCost->getEventTypeTimeLength() . "</td>\n";
        $output .= "  </tr>\n";
    }
    $output .= " </tbody>\n";
    $output .= "</table>\n";
    if (Constant::MODE_VIEW == $mode) {
        $output .= "<div class=\"buttons center\">\n";
        $buttonCreate = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CREATE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CREATE . "_2", maxLength: NULL, name: Constant::TEXT_CREATE . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CREATE, wrap: NULL);
        $output .= $buttonCreate->getHtml();
        $buttonModify = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_MODIFY, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: true, id: Constant::TEXT_MODIFY . "_2", maxLength: NULL, name: Constant::TEXT_MODIFY . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_MODIFY, wrap: NULL);
        $output .= $buttonModify->getHtml();
        $buttonDelete = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_DELETE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: true, id: Constant::TEXT_DELETE . "_2", maxLength: NULL, name: Constant::TEXT_DELETE . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_DELETE, wrap: NULL);
        $output .= $buttonDelete->getHtml();
        $output .= "</div>\n";
    }
}
$smarty->assign("content", $output);
$smarty->assign("contentAdditional", "");
$smarty->display("manage.tpl");