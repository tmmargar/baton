<?php
declare(strict_types = 1);
namespace Baton\T4g;
use Baton\T4g\Entity\Teams;
use Baton\T4g\Entity\TeamStudents;
use Baton\T4g\Model\Constant;
use Baton\T4g\Model\FormControl;
use Baton\T4g\Model\FormOption;
use Baton\T4g\Model\FormSelect;
use Baton\T4g\Utility\DateTimeUtility;
use Baton\T4g\Utility\HtmlUtility;
use Baton\T4g\Utility\SessionUtility;
use DateInterval;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
require_once "init.php";
define("TEAM_ID_FIELD_LABEL", "Id");
define("TEAM_NAME_FIELD_LABEL", "Name");
define("TEAM_NAME_FIELD_NAME", "teamName");
define("TEAM_DESCRIPTION_FIELD_LABEL", "Description");
define("TEAM_DESCRIPTION_FIELD_NAME", "teamDescription");
define("TEAM_STUDENT_FIELD_LABEL", "Student");
define("TEAM_STUDENT_FIELD_NAME", "teamStudent");
define("TEAM_STUDENT_SELECTED_FIELD_NAME", "teamStudentSelected");
$smarty->assign("title", "Manage Team");
$smarty->assign("heading", "Manage Team");
$smarty->assign("style", "<link href=\"css/manageTeam.css\" rel=\"stylesheet\">");
$errors = NULL;
$outputAdditional = "";
$now = new DateTime();
if (Constant::MODE_CREATE == $mode || Constant::MODE_MODIFY == $mode) {
    $teams = $entityManager->getRepository(Constant::ENTITY_TEAMS)->getById(teamId: (int) $ids);
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
        foreach ($ary as $id) {
            $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\">" . TEAM_ID_FIELD_LABEL . ": </div>";
            $output .= " <div class=\"responsive-cell responsive-cell-value\">" . ($id == "" ? "NEW" : $id) . "</div>";
            $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . TEAM_NAME_FIELD_NAME . "_" . $id . "\">" . TEAM_NAME_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>";
            $textBoxName = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: TEAM_NAME_FIELD_NAME . "_" . $id, maxLength: 30, name: TEAM_NAME_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 30, suffix: NULL, type: FormControl::TYPE_INPUT_TEXTBOX, value: (0 < count($teams)) ? $teams[0]->getTeamName() : "", wrap: NULL);
            $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxName->getHtml() . "</div>";
            $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . TEAM_DESCRIPTION_FIELD_NAME . "_" . $id . "\">" . TEAM_DESCRIPTION_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>";
            $textBoxDescription = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: 60, disabled: false, id: TEAM_DESCRIPTION_FIELD_NAME . "_" . $id, maxLength: 400, name: TEAM_DESCRIPTION_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: 5, size: 20, suffix: NULL, type: FormControl::TYPE_INPUT_TEXTAREA, value: (0 < count($teams)) ? $teams[0]->getTeamDescription() : "", wrap: NULL);
            $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxDescription->getHtml() . "</div>";
            $students = $entityManager->getRepository(Constant::ENTITY_STUDENTS)->getById(studentId: NULL);
            if (NULL !== $students) {
                $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . TEAM_STUDENT_FIELD_NAME . "_" . $id . "\">" . TEAM_STUDENT_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>\n";
                $selectStudent = new FormSelect(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_STUDENT, class: NULL, disabled: false, id: TEAM_STUDENT_FIELD_NAME . "_" . $id, multiple: true, name: TEAM_STUDENT_FIELD_NAME . "_" . $id, onClick: NULL, readOnly: false, size: 10, suffix: NULL, value: NULL);
                $output .= " <div class=\"addRemove responsive-cell responsive-cell-value\">" . $selectStudent->getHtml();
                foreach ($students as $student) {
                    $found = false;
                    if (0 < count($teams)) {
                        foreach ($teams[0]->getTeamStudents() as $teamStudent) {
                            if ($teamStudent->getStudents()->getStudentId() == $student->getStudentId()) {
                                $found = true;
                            }
                        }
                    }
                    if (!$found) {
                        $option = new FormOption(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: NULL, suffix: NULL, text: $student->getStudentName(), value: $student->getStudentId());
                        $output .= $option->getHtml();
                    }
                }
                $output .= "     </select>\n";
                $output .= "     <div class=\"addRemoveButtons\">\n";
                $buttonAdd = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_ADD, autoComplete: NULL, autoFocus: false, checked: NULL, class: array("addRemove"), cols: NULL, disabled: false, id: Constant::TEXT_ADD, maxLength: NULL, name: Constant::TEXT_ADD, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_ADD . " ->", wrap: NULL);
                $output .= $buttonAdd->getHtml();
                $buttonRemove = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_REMOVE, autoComplete: NULL, autoFocus: false, checked: NULL, class: array("addRemove"), cols: NULL, disabled: false, id: Constant::TEXT_REMOVE, maxLength: NULL, name: Constant::TEXT_REMOVE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: "<- " . Constant::TEXT_REMOVE, wrap: NULL);
                $output .= $buttonRemove->getHtml();
                $output .= "     </div>\n";
            }
            $selectStudentSelected = new FormSelect(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_STUDENT, class: NULL, disabled: false, id: TEAM_STUDENT_SELECTED_FIELD_NAME . "_" . $id, multiple: true, name: TEAM_STUDENT_SELECTED_FIELD_NAME . "_" . $id, onClick: NULL, readOnly: false, size: 10, suffix: NULL, value: NULL);
            $output .= $selectStudentSelected->getHtml();
            if (0 < count($teams)) {
                foreach ($teams[0]->getTeamStudents() as $teamStudent) {
                    $option = new FormOption(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: NULL, suffix: NULL, text: $teamStudent->getStudents()->getStudentName(), value: $teamStudent->getStudents()->getStudentId());
                    $output .= $option->getHtml();
                }
            }
            $output .= "     </select>\n";
            $output .= "    </div>\n";
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
        $teamName = (isset($_POST[TEAM_NAME_FIELD_NAME . "_" . $id])) ? $_POST[TEAM_NAME_FIELD_NAME . "_" . $id] :  "";
        $teamDescription = (isset($_POST[TEAM_DESCRIPTION_FIELD_NAME . "_" . $id])) ? $_POST[TEAM_DESCRIPTION_FIELD_NAME . "_" . $id] :  "";
        // multiple returns array of values
        $teamStudentSelecteds = (isset($_POST[TEAM_STUDENT_SELECTED_FIELD_NAME . "_" . $id])) ? $_POST[TEAM_STUDENT_SELECTED_FIELD_NAME . "_" . $id] :  "";
