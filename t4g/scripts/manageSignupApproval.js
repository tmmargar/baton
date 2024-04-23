"use strict";
import { dataTable, display, input } from "./import.js";
export const inputLocal = {
  enableSave : function() {
    document.querySelectorAll("[id^='save']")?.forEach(obj => {
      obj.disabled = document.querySelectorAll("[id^='approveMember_']:checked").length == 0 && document.querySelectorAll("[id^='rejectMember_']:checked").length == 0;
    });
  },
  initializeTable : function() {
    dataTable.initialize({tableId: "dataTblSignupApproval", aryColumns: [ { "type" : "name" }, null, null, null, { "type" : "name" }, { "sortable": false }, { "sortable": false } ], aryOrder: [], aryRowGroup: false, autoWidth: false, paging: false, scrollCollapse: true, scrollResize: true, scrollY: "400px", searching: false });
  }
};
let documentReadyCallback = () => {
  inputLocal.initializeTable();
  inputLocal.enableSave();
  input.countUpdate({prefix: "approveMember"});
  input.countUpdate({prefix: "rejectMember"});
};
if (document.readyState === "complete" || (document.readyState !== "loading" && !document.documentElement.doScroll)) {
  documentReadyCallback();
} else {
  document.addEventListener("DOMContentLoaded", documentReadyCallback);
}
document.addEventListener("click", (event) => {
  if (event.target && event.target.id.includes("approveMemberCheckAll")) {
    input.toggleCheckboxes({id: "approveMember", idAll: "approveMember"});
    if (event.target.checked) {
      input.checkboxToggle({inputId: "rejectMember", checked: false});
    }
    input.countUpdate({prefix: "approveMember"});
    input.countUpdate({prefix: "rejectMember"});
    inputLocal.enableSave();
    event.stopImmediatePropagation();
  } else if (event.target && event.target.id.includes("rejectMemberCheckAll")) {
    input.toggleCheckboxes({id: "rejectMember", idAll: "rejectMember"});
    if (event.target.checked) {
      input.checkboxToggle({inputId: "approveMember", checked: false});
    }
    input.countUpdate({prefix: "approveMember"});
    input.countUpdate({prefix: "rejectMember"});
    inputLocal.enableSave();
    event.stopImmediatePropagation();
  } else if (event.target && event.target.id.includes("approveMember")) {
    if (event.target.checked) {
      const values = event.target.id.split("_");
      input.checkboxToggle({inputId: "rejectMember_" + values[1], checked: false});
      input.countUpdate({prefix: "rejectMember"});
    }
    input.toggleCheckAll({id: "approveMember", idAll: "approveMember"});
    input.toggleCheckAll({id: "rejectMember", idAll: "rejectMember"});
    inputLocal.enableSave();
    input.countUpdate({prefix: "approveMember"});
  } else if (event.target && event.target.id.includes("rejectMember")) {
    if (event.target.checked) {
      const values = event.target.id.split("_");
      input.checkboxToggle({inputId: "approveMember_" + values[1], checked: false});
      input.countUpdate({prefix: "approveMember"});
    }
    input.toggleCheckAll({id: "approveMember", idAll: "approveMember"});
    input.toggleCheckAll({id: "rejectMember", idAll: "rejectMember"});
    inputLocal.enableSave();
    input.countUpdate({prefix: "rejectMember"});
  }
});
