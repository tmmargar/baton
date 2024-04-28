<?php
declare(strict_types = 1);
namespace Baton\T4g;
use Baton\T4g\Entity\Events;
use Baton\T4g\Entity\EventTypes;
use Baton\T4g\Model\Constant;
use Baton\T4g\Model\FormControl;
use Baton\T4g\Model\FormOption;
use Baton\T4g\Model\FormSelect;
use Baton\T4g\Utility\DateTimeUtility;
use Baton\T4g\Utility\SessionUtility;
use DateInterval;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
require_once "init.php";
define("EVENT_TYPE_FIELD_LABEL", "Event type");
define("EVENT_TYPE_FIELD_NAME", "eventType");
define("EVENT_START_DATE_FIELD_LABEL", "Start date");
define("EVENT_START_DATE_FIELD_NAME", "eventStartDate");
define("EVENT_END_DATE_FIELD_LABEL", "End date");
define("EVENT_END_DATE_FIELD_NAME", "eventEndDate");
define("EVENT_NAME_FIELD_LABEL", "Name");
define("EVENT_NAME_FIELD_NAME", "eventName");
define("EVENT_DESCRIPTION_FIELD_LABEL", "Description");
define("EVENT_DESCRIPTION_FIELD_NAME", "eventDescription");
define("EVENT_LOCATION_FIELD_LABEL", "Location");
define("EVENT_LOCATION_FIELD_NAME", "eventLocation");
define("EVENT_URL_FIELD_LABEL", "Url");
define("EVENT_URL_FIELD_NAME", "eventUrl");
define("EVENT_ID_FIELD_LABEL", "Id");
$smarty->assign("title", "Manage Event");
$smarty->assign("heading", "Manage Event");
$smarty->assign("style", "<link href=\"css/manageEvent.css\" rel=\"stylesheet\">");
$errors = NULL;
$outputAdditional = "";
$now = new DateTime();
if (Constant::MODE_CREATE == $mode || Constant::MODE_MODIFY == $mode) {
    $events = $entityManager->getRepository(Constant::ENTITY_EVENTS)->getById(eventId: (int) $ids);
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
        echo "<br>".$ids;
        $ary = explode(Constant::DELIMITER_DEFAULT, $ids);
        foreach ($ary as $id) {
            $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\">" . EVENT_ID_FIELD_LABEL . ": </div>";
            $output .= " <div class=\"responsive-cell responsive-cell-value\">" . ($id == "" ? "NEW" : $id) . "</div>";
            $eventTypesSelect = $entityManager->getRepository(Constant::ENTITY_EVENT_TYPES)->getById(eventTypeId: NULL);
            if (NULL !== $eventTypesSelect) {
                $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . EVENT_TYPE_FIELD_NAME . "_" . $id . "\">" . EVENT_TYPE_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>\n";
                $selectEventType = new FormSelect(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_EVENT_TYPE, class: NULL, disabled: false, id: EVENT_TYPE_FIELD_NAME . "_" . $id, multiple: false, name: EVENT_TYPE_FIELD_NAME . "_" . $id, onClick: NULL, readOnly: false, size: 1, suffix: NULL, value: NULL);
                $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $selectEventType->getHtml();
                $option = new FormOption(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: (0 < count($events) ? $events[$ctr]->getEventType()->getEventTypeId() : ""), suffix: NULL, text: Constant::TEXT_NONE, value: DEFAULT_VALUE_BLANK);
                $output .= $option->getHtml();
                foreach ($eventTypesSelect as $eventTypeSelect) {
                    $option = new FormOption(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: (0 < count($events) ? $events[$ctr]->getEventType()->getEventTypeId() : ""), suffix: NULL, text: $eventTypeSelect->getEventTypeName(), value: $eventTypeSelect->getEventTypeId());
                    $output .= $option->getHtml();
                }
                $output .= "     </select>\n";
                $output .= "    </div>\n";
            }
            $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . EVENT_START_DATE_FIELD_NAME . "_" . $id . "\">" . EVENT_START_DATE_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>";
            $textBoxStartDate = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_START_DATE, autoComplete: NULL, autoFocus: true, checked: NULL, class: array("timePicker"), cols: NULL, disabled: false, id: EVENT_START_DATE_FIELD_NAME . "_" . $id, maxLength: 30, name: EVENT_START_DATE_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 20, suffix: NULL, type: FormControl::TYPE_INPUT_DATE_TIME, value: ((0 < count($events)) ? DateTimeUtility::formatDatabaseDateTime(value: $events[0]->getEventStartDate()) : DateTimeUtility::formatDatabaseDateTime(value: $now)), wrap: NULL);
            $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxStartDate->getHtml() . "</div>";
            $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . EVENT_END_DATE_FIELD_NAME . "_" . $id . "\">" . EVENT_END_DATE_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>";
            $endDate = new DateTime();
            $endDate->add(new \DateInterval("P1D"));
            $textBoxEndDate = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_END_DATE, autoComplete: NULL, autoFocus: false, checked: NULL, class: array("timePicker"), cols: NULL, disabled: false, id: EVENT_END_DATE_FIELD_NAME . "_" . $id, maxLength: 30, name: EVENT_END_DATE_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 20, suffix: NULL, type: FormControl::TYPE_INPUT_DATE_TIME, value: ((0 < count($events)) ? DateTimeUtility::formatDatabaseDateTime(value: $events[0]->getEventEndDate()) : DateTimeUtility::formatDatabaseDateTime(value: $endDate)), wrap: NULL);
            $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxEndDate->getHtml() . "</div>";
            $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . EVENT_NAME_FIELD_NAME . "_" . $id . "\">" . EVENT_NAME_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>";
            $textBoxName = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: EVENT_NAME_FIELD_NAME . "_" . $id, maxLength: 30, name: EVENT_NAME_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 30, suffix: NULL, type: FormControl::TYPE_INPUT_TEXTBOX, value: (0 < count($events)) ? $events[0]->getEventName() : "", wrap: NULL);
            $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxName->getHtml() . "</div>";
            $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . EVENT_DESCRIPTION_FIELD_NAME . "_" . $id . "\">" . EVENT_DESCRIPTION_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>";
            $textBoxDescription = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: 60, disabled: false, id: EVENT_DESCRIPTION_FIELD_NAME . "_" . $id, maxLength: 400, name: EVENT_DESCRIPTION_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: 10, size: 20, suffix: NULL, type: FormControl::TYPE_INPUT_TEXTAREA, value: (0 < count($events)) ? $events[0]->getEventDescription() : "", wrap: NULL);
            $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxDescription->getHtml() . "</div>";
            $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . EVENT_LOCATION_FIELD_NAME . "_" . $id . "\">" . EVENT_LOCATION_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>";
            $textBoxLocation = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: EVENT_LOCATION_FIELD_NAME . "_" . $id, maxLength: 400, name: EVENT_LOCATION_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 20, suffix: NULL, type: FormControl::TYPE_INPUT_TEXTBOX, value: (0 < count($events)) ? $events[0]->getEventLocation() : "", wrap: NULL);
            $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxLocation->getHtml() . "</div>";
            $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . EVENT_URL_FIELD_NAME . "_" . $id . "\">" . EVENT_URL_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>";
            $textBoxUrl = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: EVENT_URL_FIELD_NAME . "_" . $id, maxLength: 200, name: EVENT_URL_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: false, rows: NULL, size: 20, suffix: NULL, type: FormControl::TYPE_INPUT_TEXTBOX, value: (0 < count($events)) ? $events[0]->getEventUrl() : "", wrap: NULL);
            $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxUrl->getHtml() . "</div>";
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
} elseif (Constant::MODE_SAVE_CREATE == $mode || Constant::MODE_SAVE_MODIFY == $mode) {
    $ary = explode(Constant::DELIMITER_DEFAULT, $ids);
    foreach ($ary as $id) {
        $eventStartDateString = (isset($_POST[EVENT_START_DATE_FIELD_NAME . "_" . $id])) ? $_POST[EVENT_START_DATE_FIELD_NAME . "_" . $id] : "";
        $eventEndDateString = (isset($_POST[EVENT_END_DATE_FIELD_NAME . "_" . $id])) ? $_POST[EVENT_END_DATE_FIELD_NAME . "_" . $id] : "";
        $eventTypeId = (isset($_POST[EVENT_TYPE_FIELD_NAME . "_" . $id])) ? $_POST[EVENT_TYPE_FIELD_NAME . "_" . $id] : 0;
        $eventName = (isset($_POST[EVENT_NAME_FIELD_NAME . "_" . $id])) ? $_POST[EVENT_NAME_FIELD_NAME . "_" . $id] :  "";
        $eventDescription = (isset($_POST[EVENT_DESCRIPTION_FIELD_NAME . "_" . $id])) ? $_POST[EVENT_DESCRIPTION_FIELD_NAME . "_" . $id] :  "";
        $eventLocation = (isset($_POST[EVENT_LOCATION_FIELD_NAME . "_" . $id])) ? $_POST[EVENT_LOCATION_FIELD_NAME . "_" . $id] :  "";
        $eventUrl = (isset($_POST[EVENT_URL_FIELD_NAME . "_" . $id])) ? $_POST[EVENT_URL_FIELD_NAME . "_" . $id] :  "";
        if ("" == $eventUrl) {
            $eventUrl = NULL;
        }
        if (Constant::MODE_SAVE_CREATE == $mode) {
            $ev = new Events();
            $ev->setEventDescription($eventDescription);
            $eventEndDate = new DateTime(datetime: $eventEndDateString);
            $ev->setEventEndDate($eventEndDate);
            $ev->setEventLocation($eventLocation);
            $ev->setEventName($eventName);
            $eventStartDate = new DateTime(datetime: $eventStartDateString);
            $ev->setEventStartDate($eventStartDate);
            $etFind = $entityManager->find(Constant::ENTITY_EVENT_TYPES, $eventTypeId);
            $ev->setEventType($etFind);
            $ev->setEventUrl($eventUrl);
            $entityManager->persist($ev);
            try {
                $entityManager->flush();
            } catch (Exception $e) {
                $errors = $e->getMessage();
            }
        } elseif (Constant::MODE_SAVE_MODIFY == $mode) {
            $ev = $entityManager->find(Constant::ENTITY_EVENTS, $ids);
            $ev->setEventDescription($eventDescription);
            $eventEndDate = new DateTime(datetime: $eventEndDateString);
            $ev->setEventEndDate($eventEndDate);
            $ev->setEventLocation($eventLocation);
            $ev->setEventName($eventName);
            $eventStartDate = new DateTime(datetime: $eventStartDateString);
            $ev->setEventStartDate($eventStartDate);
            $etFind = $entityManager->find(Constant::ENTITY_EVENT_TYPES, $eventTypeId);
            $ev->setEventType($etFind);
            $ev->setEventUrl($eventUrl);
            $entityManager->persist($ev);
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
            $ev = $entityManager->find(Constant::ENTITY_EVENTS, $ids);
            $entityManager->remove($ev);
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
    $results = $entityManager->getRepository(Constant::ENTITY_EVENTS)->getById(eventId: $id);
    $output .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"display\" id=\"" . Constant::ID_TABLE_DATA . "\" style=\"width: 100%;\">\n";
    $output .= " <thead>\n";
    // event type, start date, end date, name, description, location, url
    $output .= "  <th>#</th>\n";
    $output .= "  <th>Type</th>\n";
    $output .= "  <th>Start Date</th>\n";
    $output .= "  <th>End Date</th>\n";
    $output .= "  <th>Name</th>\n";
    $output .= "  <th>Description</th>\n";
    $output .= "  <th>Location</th>\n";
    $output .= "  <th>Url</th>\n";
    $output .= " </thead>\n";
    $output .= " <tbody>\n";
    $index = 0;
    foreach($results as $event) {
        $output .= "  <tr>\n";
        $output .= "   <td>" . $event->getEventId() . "</td>\n";
        $output .= "   <td>" . $event->getEventType()->getEventTypeName() . "</td>\n";
        $output .= "   <td>" . DateTimeUtility::formatDisplayDateTime(value: $event->getEventStartDate()) . "</td>\n";
        $output .= "   <td>" . DateTimeUtility::formatDisplayDateTime(value: $event->getEventEndDate()) . "</td>\n";
        $output .= "   <td>" . $event->getEventName() . "</td>\n";
        if (30 < strlen($event->getEventDescription())) {
            $output .= "   <td><a href=\"#\" id=\"event_description_link_" . $index . "\" title=\"View description\">View</a></td>\n";
            $outputAdditional .= "<script type=\"module\">\n";
            $outputAdditional .= "  document.querySelector(\"#event_description_link_" . $index . "\").addEventListener(\"click\", (evt) => document.querySelector(\"#dialogEventDescription_" . $index . "\").showModal());\n";
            $outputAdditional .= "</script>\n";
            $outputAdditional .= "<dialog class=\"child dialog\" id=\"dialogEventDescription_" . $index . "\">\n";
            $outputAdditional .= " <form method=\"dialog\">\n";
            $outputAdditional .= "  <header>\n";
            $outputAdditional .= "   <h2>Event Description</h2>\n";
            $outputAdditional .= "   <button class=\"dialogButton\" id=\"dialogEventDescription_" . $index . "-header--cancel-btn\">X</button>\n";
            $outputAdditional .= "  </header>\n";
            $outputAdditional .= "  <main>\n";
            $outputAdditional .= "   <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"display\" id=\"dataTblEventDescription\" style=\"width: 100%;\">\n";
            $outputAdditional .= "    <thead>\n";
            $outputAdditional .= "     <tr>\n";
            $outputAdditional .= "      <th>Description</th>\n";
            $outputAdditional .= "     </tr>\n";
            $outputAdditional .= "    </thead>\n";
            $outputAdditional .= "    <tbody>\n";
            $outputAdditional .= "     <tr>\n";
            $outputAdditional .= "      <td>" . $event->getEventDescription() . "</td>\n";
            $outputAdditional .= "     </tr>\n";
            $outputAdditional .= "    </tbody>\n";
            $outputAdditional .= "   </table>\n";
            $outputAdditional .= "  </main>\n";
            $outputAdditional .= " </form>\n";
            $outputAdditional .= "</dialog>\n";
        } else {
            $output .= "   <td>" . $event->getEventDescription() . "</td>\n";
        }
        $output .= "   <td>" . $event->getEventLocation() . "</td>\n";
        $output .= "   <td>" . $event->getEventUrl() . "</td>\n";
        $output .= "  </tr>\n";
        $index++;
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
$smarty->assign("contentAdditional", $outputAdditional);
$smarty->display("manage.tpl");