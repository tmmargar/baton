<?php
declare(strict_types = 1);
namespace Baton\T4g;
use Baton\T4g\Entity\EventTypes;
use Baton\T4g\Entity\InvoiceLines;
use Baton\T4g\Entity\Invoices;
use Baton\T4g\Entity\InvoicePayments;
use Baton\T4g\Entity\Members;
use Baton\T4g\Entity\Students;
use Baton\T4g\Model\Constant;
use Baton\T4g\Model\FormControl;
use Baton\T4g\Model\FormOption;
use Baton\T4g\Model\FormSelect;
use Baton\T4g\Utility\DateTimeUtility;
use Baton\T4g\Utility\HtmlUtility;
use Baton\T4g\Utility\SessionUtility;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use Konekt\PdfInvoice\InvoicePrinter;
require_once "init.php";
define("INVOICE_AMOUNT_FIELD_LABEL", "Amount");
define("INVOICE_AMOUNT_FIELD_NAME", "invoiceAmount");
define("INVOICE_COMMENT_FIELD_LABEL", "Comment");
define("INVOICE_COMMENT_FIELD_NAME", "invoiceComment");
define("INVOICE_DATE_FIELD_LABEL", "Date");
define("INVOICE_DATE_FIELD_NAME", "invoiceDate");
define("INVOICE_DUE_DATE_FIELD_LABEL", "Due date");
define("INVOICE_DUE_DATE_FIELD_NAME", "invoiceDueDate");
define("INVOICE_ID_FIELD_LABEL", "Id");
define("INVOICE_LINE_AMOUNT_FIELD_NAME", "invoiceLineAmount");
define("INVOICE_LINE_COMMENT_FIELD_NAME", "invoiceLineComment");
define("INVOICE_LINE_EVENT_TYPE_FIELD_LABEL", "Event type");
define("INVOICE_LINE_EVENT_TYPE_FIELD_NAME", "invoiceLineEventType");
define("INVOICE_LINE_ID_FIELD_LABEL", "Line Id");
define("INVOICE_LINE_ID_FIELD_NAME", "invoiceLineId");
define("INVOICE_LINE_STUDENT_FIELD_LABEL", "Student");
define("INVOICE_LINE_STUDENT_FIELD_NAME", "invoiceLineStudent");
define("INVOICE_MEMBER_FIELD_LABEL", "Member");
define("INVOICE_MEMBER_FIELD_NAME", "member");
define("INVOICE_PAYMENT_AMOUNT_FIELD_LABEL", "Payment amount");
define("INVOICE_PAYMENT_AMOUNT_FIELD_NAME", "invoicePaymentAmount");
define("INVOICE_PAYMENT_COMMENT_FIELD_LABEL", "Payment comment");
define("INVOICE_PAYMENT_COMMENT_FIELD_NAME", "invoicePaymentComment");
define("HIDDEN_PAYMENT_AMOUNT_FIELD_NAME", "invoicePaymentAmount");
define("HIDDEN_PAYMENT_COMMENT_FIELD_NAME", "invoicePaymentComment");
define("HIDDEN_PAYMENT_DUE_DATE_FIELD_NAME", "invoicePaymentDueDate");
$smarty->assign("title", "Manage Invoice");
$smarty->assign("heading", "Manage Invoice");
$smarty->assign("style", "<link href=\"css/manageInvoice.css\" rel=\"stylesheet\">");
$errors = NULL;
$outputAdditional = "";
$now = new DateTime();
if (SessionUtility::getValue(SessionUtility::OBJECT_NAME_ADMINISTRATOR) != 0 && Constant::MODE_CREATE == $mode || Constant::MODE_MODIFY == $mode) {
    $invoices = $entityManager->getRepository(Constant::ENTITY_INVOICES)->getById(invoiceId: (int) $ids, memberId: SessionUtility::getValue(SessionUtility::OBJECT_NAME_ADMINISTRATOR) != 0 ? NULL : SessionUtility::getValue(SessionUtility::OBJECT_NAME_MEMBERID));
    $output .= " <div class=\"buttons center\">\n";
    $buttonAddRow = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_ADD_ROW, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_ADD_ROW . "_2", maxLength: NULL, name: Constant::TEXT_ADD_ROW . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_BUTTON, value: Constant::TEXT_ADD_LINE, wrap: NULL);
    $output .= $buttonAddRow->getHtml();
    $buttonRemoveRow = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_REMOVE_ROW, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_REMOVE_ROW . "_2", maxLength: NULL, name: Constant::TEXT_REMOVE_ROW . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_BUTTON, value: Constant::TEXT_REMOVE_LINE, wrap: NULL);
    $output .= $buttonRemoveRow->getHtml();
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
        foreach ($ary as $id) {
            $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\">" . INVOICE_ID_FIELD_LABEL . ": </div>";
            $output .= " <div class=\"responsive-cell responsive-cell-value\">" . ($id == "" ? "NEW" : $id) . "</div>";
            $members = $entityManager->getRepository(Constant::ENTITY_MEMBERS)->getById(memberId: NULL);
            if (NULL !== $members) {
                $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . INVOICE_MEMBER_FIELD_NAME . "_" . $id . "\">" . INVOICE_MEMBER_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>\n";
                $selectMember = new FormSelect(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_MEMBER, class: NULL, disabled: false, id: INVOICE_MEMBER_FIELD_NAME . "_" . $id, multiple: false, name: INVOICE_MEMBER_FIELD_NAME . "_" . $id, onClick: NULL, readOnly: false, size: 1, suffix: NULL, value: NULL);
                $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $selectMember->getHtml();
                $invoiceMemberIdTemp = isset($_POST[INVOICE_MEMBER_FIELD_NAME . "_" . $id]) ? $_POST[INVOICE_MEMBER_FIELD_NAME . "_" . $id] : (0 < count($invoices) ? $invoices[$ctr]->getMembers()->getMemberId() : "");
                $option = new FormOption(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: $invoiceMemberIdTemp, suffix: NULL, text: Constant::TEXT_NONE, value: DEFAULT_VALUE_BLANK);
                $output .= $option->getHtml();
                foreach ($members as $member) {
                    $option = new FormOption(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: $invoiceMemberIdTemp, suffix: NULL, text: $member->getMemberName(), value: $member->getMemberId());
                    $output .= $option->getHtml();
                }
                $output .= "     </select>\n";
                $output .= "    </div>\n";
            }
            $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . INVOICE_DATE_FIELD_NAME . "_" . $id . "\">" . INVOICE_DATE_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>";
            $textBoxDate = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_DATE, autoComplete: NULL, autoFocus: false, checked: NULL, class: array("timePicker"), cols: NULL, disabled: false, id: INVOICE_DATE_FIELD_NAME . "_" . $id, maxLength: 30, name: INVOICE_DATE_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 20, suffix: NULL, type: FormControl::TYPE_INPUT_DATE_TIME, value: ((0 < count($invoices)) ? DateTimeUtility::formatDatabaseDateTime(value: $invoices[0]->getInvoiceDate()) : DateTimeUtility::formatDatabaseDateTime(value: $now)), wrap: NULL);
            $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxDate->getHtml() . "</div>";
            $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . INVOICE_DUE_DATE_FIELD_NAME . "_" . $id . "\">" . INVOICE_DUE_DATE_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>";
            $dueDate = $now->add(new \DateInterval("P14D"));
            $textBoxDueDate = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_DUE_DATE, autoComplete: NULL, autoFocus: false, checked: NULL, class: array("timePicker"), cols: NULL, disabled: false, id: INVOICE_DUE_DATE_FIELD_NAME . "_" . $id, maxLength: 30, name: INVOICE_DUE_DATE_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 20, suffix: NULL, type: FormControl::TYPE_INPUT_DATE_TIME, value: ((0 < count($invoices)) ? DateTimeUtility::formatDatabaseDateTime(value: $invoices[0]->getInvoiceDueDate()) : DateTimeUtility::formatDatabaseDateTime(value: $dueDate)), wrap: NULL);
            $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxDueDate->getHtml() . "</div>";
            $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . INVOICE_AMOUNT_FIELD_NAME . "_" . $id . "\">" . INVOICE_AMOUNT_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>";
            $textBoxAmount = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: true, checked: NULL, class: NULL, cols: NULL, disabled: false, id: INVOICE_AMOUNT_FIELD_NAME . "_" . $id, maxLength: 4, name: INVOICE_AMOUNT_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: true, required: true, rows: NULL, size: 4, suffix: NULL, type: FormControl::TYPE_INPUT_NUMBER, value: (int) ((0 < count($invoices)) ? $invoices[0]->getInvoiceAmount() : 0), wrap: NULL);
            $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxAmount->getHtml() . "</div>";
            $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . INVOICE_COMMENT_FIELD_NAME . "_" . $id . "\">" . INVOICE_COMMENT_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>";
            $textBoxComment = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_COMMENT, autoComplete: NULL, autoFocus: true, checked: NULL, class: NULL, cols: 30, disabled: false, id: INVOICE_COMMENT_FIELD_NAME . "_" . $id, maxLength: 200, name: INVOICE_COMMENT_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: false, rows: 6, size: 100, suffix: NULL, type: FormControl::TYPE_INPUT_TEXTAREA, value: ((0 < count($invoices)) ? $invoices[0]->getInvoiceComment() : ""), wrap: NULL);
            $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxComment->getHtml() . "</div>";
            if (count($invoices) == 0) {
                $il = new InvoiceLines();
                $il->initialize();
                $collection = new ArrayCollection();
                $collection->add($il);
                $invoices[0] = new Invoices();
                $invoices[0]->setInvoiceId(0);
                $members = new Members();
                $members->setMemberId(0);
                $invoices[0]->setMembers($members);
                $invoices[0]->setInvoiceLines($collection);
            }
            $amountTotal = 0;
            $ctr2 = 1;
            $output .= " <div class=\"center xxlarge\">Lines</div>\n";
            $output .= " <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"display\" id=\"" . Constant::ID_TABLE_INPUT . "\" style=\"margin: 0;\" width=\"100%\">\n";
            $output .= "  <thead>\n";
            $output .= "   <tr>\n";
            $output .= "    <th>" . INVOICE_LINE_ID_FIELD_LABEL . "</th>\n";
            $output .= "    <th>" . INVOICE_LINE_STUDENT_FIELD_LABEL . "</th>\n";
            $output .= "    <th>" . INVOICE_LINE_EVENT_TYPE_FIELD_LABEL . "</th>\n";
            $output .= "    <th>" . INVOICE_AMOUNT_FIELD_LABEL . "</th>\n";
            $output .= "    <th>" . INVOICE_COMMENT_FIELD_LABEL . "</th>\n";
            $output .= "   </tr>\n";
            $output .= "  </thead>\n";
            $output .= "  <tbody>\n";
            foreach ($invoices[$ctr]->getInvoiceLines() as $invoiceLines) {
                // amount, student, comment, event type
                $output .= "   <tr>\n";
                $output .= "    <td>\n";
                $output .= "     <span id=\"invoice_line_id_" . $ctr2 . "\">" . explode("-", $invoiceLines->getInvoiceLineId())[1] . "</span>\n";
                $hiddenLineRow = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: INVOICE_LINE_ID_FIELD_NAME . "_" . $id . "_" . $ctr2, maxLength: NULL, name: INVOICE_LINE_ID_FIELD_NAME . "_" . $id . "_" . $ctr2, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: ((0 < count($invoices)) ? $invoiceLines->getInvoiceLineId() : ""), wrap: NULL);
                $output .= $hiddenLineRow->getHtml();
                $output .= "    </td>\n";
                $output .= "    <td>\n";
                $students = $entityManager->getRepository(Constant::ENTITY_STUDENTS)->getByMemberId(memberId: (int) $invoiceMemberIdTemp);
                if (NULL !== $students) {
                    $selectMember = new FormSelect(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_STUDENT, class: NULL, disabled: false, id: INVOICE_LINE_STUDENT_FIELD_NAME . "_" . $id . "_" . $ctr2, multiple: false, name: INVOICE_LINE_STUDENT_FIELD_NAME . "_" . $id . "_" . $ctr2, onClick: NULL, readOnly: false, size: 1, suffix: NULL, value: NULL);
                    $output .= $selectMember->getHtml();
                    if (1 < count($students)) {
                        $option = new FormOption(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: 0 < count($invoices) ? $invoiceLines->getStudents()->getStudentId() : "", suffix: NULL, text: Constant::TEXT_NONE, value: DEFAULT_VALUE_BLANK);
                        $output .= $option->getHtml();
                    }
                    foreach ($students as $student) {
                        $option = new FormOption(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: 0 < count($invoices) ? $invoiceLines->getStudents()->getStudentId() : "", suffix: NULL, text: $student->getStudentName(), value: $student->getStudentId());
                        $output .= $option->getHtml();
                    }
                    $output .= "     </select>\n";
                }
                $output .= "    </td>\n";
                $output .= "    <td>\n";
                $eventTypes = $entityManager->getRepository(Constant::ENTITY_EVENT_TYPES)->getById(eventTypeId: NULL);
                if (NULL !== $eventTypes) {
                    $selectEventType = new FormSelect(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_EVENT_TYPE, class: NULL, disabled: false, id: INVOICE_LINE_EVENT_TYPE_FIELD_NAME . "_" . $id . "_" . $ctr2, multiple: false, name: INVOICE_LINE_EVENT_TYPE_FIELD_NAME . "_" . $id . "_" . $ctr2, onClick: NULL, readOnly: false, size: 1, suffix: NULL, value: NULL);
                    $output .= $selectEventType->getHtml();
                    $option = new FormOption(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: 0 < count($invoices) ? $invoiceLines->getStudents()->getStudentId() : "", suffix: NULL, text: Constant::TEXT_NONE, value: DEFAULT_VALUE_BLANK);
                    $output .= $option->getHtml();
                    foreach ($eventTypes as $eventType) {
                        foreach ($eventType->getEventTypeCosts() as $eventTypeCost) {
                            $option = new FormOption(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: 0 < count($invoices) ? $invoiceLines->getEventTypes()->getEventTypeId() . "::" . $eventTypeCost->getEventTypeCost() : "", suffix: NULL, text: $eventType->getEventTypeName() . " (" . $eventTypeCost->getEventTypeStudentCount() . " student(s))", value: $eventType->getEventTypeId() . "::" . $eventTypeCost->getEventTypeCost());
                            $output .= $option->getHtml();
                        }
                    }
                    $output .= "     </select>\n";
                }
                $output .= "    </td>\n";
                $output .= "    <td>\n";
                $textAmount = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_AMOUNT, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: INVOICE_LINE_AMOUNT_FIELD_NAME . "_" . $id . "_" . $ctr2, maxLength: 2, name: INVOICE_LINE_AMOUNT_FIELD_NAME . "_" . $id . "_" . $ctr2, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 2, suffix: NULL, type: FormControl::TYPE_INPUT_NUMBER, value: (string) ((count($invoices) > 0) ? $invoiceLines->getInvoiceLineAmount() : ""), wrap: NULL);
                $output .= $textAmount->getHtml();
                $output .= "    </td>\n";
                $output .= "    <td>\n";
                $textBoxComment = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_COMMENT, autoComplete: NULL, autoFocus: true, checked: NULL, class: NULL, cols: 30, disabled: false, id: INVOICE_LINE_COMMENT_FIELD_NAME . "_" . $id . "_" . $ctr2, maxLength: 200, name: INVOICE_LINE_COMMENT_FIELD_NAME . "_" . $id . "_" . $ctr2, onClick: NULL, placeholder: NULL, readOnly: false, required: false, rows: 3, size: 100, suffix: NULL, type: FormControl::TYPE_INPUT_TEXTAREA, value: ((0 < count($invoices)) ? $invoiceLines->getInvoiceLineComment() : ""), wrap: NULL);
                $output .= $textBoxComment->getHtml();
                $output .= "    </td>\n";
                $output .= "   </tr>\n";
                $amountTotal += $invoiceLines->getInvoiceLineAmount();
                $ctr2++;
            }
            $output .= "   <tr id=\"rowTotal\">\n";
            $output .= "    <td></td>\n";
            $output .= "    <td>\n";
            $textDummy = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: array("hidden"), cols: NULL, disabled: false, id: "dummy_" . $id . "_total", maxLength: 2, name: "dummy_" . $id . "_total", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: 2, suffix: NULL, type: FormControl::TYPE_INPUT_TEXTBOX, value: NULL, wrap: NULL);
            $output .= $textDummy->getHtml();
            $output .= "    </td>\n";
            $output .= "    <td>Total " . INVOICE_AMOUNT_FIELD_LABEL . ($id != "" ? " " . $id : "") . ":</td>\n";
            $output .= "    <td>\n";
            $textAmount = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_AMOUNT, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: true, id: INVOICE_AMOUNT_FIELD_NAME . "Total", maxLength: 3, name: INVOICE_AMOUNT_FIELD_NAME . "Total", onClick: NULL, placeholder: NULL, readOnly: true, required: NULL, rows: NULL, size: 2, suffix: NULL, type: FormControl::TYPE_INPUT_TEXTBOX, value: (string) $amountTotal, wrap: NULL);
            $output .= $textAmount->getHtml();
            $output .= "    </td>\n";
            $output .= "    <td></td>\n";
            $output .= "   </tr>\n";
            $output .= "  </tbody>\n";
            $output .= " </table>\n";
            $hiddenRow = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: HIDDEN_ROW_FIELD_NAME . "_" . $id, maxLength: NULL, name: HIDDEN_ROW_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: ((0 < count($invoices)) ? $invoices[0]->getInvoiceId() : ""), wrap: NULL);
            $output .= $hiddenRow->getHtml();
            $ctr++;
        }
        $output .= "<div class=\"buttons center\">\n";
        $buttonAddRow = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_ADD_ROW, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_ADD_ROW, maxLength: NULL, name: Constant::TEXT_ADD_ROW, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_BUTTON, value: Constant::TEXT_ADD_LINE, wrap: NULL);
        $output .= $buttonAddRow->getHtml();
        $buttonRemoveRow = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_REMOVE_ROW, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_REMOVE_ROW, maxLength: NULL, name: Constant::TEXT_REMOVE_ROW, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_BUTTON, value: Constant::TEXT_REMOVE_LINE, wrap: NULL);
        $output .= $buttonRemoveRow->getHtml();
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
} elseif (Constant::MODE_SAVE_CREATE == $mode || Constant::MODE_SAVE_MODIFY == $mode || Constant::MODE_SAVE_PAYMENT == $mode || Constant::MODE_VIEW_PDF == $mode) {
    $ary = explode(Constant::DELIMITER_DEFAULT, $ids);
    if (SessionUtility::getValue(SessionUtility::OBJECT_NAME_ADMINISTRATOR) != 0 && Constant::MODE_SAVE_PAYMENT == $mode) {
//         print_r($_POST);
        foreach ($ary as $id) {
//             $invoiceId = (int) ((isset($_POST[HIDDEN_ROW_FIELD_NAME . "_" . $id])) ? $_POST[HIDDEN_ROW_FIELD_NAME . "_" . $id] : 0);
            $invoicePaymentAmount = (isset($_POST[HIDDEN_PAYMENT_AMOUNT_FIELD_NAME])) ? $_POST[HIDDEN_PAYMENT_AMOUNT_FIELD_NAME] : 0;
            $invoicePaymentComment = (isset($_POST[HIDDEN_PAYMENT_COMMENT_FIELD_NAME])) ? $_POST[HIDDEN_PAYMENT_COMMENT_FIELD_NAME] : NULL;
            if ("" == $invoicePaymentComment) {
                $invoicePaymentComment = NULL;
            }
            $invoiceDueDate = isset($_POST[HIDDEN_PAYMENT_DUE_DATE_FIELD_NAME]) ? $_POST[HIDDEN_PAYMENT_DUE_DATE_FIELD_NAME] : DEFAULT_VALUE_BLANK;
//             echo $id . "/" . $invoicePaymentAmount . "/" . $invoicePaymentComment . "/" . $invoiceDueDate;
//             die();
            $ip = new InvoicePayments();
            $ip->setInvoicePaymentAmount((float) $invoicePaymentAmount);
            $ip->setInvoicePaymentComment($invoicePaymentComment);
            $ip->setInvoicePaymentDate($now);
            $in = $entityManager->find(Constant::ENTITY_INVOICES, $id);
            $ip->setInvoices($in);
            $entityManager->persist($ip);
            try {
                $entityManager->flush();
            } catch (Exception $e) {
                $errors = $e->getMessage();
            }
        }
    } elseif (Constant::MODE_VIEW_PDF == $mode) {
        foreach ($ary as $id) {
            $invoices = $entityManager->getRepository(Constant::ENTITY_INVOICES)->getById(invoiceId: (int) $id, memberId: SessionUtility::getValue(SessionUtility::OBJECT_NAME_ADMINISTRATOR) != 0 ? NULL : SessionUtility::getValue(SessionUtility::OBJECT_NAME_MEMBERID));
            // size: A4/Letter/Legal, currency: any string, language: any language in inc/languages folder
            $invoicePrinter = new InvoicePrinter(size: "Letter", currency: "$", language: "en");
            // decimals_sep: any string, thousands_sep: any string, alignment: left/right, space: true/false, negativeParenthesis: true/false
            $invoicePrinter->setNumberFormat(decimals_sep: ".", thousands_sep: ",", alignment: "left", space: false, negativeParenthesis: true);
            // custom color
            $invoicePrinter->setColor(rgbcolor: "#007fff");
            // maxWidth: optional #, maxHeight; optional #)
            $invoicePrinter->setLogo(logo: "images/logo.jpg");
            // title shown in upper right corner
            $invoicePrinter->setType(title: "Invoice");
            // invoice # shown in upper right corner
            $invoicePrinter->setReference(reference: $invoices[0]->getInvoiceId());
            $invoicePrinter->setDate(date: DateTimeUtility::formatDisplayDateInvoice(value: $invoices[0]->getInvoiceDate()));
//             $invoicePrinter->setTime(time: date("h:i:s A", time()));
            $invoicePrinter->setDue(date: DateTimeUtility::formatDisplayDateInvoice(value: $invoices[0]->getInvoiceDueDate()));
            //$invoicePrinter->addCustomHeader(title: "title here", content: "content here");
            // company details (array first element will be bolded can have as many lines as needed)
            $invoicePrinter->setFrom(["Twirling for Grace", "877 S. Hacker Rd", "Brighton, MI 48114"]);
            // client details (array first element will be bolded can have as many lines as needed)
            $invoicePrinter->setTo([$invoices[0]->getMembers()->getMemberName()]);
            // switch horizontal position of company and client (default is company on left)
            //$invoicePrinter->flipflop();
            // hide issuer and client header row
            //$invoicePrinter->hideToFromHeaders();
            // item, description: use <br> or \n for line break, quantity: #, vat: string percent or actual # amount, price: #, discount: string percent or actual # amount, total: #
            // only name required pass false to disable any other options
            foreach ($invoices[0]->getInvoiceLines() as $invoiceLine) {
                $invoicePrinter->addItem(item: $invoiceLine->getStudents()->getStudentName(), description: $invoiceLine->getEventTypes()->getEventTypeName(), quantity: false, vat: false, price: false, discount: false, total: $invoiceLine->getInvoiceLineAmount());
            }
            // description font size default is 7
            //$invoicePrinter->setFontSizeProductDescription(9);
            // alignment: horizontal/vertical
            $invoicePrinter->setTotalsAlignment(alignment: "horizontal");
            // add row for calculations/totals unlimited # of rows
            // name, value, colored: true/false true set theme color as background color of row
            $invoicePrinter->addTotal(name: "Total due", value: $invoices[0]->getInvoiceAmount(), colored: true);
            // add badge below products/services (e.g. show paid)
            // badge, color: hex code
            if ($invoices[0]->getInvoiceBalance() <= 0) {
                $invoicePrinter->addBadge(badge: "Paid in Full");
            }
            // add title/paragraph at bottom such as payment details or shipping info
            $invoicePrinter->addTitle(title: "Important Notice");
            // add title/paragraph at bottom such as payment details or shipping info
            $invoicePrinter->addParagraph(paragraph: "If you pay weekly your invoice is due 14 days from Friday.");
            // override term from language file
            $invoicePrinter->changeLanguageTerm(term: "number", new: "Invoice #");
            $invoicePrinter->changeLanguageTerm(term: "date", new: "Invoice Date");
            $invoicePrinter->changeLanguageTerm(term: "due", new: "Invoice Due");
            $invoicePrinter->changeLanguageTerm(term: "from", new: "Bill From");
            $invoicePrinter->changeLanguageTerm(term: "to", new: "Bill To");
            // small text to display in bottom left corder
            $invoicePrinter->setFooternote(note: "Generated at " . DateTimeUtility::formatDisplayDateTime(value: new DateTime()));
            // name, destination: I => Display on browser, D => Force Download, F => local path save, S => return document path
            $invoicePrinter->render(name: "invoice_" . $invoices[0]->getInvoiceId() . "_" . date("mdyhis") . ".pdf", destination: "D");
        }
    } else {
        if (SessionUtility::getValue(SessionUtility::OBJECT_NAME_ADMINISTRATOR) != 0) {
            foreach ($ary as $id) {
                $invoiceId = (int) ((isset($_POST[HIDDEN_ROW_FIELD_NAME . "_" . $id])) ? $_POST[HIDDEN_ROW_FIELD_NAME . "_" . $id] : 0);
                $invoiceAmount = (isset($_POST[INVOICE_AMOUNT_FIELD_NAME . "_" . $id])) ? $_POST[INVOICE_AMOUNT_FIELD_NAME . "_" . $id] : 0;
                $invoiceMemberId = (isset($_POST[INVOICE_MEMBER_FIELD_NAME . "_" . $id])) ? $_POST[INVOICE_MEMBER_FIELD_NAME . "_" . $id] : 0;
                $invoiceComment = (isset($_POST[INVOICE_COMMENT_FIELD_NAME . "_" . $id])) ? $_POST[INVOICE_COMMENT_FIELD_NAME . "_" . $id] : NULL;
                if ("" == $invoiceComment) {
                    $invoiceComment = NULL;
                }
                $invoiceDate = isset($_POST[INVOICE_DATE_FIELD_NAME . "_" . $id]) ? $_POST[INVOICE_DATE_FIELD_NAME . "_" . $id] : DEFAULT_VALUE_BLANK;
                $invoiceDueDate = isset($_POST[INVOICE_DUE_DATE_FIELD_NAME . "_" . $id]) ? $_POST[INVOICE_DUE_DATE_FIELD_NAME . "_" . $id] : DEFAULT_VALUE_BLANK;
                if (Constant::MODE_SAVE_CREATE == $mode) {
                    $in = new Invoices();
                    $in->setInvoiceAmount((float) $invoiceAmount);
                    $in->setInvoiceComment($invoiceComment);
                    $in->setInvoiceDate(new DateTime(datetime: $invoiceDate));
                    $in->setInvoiceDueDate(new DateTime(datetime: $invoiceDueDate));
                    $invoiceMember = $entityManager->find(Constant::ENTITY_MEMBERS, $invoiceMemberId);
                    $in->setMembers($invoiceMember);
                    $entityManager->persist($in);
                    try {
                        $entityManager->flush();
                    } catch (Exception $e) {
                        $errors = $e->getMessage();
                    }
                } elseif (Constant::MODE_SAVE_MODIFY == $mode) {
                    $in = $entityManager->find(Constant::ENTITY_INVOICES, $invoiceId);
                    $in->setInvoiceAmount((float) $invoiceAmount);
                    $in->setInvoiceComment($invoiceComment);
                    $in->setInvoiceDate(new DateTime(datetime: $invoiceDate));
                    $in->setInvoiceDueDate(new DateTime(datetime: $invoiceDueDate));
                    $invoiceMember = $entityManager->find(Constant::ENTITY_MEMBERS, $invoiceMemberId);
                    $in->setMembers($invoiceMember);
                    $entityManager->persist($in);
                    try {
                        $entityManager->flush();
                    } catch (Exception $e) {
                        $errors = $e->getMessage();
                    }
                }
                $done = false;
                $ctrTemp = 1;
                while (!$done) {
                    if (isset($_POST[INVOICE_LINE_STUDENT_FIELD_NAME . "_" . $id . "_" . $ctrTemp])) {
                        $invoiceLineId = $_POST[INVOICE_LINE_ID_FIELD_NAME . "_" . $id . "_" . $ctrTemp];
                        $invoiceLineStudentId = $_POST[INVOICE_LINE_STUDENT_FIELD_NAME . "_" . $id . "_" . $ctrTemp];
                        $invoiceLineStudent = $entityManager->find(Constant::ENTITY_STUDENTS, $invoiceLineStudentId);
                        $invoiceLineEventTypeId = explode("::", $_POST[INVOICE_LINE_EVENT_TYPE_FIELD_NAME . "_" . $id . "_" . $ctrTemp])[0];
                        $invoiceLineEventType = $entityManager->find(Constant::ENTITY_EVENT_TYPES, $invoiceLineEventTypeId);
                        $invoiceLineAmount = $_POST[INVOICE_LINE_AMOUNT_FIELD_NAME . "_" . $id . "_" . $ctrTemp];
                        $invoiceLineComment = NULL;
                        if (isset($_POST[INVOICE_LINE_STUDENT_FIELD_NAME . "_" . $id . "_" . $ctrTemp])) {
                            $invoiceLineComment = $_POST[INVOICE_LINE_COMMENT_FIELD_NAME . "_" . $id . "_" . $ctrTemp];
                            if ("" == $invoiceLineComment) {
                                $invoiceLineComment = NULL;
                            }
                        }
        //                 $in = $entityManager->find(Constant::ENTITY_INVOICES, $invoiceId);
                        $il = $entityManager->find(Constant::ENTITY_INVOICE_LINES, $invoiceLineId);
                        if (!isset($il)) {
                            $il = new InvoiceLines();
                        }
                        $il->setEventTypes($invoiceLineEventType);
                        $il->setInvoiceLineAmount((int) $invoiceLineAmount);
                        $il->setInvoiceLineComment($invoiceLineComment);
                        $il->setStudents($invoiceLineStudent);
                        $il->setInvoices($in);
                        $entityManager->persist($il);
                        try {
                            $entityManager->flush();
                        } catch (Exception $e) {
                            $errors = $e->getMessage();
                        }
                        $ctrTemp++;
                    } else {
                        $done = true;
                    }
                }
                // doctrine ORM stores 1 instance of entity so need to refresh after adding lines
                $entityManager->refresh($in);
                if (isset($errors)) {
                    $output .=
                        "<script type=\"module\">\n" .
                        "  import { dataTable, display, input } from \"./scripts/import.js\";\n" .
                        "  display.showErrors({errors: [ \"" . $errors . "\" ]});\n" .
                        "</script>\n";
                }
    //             $ids = DEFAULT_VALUE_BLANK;
            }
        }
    }
    $ids = DEFAULT_VALUE_BLANK;
    $mode = Constant::MODE_VIEW;
}
if (Constant::MODE_VIEW == $mode || Constant::MODE_DELETE == $mode || Constant::MODE_CONFIRM == $mode) {
    if (SessionUtility::getValue(SessionUtility::OBJECT_NAME_ADMINISTRATOR) != 0 && Constant::MODE_CONFIRM == $mode) {
        if ($ids != DEFAULT_VALUE_BLANK) {
            $invoices = $entityManager->getRepository(Constant::ENTITY_INVOICES)->getById(invoiceId: (int) $id);
            $entityManager->remove($invoices);
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
        if (SessionUtility::getValue(SessionUtility::OBJECT_NAME_ADMINISTRATOR) != 0) {
            $buttonCreate = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CREATE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CREATE, maxLength: NULL, name: Constant::TEXT_CREATE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CREATE, wrap: NULL);
            $output .= $buttonCreate->getHtml();
            $buttonModify = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_MODIFY, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: true, id: Constant::TEXT_MODIFY, maxLength: NULL, name: Constant::TEXT_MODIFY, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_MODIFY, wrap: NULL);
            $output .= $buttonModify->getHtml();
            $buttonDelete = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_DELETE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: true, id: Constant::TEXT_DELETE, maxLength: NULL, name: Constant::TEXT_DELETE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_DELETE, wrap: NULL);
            $output .= $buttonDelete->getHtml();
            $buttonMakePayment = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_MAKE_PAYMENT, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: true, id: Constant::TEXT_MAKE_PAYMENT, maxLength: NULL, name: Constant::TEXT_MAKE_PAYMENT, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_MAKE_PAYMENT, wrap: NULL);
            $output .= $buttonMakePayment->getHtml();
        }
        $buttonCreatePdf = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_VIEW_PDF, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: true, id: Constant::TEXT_VIEW_PDF, maxLength: NULL, name: Constant::TEXT_VIEW_PDF, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_VIEW_PDF, wrap: NULL);
        $output .= $buttonCreatePdf->getHtml();
    } else if (SessionUtility::getValue(SessionUtility::OBJECT_NAME_ADMINISTRATOR) != 0 && Constant::MODE_DELETE == $mode) {
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
    $hiddenPaymentAmount = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: HIDDEN_PAYMENT_AMOUNT_FIELD_NAME, maxLength: NULL, name: HIDDEN_PAYMENT_AMOUNT_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: NULL, wrap: NULL);
    $output .= $hiddenPaymentAmount->getHtml();
    $hiddenPaymentComment = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: HIDDEN_PAYMENT_COMMENT_FIELD_NAME, maxLength: NULL, name: HIDDEN_PAYMENT_COMMENT_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: NULL, wrap: NULL);
    $output .= $hiddenPaymentComment->getHtml();
    $hiddenPaymentDueDate = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: HIDDEN_PAYMENT_DUE_DATE_FIELD_NAME, maxLength: NULL, name: HIDDEN_PAYMENT_DUE_DATE_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: NULL, wrap: NULL);
    $output .= $hiddenPaymentDueDate->getHtml();
    $id = ("" == $ids) ? NULL : (int) $ids;
    $results = $entityManager->getRepository(Constant::ENTITY_INVOICES)->getById(invoiceId: $id, memberId: SessionUtility::getValue(SessionUtility::OBJECT_NAME_ADMINISTRATOR) != 0 ? NULL : SessionUtility::getValue(SessionUtility::OBJECT_NAME_MEMBERID));
    $output .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"display\" id=\"" . Constant::ID_TABLE_DATA . "\" style=\"width: 100%;\">\n";
    $output .= " <thead>\n";
    $output .= "  <th>#</th>\n";
    $output .= "  <th>Member</th>\n";
    $output .= "  <th>Date</th>\n";
    $output .= "  <th>Due Date</th>\n";
    $output .= "  <th>Amount</th>\n";
    $output .= "  <th>Payments</th>\n";
    $output .= "  <th>Balance</th>\n";
    $output .= "  <th># of lines</th>\n";
    $output .= "  <th>Comment</th>\n";
    $output .= " </thead>\n";
    $output .= " <tbody>\n";
    $output .= "  <tr>\n";
    foreach($results as $invoice) {
        $output .= "   <td>" . HtmlUtility::buildLink(href: "#", id: "invoice_history_link_" . $invoice->getInvoiceId(), target: NULL, title: "View history", text: (string) $invoice->getInvoiceId());
        $output .= "   <td>" . $invoice->getMembers()->getMemberName() . "</td>\n";
        $output .= "   <td>" . DateTimeUtility::formatDisplayDate(value: $invoice->getInvoiceDate()) . "</td>\n";
        $output .= "   <td" . ($invoice->getInvoiceBalance() > 0 && DateTimeUtility::formatDisplayDate(value: $now) > DateTimeUtility::formatDisplayDate(value: $invoice->getInvoiceDueDate()) ? " class=\"pastDue\"" : "") . ">" . DateTimeUtility::formatDisplayDate(value: $invoice->getInvoiceDueDate()) . "</td>\n";
        $output .= "   <td class=\"negative\">$" . $invoice->getInvoiceAmount() . "</td>\n";
        $output .= "   <td>" . HtmlUtility::buildLink(href: "#", id: "invoice_payments_link_" . $invoice->getInvoiceId(), target: "_self", title: "View payment history", text: (string) "$" . $invoice->getInvoicePaymentTotal());
        $output .= "   <td class=\"negative\">$" . $invoice->getInvoiceBalance() . "</td>\n";
        $output .= "   <td>" . HtmlUtility::buildLink(href: "#", id: "invoice_lines_link_" . $invoice->getInvoiceId(), target: "_self", title: "View lines history", text: (string) count($invoice->getInvoiceLines()));
        $output .= "   <td><pre>" . $invoice->getInvoiceComment() . "</pre></td>\n";
        $output .= "  </tr>\n";
        $invoicesHistory = $entityManager->getRepository(Constant::ENTITY_INVOICES_HISTORY)->getById(invoiceId: $invoice->getInvoiceId());
        foreach($invoicesHistory as $invoiceHistory) {
            $outputAdditional .= "<script type=\"module\">\n";
            $outputAdditional .= "  document.querySelector(\"#invoice_history_link_" . $invoice->getInvoiceId() . "\").addEventListener(\"click\", (evt) => document.querySelector(\"#dialogInvoiceHistory_" . $invoice->getInvoiceId() . "\").showModal());\n";
            $outputAdditional .= "</script>\n";
            $outputAdditional .= "<dialog class=\"child dialog\" id=\"dialogInvoiceHistory_" . $invoice->getInvoiceId() . "\" style=\"left: 0; top: 0; width: unset;\">\n";
            $outputAdditional .= " <form method=\"dialog\">\n";
            $outputAdditional .= "  <header>\n";
            $outputAdditional .= "   <h2>Invoice History</h2>\n";
            $outputAdditional .= "   <button class=\"dialogButton\" id=\"dialogInvoiceHistory_" . $invoice->getInvoiceId() . "-header--cancel-btn\">X</button>\n";
            $outputAdditional .= "  </header>\n";
            $outputAdditional .= "  <main>\n";
            $outputAdditional .= "  <h2 class=\"center\">Invoice</h2>\n";
            $outputAdditional .= "   <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"display\" id=\"dataTblInvoiceHistory\" style=\"width: 100%;\">\n";
            $outputAdditional .= "    <thead>\n";
            $outputAdditional .= "     <tr>\n";
            $outputAdditional .= "      <th>#</th>\n";
            $outputAdditional .= "      <th>Date</th>\n";
            $outputAdditional .= "      <th>Due Date</th>\n";
            $outputAdditional .= "      <th>Amount</th>\n";
            $outputAdditional .= "      <th>Comment</th>\n";
            $outputAdditional .= "      <th>Action</th>\n";
            $outputAdditional .= "      <th>Revision</th>\n";
            $outputAdditional .= "      <th>Chg Date</th>\n";
            $outputAdditional .= "     </tr>\n";
            $outputAdditional .= "    </thead>\n";
            $outputAdditional .= "    <tbody>\n";
            $outputAdditional .= "     <tr>\n";
            $outputAdditional .= "      <td>" . $invoiceHistory->getInvoiceId() . "</td>\n";
            $outputAdditional .= "      <td>" . DateTimeUtility::formatDisplayDate(value: $invoiceHistory->getInvoiceDate()) . "</td>\n";
            $outputAdditional .= "      <td>" . DateTimeUtility::formatDisplayDate(value: $invoiceHistory->getInvoiceDueDate()) . "</td>\n";
            $outputAdditional .= "      <td class=\"positive\">$" . $invoiceHistory->getInvoiceAmount() . "</td>\n";
            $outputAdditional .= "      <td><pre>" . $invoiceHistory->getInvoiceComment() . "</pre></td>\n";
            $outputAdditional .= "      <td>" . $invoiceHistory->getAction() . "</td>\n";
            $outputAdditional .= "      <td>" . $invoiceHistory->getRevision() . "</td>\n";
            $outputAdditional .= "      <td>" . DateTimeUtility::formatDisplayDateTime(value: $invoiceHistory->getChangeDate()) . "</td>\n";
            $outputAdditional .= "     </tr>\n";
            $outputAdditional .= "    </tbody>\n";
            $outputAdditional .= "   </table>\n";
            $outputAdditional .= "   <br />\n";
            $outputAdditional .= "  <h2 class=\"center\">Lines</h2>\n";
            $outputAdditional .= "   <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"display\" id=\"dataTblInvoiceLineHistory\" style=\"width: 100%;\">\n";
            $outputAdditional .= "    <thead>\n";
            $outputAdditional .= "     <tr>\n";
            $outputAdditional .= "      <th>Line Id</th>\n";
            $outputAdditional .= "      <th>Student</th>\n";
            $outputAdditional .= "      <th>Amount</th>\n";
            $outputAdditional .= "      <th>Comment</th>\n";
            $outputAdditional .= "      <th>Action</th>\n";
            $outputAdditional .= "      <th>Revision</th>\n";
            $outputAdditional .= "      <th>Chg Date</th>\n";
            $outputAdditional .= "     </tr>\n";
            $outputAdditional .= "    </thead>\n";
            $outputAdditional .= "    <tbody>\n";
            foreach($invoiceHistory->getInvoiceLines() as $invoiceHistoryLine) {
                $outputAdditional .= "     <tr>\n";
                $outputAdditional .= "      <td>" . $invoiceHistoryLine->getInvoiceLineId() . "</td>\n";
                $outputAdditional .= "      <td>" . $invoiceHistoryLine->getStudents()->getStudentName() . "</td>\n";
                $outputAdditional .= "      <td class=\"positive\">$" . $invoiceHistoryLine->getInvoiceLineAmount() . "</td>\n";
                $outputAdditional .= "      <td><pre>" . $invoiceHistoryLine->getInvoiceLineComment() . "</pre></td>\n";
                $outputAdditional .= "      <td>" . $invoiceHistoryLine->getAction() . "</td>\n";
                $outputAdditional .= "      <td>" . $invoiceHistoryLine->getRevision() . "</td>\n";
                $outputAdditional .= "      <td>" . DateTimeUtility::formatDisplayDateTime(value: $invoiceHistoryLine->getChangeDate()) . "</td>\n";
                $outputAdditional .= "     </tr>\n";
            }
            $outputAdditional .= "    </tbody>\n";
            $outputAdditional .= "   </table>\n";
            $outputAdditional .= "   <br />\n";
            $outputAdditional .= "  <h2 class=\"center\">Payments</h2>\n";
            $outputAdditional .= "   <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"display\" id=\"dataTblInvoicePaymentHistory\" style=\"width: 100%;\">\n";
            $outputAdditional .= "    <thead>\n";
            $outputAdditional .= "     <tr>\n";
            $outputAdditional .= "      <th>Pmt Id</th>\n";
            $outputAdditional .= "      <th>Date</th>\n";
            $outputAdditional .= "      <th>Amount</th>\n";
            $outputAdditional .= "      <th>Comment</th>\n";
            $outputAdditional .= "      <th>Action</th>\n";
            $outputAdditional .= "      <th>Revision</th>\n";
            $outputAdditional .= "      <th>Chg Date</th>\n";
            $outputAdditional .= "     </tr>\n";
            $outputAdditional .= "    </thead>\n";
            $outputAdditional .= "    <tbody>\n";
            foreach($invoiceHistory->getInvoicePayments() as $invoiceHistoryPayment) {
                $outputAdditional .= "     <tr>\n";
                $outputAdditional .= "      <td>" . $invoiceHistoryPayment->getInvoicePaymentId() . "</td>\n";
                $outputAdditional .= "      <td>" . DateTimeUtility::formatDisplayDate(value: $invoiceHistoryPayment->getInvoicePaymentDate()) . "</td>\n";
                $outputAdditional .= "      <td class=\"positive\">$" . $invoiceHistoryPayment->getInvoicePaymentAmount() . "</td>\n";
                $outputAdditional .= "      <td><pre>" . $invoiceHistoryPayment->getInvoicePaymentComment() . "</pre></td>\n";
                $outputAdditional .= "      <td>" . $invoiceHistoryPayment->getAction() . "</td>\n";
                $outputAdditional .= "      <td>" . $invoiceHistoryPayment->getRevision() . "</td>\n";
                $outputAdditional .= "      <td>" . DateTimeUtility::formatDisplayDateTime(value: $invoiceHistoryPayment->getChangeDate()) . "</td>\n";
                $outputAdditional .= "     </tr>\n";
            }
            $outputAdditional .= "    </tbody>\n";
            $outputAdditional .= "   </table>\n";
            $outputAdditional .= "  </main>\n";
            $outputAdditional .= " </form>\n";
            $outputAdditional .= "</dialog>\n";
    }}
    $output .= " </tbody>\n";
    $output .= "</table>\n";
    foreach($results as $invoice) {
        $outputAdditional .= "<script type=\"module\">\n";
        $outputAdditional .= "  document.querySelector(\"#invoice_lines_link_" . $invoice->getInvoiceId() . "\").addEventListener(\"click\", (evt) => document.querySelector(\"#dialogInvoiceLines_" . $invoice->getInvoiceId() . "\").showModal());\n";
        $outputAdditional .= "</script>\n";
        $outputAdditional .= "<dialog class=\"child dialog\" id=\"dialogInvoiceLines_" . $invoice->getInvoiceId() . "\">\n";
        $outputAdditional .= " <form method=\"dialog\">\n";
        $outputAdditional .= "  <header>\n";
        $outputAdditional .= "   <h2>Invoice Lines</h2>\n";
        $outputAdditional .= "   <button class=\"dialogButton\" id=\"dialogInvoiceLines_" . $invoice->getInvoiceId() . "-header--cancel-btn\">X</button>\n";
        $outputAdditional .= "  </header>\n";
        $outputAdditional .= "  <main>\n";
        $outputAdditional .= "   <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"display\" id=\"dataTblInvoiceLines\" style=\"width: 100%;\">\n";
        $outputAdditional .= "    <thead>\n";
        $outputAdditional .= "     <tr>\n";
        $outputAdditional .= "      <th>#</th>\n";
        $outputAdditional .= "      <th>Student</th>\n";
        $outputAdditional .= "      <th>Amount</th>\n";
        $outputAdditional .= "      <th>Comment</th>\n";
        $outputAdditional .= "     </tr>\n";
        $outputAdditional .= "    </thead>\n";
        $outputAdditional .= "    <tbody>\n";
        foreach($invoice->getInvoiceLines() as $invoiceLine) {
            $outputAdditional .= "     <tr>\n";
            $outputAdditional .= "      <td>" . $invoiceLine->getInvoiceLineId() . "</td>\n";
            $outputAdditional .= "      <td>" . $invoiceLine->getStudents()->getStudentName() . "</td>\n";
            $outputAdditional .= "      <td class=\"positive\">$" . $invoiceLine->getInvoiceLineAmount() . "</td>\n";
            $outputAdditional .= "      <td><pre>" . $invoiceLine->getInvoiceLineComment() . "</pre></td>\n";
            $outputAdditional .= "     </tr>\n";
        }
        $outputAdditional .= "    </tbody>\n";
        $outputAdditional .= "   </table>\n";
        $outputAdditional .= "  </main>\n";
        $outputAdditional .= " </form>\n";
        $outputAdditional .= "</dialog>\n";
        $outputAdditional .= "<script type=\"module\">\n";
        $outputAdditional .= "  document.querySelector(\"#invoice_payments_link_" . $invoice->getInvoiceId() . "\").addEventListener(\"click\", (evt) => document.querySelector(\"#dialogInvoicePayments_" . $invoice->getInvoiceId() . "\").showModal());\n";
        $outputAdditional .= "</script>\n";
        $outputAdditional .= "<dialog class=\"child dialog\" id=\"dialogInvoicePayments_" . $invoice->getInvoiceId() . "\">\n";
        $outputAdditional .= " <form method=\"dialog\">\n";
        $outputAdditional .= "  <header>\n";
        $outputAdditional .= "   <h2>Invoice Payments</h2>\n";
        $outputAdditional .= "   <button class=\"dialogButton\" id=\"dialogInvoicePayments_" . $invoice->getInvoiceId() . "-header--cancel-btn\">X</button>\n";
        $outputAdditional .= "  </header>\n";
        $outputAdditional .= "  <main>\n";
        $outputAdditional .= "   <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"display\" id=\"dataTblInvoicePayments\" style=\"width: 100%;\">\n";
        $outputAdditional .= "    <thead>\n";
        $outputAdditional .= "     <tr>\n";
        $outputAdditional .= "      <th>#</th>\n";
        $outputAdditional .= "      <th>Date</th>\n";
        $outputAdditional .= "      <th>Amount</th>\n";
        $outputAdditional .= "      <th>Comment</th>\n";
        $outputAdditional .= "     </tr>\n";
        $outputAdditional .= "    </thead>\n";
        $outputAdditional .= "    <tbody>\n";
        foreach($invoice->getInvoicePayments() as $invoicePayment) {
            $outputAdditional .= "     <tr>\n";
            $outputAdditional .= "      <td>" . $invoicePayment->getInvoicePaymentId() . "</td>\n";
            $outputAdditional .= "      <td>" . DateTimeUtility::formatDisplayDate(value: $invoicePayment->getInvoicePaymentDate()) . "</td>\n";
            $outputAdditional .= "      <td class=\"positive\">$" . $invoicePayment->getInvoicePaymentAmount() . "</td>\n";
            $outputAdditional .= "      <td><pre>" . $invoicePayment->getInvoicePaymentComment() . "</pre></td>\n";
            $outputAdditional .= "     </tr>\n";
        }
        $outputAdditional .= "    </tbody>\n";
        $outputAdditional .= "   </table>\n";
        $outputAdditional .= "  </main>\n";
        $outputAdditional .= " </form>\n";
        $outputAdditional .= "</dialog>\n";

//         $outputAdditional .= "<div class=\"wrap\">\n";
        $outputAdditional .= "<dialog class=\"child dialog\" id=\"dialogInvoiceMakePayments_" . $invoice->getInvoiceId() . "\">\n";
        $outputAdditional .= " <form method=\"dialog\">\n";
        $outputAdditional .= "  <header>\n";
        $outputAdditional .= "   <h2>Make Payment</h2>\n";
        $outputAdditional .= "   <button class=\"dialogButton\" id=\"dialogInvoiceMakePayments_" . $invoice->getInvoiceId() . "-header--cancel-btn\">X</button>\n";
        $outputAdditional .= "  </header>\n";
        $outputAdditional .= "  <main>\n";
        $outputAdditional .= "   <table border=\"0\" cellpadding=\"0\" cellspacing=\"3\" class=\"display\" id=\"dataTblInvoiceMakePayments_" . $invoice->getInvoiceId() . "\" style=\"width: 100%;\">\n";
        $outputAdditional .= "    <tbody>\n";
        $outputAdditional .= "     <tr>\n";
        $outputAdditional .= "      <td class=\"label\">Invoice #</td>\n";
        $outputAdditional .= "      <td>" . $invoice->getInvoiceId() . "</td>\n";
        $outputAdditional .= "     </tr>\n";
        $outputAdditional .= "     <tr>\n";
        $outputAdditional .= "      <td class=\"label\">" . INVOICE_DUE_DATE_FIELD_LABEL . "</td>\n";
        $outputAdditional .= "      <td>" . DateTimeUtility::formatDisplayDate(value: $now) . "</td>\n";
        $outputAdditional .= "     </tr>\n";
        $outputAdditional .= "     <tr>\n";
        $outputAdditional .= "      <td class=\"label\">" . INVOICE_PAYMENT_AMOUNT_FIELD_LABEL . "</td>\n";
        $textAmount = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_AMOUNT, autoComplete: NULL, autoFocus: true, checked: NULL, class: NULL, cols: NULL, disabled: false, id: INVOICE_PAYMENT_AMOUNT_FIELD_NAME . "_" . $invoice->getInvoiceId(), maxLength: 3, name: INVOICE_PAYMENT_AMOUNT_FIELD_NAME . "_" . $invoice->getInvoiceId(), onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 2, suffix: NULL, type: FormControl::TYPE_INPUT_NUMBER, value: NULL, wrap: NULL);
        $outputAdditional .= "      <td>" . $textAmount->getHtml() . "</td>\n";
        $outputAdditional .= "     </tr>\n";
        $outputAdditional .= "     <tr>\n";
        $outputAdditional .= "      <td class=\"label\">" . INVOICE_PAYMENT_COMMENT_FIELD_LABEL . "</td>\n";
        $textComment = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_COMMENT, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: 30, disabled: false, id: INVOICE_PAYMENT_COMMENT_FIELD_NAME . "_" . $invoice->getInvoiceId(), maxLength: 200, name: INVOICE_COMMENT_FIELD_NAME . "_" . $invoice->getInvoiceId(), onClick: NULL, placeholder: NULL, readOnly: false, required: false, rows: 6, size: 100, suffix: NULL, type: FormControl::TYPE_INPUT_TEXTAREA, value: NULL, wrap: NULL);
        $outputAdditional .= "      <td>" . $textComment->getHtml() . "</td>\n";
        $outputAdditional .= "     </tr>\n";
        $outputAdditional .= "     <tr>\n";
        $outputAdditional .= "      <td colspan=\"2\">\n";
        $buttonSave = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_SAVE, autoComplete: NULL, autoFocus: false, checked: NULL, class: array("button-icon button-icon-separator icon-border-caret-right"), cols: NULL, disabled: false, id: Constant::TEXT_SAVE . "Payment" . "_" . $invoice->getInvoiceId(), maxLength: NULL, name: Constant::TEXT_SAVE . "Payment" . "_" . $invoice->getInvoiceId(), onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_SAVE . " Payment", wrap: NULL);
        $outputAdditional .= $buttonSave->getHtml();
        $outputAdditional .= "      </td>\n";
        $outputAdditional .= "     </tr>\n";
        $outputAdditional .= "    </tbody>\n";
        $outputAdditional .= "   </table>\n";
        $outputAdditional .= "  </main>\n";
        $outputAdditional .= " </form>\n";
        $outputAdditional .= "</dialog>\n";