//         print_r($_POST);die();
        if (Constant::MODE_SAVE_CREATE == $mode) {
            $te = new Teams();
            $te->setTeamDescription($teamDescription);
            $te->setTeamName($teamName);
            $collection = new ArrayCollection();
            foreach($teamStudentSelecteds as $teamStudentSelected) {
                $teamStudent = new TeamStudents();
                $teamStudent->setTeams($te);
                $student = $entityManager->find(Constant::ENTITY_STUDENTS, $teamStudentSelected);
                $teamStudent->setStudents($student);
                $entityManager->persist($teamStudent);
                $collection->add($teamStudent);
            }
            $te->setTeamStudents($collection);
            $entityManager->persist($te);
            try {
                $entityManager->flush();
            } catch (Exception $e) {
                $errors = $e->getMessage();
            }
        } elseif (Constant::MODE_SAVE_MODIFY == $mode) {
            $rowCount = $entityManager->getRepository(Constant::ENTITY_TEAM_STUDENTS)->deleteForTeam(teamId: (int) $id);
            $te = $entityManager->find(Constant::ENTITY_TEAMS, $ids);
            $te->setTeamDescription($teamDescription);
            $te->setTeamName($teamName);
            $collection = new ArrayCollection();
            foreach($teamStudentSelecteds as $teamStudentSelected) {
                $teamStudent = new TeamStudents();
                $teamStudent->setTeams($te);
                $student = $entityManager->find(Constant::ENTITY_STUDENTS, $teamStudentSelected);
                $teamStudent->setStudents($student);
                $entityManager->persist($teamStudent);
                $collection->add($teamStudent);
            }
            $te->setTeamStudents($collection);
            $entityManager->persist($te);
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
            $rowCount = $entityManager->getRepository(Constant::ENTITY_TEAM_STUDENTS)->deleteForTeam(teamId: (int) $ids);
            $te = $entityManager->find(Constant::ENTITY_TEAMS, $ids);
            $entityManager->remove($te);
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
    $results = $entityManager->getRepository(Constant::ENTITY_TEAMS)->getById(teamId: $id);
    $output .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"display\" id=\"" . Constant::ID_TABLE_DATA . "\" style=\"width: 100%;\">\n";
    $output .= " <thead>\n";
    $output .= "  <th>#</th>\n";
    $output .= "  <th>Name</th>\n";
    $output .= "  <th>Description</th>\n";
    $output .= "  <th>Students</th>\n";
    $output .= " </thead>\n";
    $output .= " <tbody>\n";
    foreach($results as $team) {
        $output .= "  <tr>\n";
        $output .= "   <td>" . $team->getTeamId() . "</td>\n";
        $output .= "   <td>" . $team->getTeamName() . "</td>\n";
        $output .= "   <td>" . $team->getTeamDescription() . "</td>\n";
        $output .= "   <td>" . (0 < count($team->getTeamStudents()) ? HtmlUtility::buildLink(href: "#", id: "students_link_" . $team->getTeamId(), target: "_self", title: "View students", text: (string) count($team->getTeamStudents())) : 0);
        if (0 < count($team->getTeamStudents())) {
            $outputAdditional .= "<script type=\"module\">\n";
            $outputAdditional .= "  document.querySelector(\"#students_link_" . $team->getTeamId() . "\").addEventListener(\"click\", (evt) => document.querySelector(\"#dialogStudents_" . $team->getTeamId() . "\").showModal());\n";
            $outputAdditional .= "</script>\n";
            $outputAdditional .= "<dialog class=\"child dialog\" id=\"dialogStudents_" . $team->getTeamId() . "\">\n";
            $outputAdditional .= " <form method=\"dialog\">\n";
            $outputAdditional .= "  <header>\n";
            $outputAdditional .= "   <h2>Students</h2>\n";
            $outputAdditional .= "   <button class=\"dialogButton\" id=\"dialogStudents_" . $team->getTeamId() . "-header--cancel-btn\">X</button>\n";
            $outputAdditional .= "  </header>\n";
            $outputAdditional .= "  <main>\n";
            $outputAdditional .= "   <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"display\" id=\"dataTblStudents\" style=\"width: 100%;\">\n";
            $outputAdditional .= "    <thead>\n";
            $outputAdditional .= "     <tr>\n";
            $outputAdditional .= "      <th>#</th>\n";
            $outputAdditional .= "      <th>Name</th>\n";
            $outputAdditional .= "     </tr>\n";
            $outputAdditional .= "    </thead>\n";
            $outputAdditional .= "    <tbody>\n";
            foreach($team->getTeamStudents() as $teamStudent) {
                $outputAdditional .= "     <tr>\n";
                $outputAdditional .= "      <td>" . $teamStudent->getStudents()->getStudentId() . "</td>\n";
                $outputAdditional .= "      <td>" . $teamStudent->getStudents()->getStudentName() . "</td>\n";
                $outputAdditional .= "     </tr>\n";
            }
            $outputAdditional .= "    </tbody>\n";
            $outputAdditional .= "   </table>\n";
            $outputAdditional .= "  </main>\n";
            $outputAdditional .= " </form>\n";
            $outputAdditional .= "</dialog>\n";
        }
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
$smarty->assign("contentAdditional", $outputAdditional);
$smarty->display("manage.tpl");