//         $outputAdditional .= "</div>\n";
    }
    if (Constant::MODE_VIEW == $mode) {
        $output .= "<div class=\"buttons center\">\n";
        if (SessionUtility::getValue(SessionUtility::OBJECT_NAME_ADMINISTRATOR) != 0) {
            $buttonCreate = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CREATE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CREATE . "_2", maxLength: NULL, name: Constant::TEXT_CREATE . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CREATE, wrap: NULL);
            $output .= $buttonCreate->getHtml();
            $buttonModify = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_MODIFY, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: true, id: Constant::TEXT_MODIFY . "_2", maxLength: NULL, name: Constant::TEXT_MODIFY . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_MODIFY, wrap: NULL);
            $output .= $buttonModify->getHtml();
            $buttonDelete = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_DELETE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: true, id: Constant::TEXT_DELETE . "_2", maxLength: NULL, name: Constant::TEXT_DELETE . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_DELETE, wrap: NULL);
            $output .= $buttonDelete->getHtml();
            $buttonMakePayment = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_MAKE_PAYMENT, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: true, id: Constant::TEXT_MAKE_PAYMENT . "_2", maxLength: NULL, name: Constant::TEXT_MAKE_PAYMENT . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_MAKE_PAYMENT, wrap: NULL);
            $output .= $buttonMakePayment->getHtml();
        }
        $buttonCreatePdf = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_VIEW_PDF, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: true, id: Constant::TEXT_VIEW_PDF . "_2", maxLength: NULL, name: Constant::TEXT_VIEW_PDF . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_VIEW_PDF, wrap: NULL);
        $output .= $buttonCreatePdf->getHtml();
        $output .= "</div>\n";
    }
}
$smarty->assign("content", $output);
$smarty->assign("contentAdditional", $outputAdditional);
$smarty->display("manage.tpl");