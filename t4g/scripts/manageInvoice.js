"use strict";
import { dataTable, display, input } from "./import.js";
export const inputLocal = {
  //addRow : function({objId} = {}) {
  addRow : function(objId) {
    let newId;
    // clone last row and adjust index by 1
    const rowLast = document.querySelector("#rowTotal").previousElementSibling;
    const newRow = rowLast.cloneNode(true);
    newRow.id = "";
    const aryInputs = ["input", "textarea", "select", "button", "span"];
    aryInputs.forEach(input => {
      newRow.querySelectorAll(input).forEach(obj => {
        //obj.disabled = (obj.id.indexOf("place_") == -1) ? false : true;
        const idVal = obj.id.split("_");
        newId = parseInt(idVal[2]) + 1;
        obj.id = idVal[0] + "_" + idVal[1] + "_" + newId;
        if (obj.localName == "span") {
            obj.innerHTML = parseInt(obj.innerHTML) + 1;
        } else {
            const nameVal = obj.name.split("_");
            obj.name = nameVal[0] + "_" + nameVal[1] + "_" + (parseInt(nameVal[2]) + 1);
            //obj.checked = false;
            if (obj.type != "textarea") {
                if (obj.id.indexOf("invoiceLineAmount_") == -1) {
                    obj.value = parseInt(obj.value) + 1;
                } else {
                    obj.value = 0;
                }
            }
            obj.dataset.previousValueValidation = obj.value;
            if (obj.type == "select-one") {
                obj.selectedIndex = 0;
            }
        }
      });
    });
    const rowEnd = document.querySelector("#rowTotal");
    rowEnd.parentNode.insertBefore(newRow, rowEnd);
    inputLocal.enableButtons(objId);
  },
  calculateTotal : function(objId) {
    let total = 0;
    document.querySelectorAll("[id^='invoiceLineAmount_']").forEach(obj => {
      if (obj.value == "") {
        obj.value = 0;
      }
      total += parseInt(obj.value);
    });
    document.querySelector("#invoiceAmountTotal").value = total;
    document.querySelectorAll("[id^='invoiceAmount_']").forEach(obj => {
        obj.value = total;
    });
    document.querySelectorAll("[id^='invoicePaymentAmount_']").forEach(obj => {
        obj.value = total;
    });
    if (undefined != objId) {
      document.querySelector("#" + objId).dataset.previousValueValidation = document.querySelector("#" + objId).value;
    }
  },
  enableButtons : function(objId) {
    document.querySelectorAll("[id^='removeRow']").forEach(obj => {
        obj.disabled = document.querySelectorAll("#" + objId + " tbody tr").length == 2 ? true : false;
    });
  },
  eventTypeChange : function(objId) {
      const values = objId.split("_");
      const obj = document.querySelector("#invoiceLineEventType_" + values[1] + "_" + values[2]);
      document.querySelector("#invoiceLineAmount_" + values[1] + "_" + values[2]).value = obj.value.split("::")[1];
      inputLocal.calculateTotal("invoiceLineAmount_" + values[1] + "_" + values[2]);
  },
  initializeDataTable : function() {
      //, { "searchable": false, "visible": false }
    dataTable.initialize({tableId: "dataTbl", aryColumns: [{ "width": "5%" }, { "width": "20%" }, { "width": "10%" }, { "width": "10%" }, { "width": "10%" }, { "width": "10%" }, { "width": "10%" }, { "width": "10%" }, { "width": "15%" }], aryOrder: [[0, "asc"]], aryRowGroup: false, autoWidth: false, paging: false, scrollCollapse: true, scrollResize: true, scrollY: "400px", searching: true });
  },
  removeRow : function(objId) {
    document.querySelector("#" + objId + " tr:nth-last-child(2)").remove();
    inputLocal.calculateTotal();
    inputLocal.enableButtons(objId);
  },
  setId : function({selectedRow} = {}) {
    return selectedRow.children[0].innerHTML;
  },
  setIds : function() {
    const selectedRows = dataTable.getSelectedRows({jQueryTable: $("#dataTbl").dataTable()});
    let ids = "";
    for (let selectedRow of selectedRows) {
      ids += inputLocal.setId({selectedRow: selectedRow}) + ", ";
    }
    ids = ids.substring(0, ids.length - 2);
    document.querySelector("#ids").value = ids;
  },
  setMinMax : function() {
    if (document.querySelector("[id^='invoiceDueDate_']")) {
      if (document.querySelector("[id^='invoiceDate_']:valid")) {
        document.querySelector("[id^='invoiceDueDate_']").min = input.formatDate({value: document.querySelector("[id^='invoiceDate_']").value, field: "minutes", operation: "+", amount: 1});
      } else {
        document.querySelector("[id^='invoiceDueDate_']").removeAttribute("min");
      }
    }
    if (document.querySelector("[id^='invoiceDate_']")) {
      if (document.querySelector("[id^='invoiceDueDate_']:valid")) {
        document.querySelector("[id^='invoiceDate_']").max = input.formatDate({value: document.querySelector("[id^='invoiceDueDate_']").value, field: "minutes", operation: "-", amount: 1});
      } else {
        document.querySelector("[id^='invoiceDate_']").removeAttribute("max");
      }
    }
    if (document.querySelector("[id^='invoiceAmount_']")) {
      document.querySelector("[id^='invoiceAmount_']").min = 1;
      document.querySelector("[id^='invoiceAmount_']").max = 9999;
    }
    const lines = document.querySelector("#inputs");
    if (lines) {
        const lineAmounts = document.querySelectorAll("[id^='invoiceLineAmount_']");
        for (let lineAmount of lineAmounts) {
            lineAmount.min = 0;
            lineAmount.max = 9999;
        }
    }
  },
  validate : function() {
    const member = document.querySelectorAll("[id^='member_']");
    const date = document.querySelectorAll("[id^='invoiceDate_']");
    const dueDate = document.querySelectorAll("[id^='invoiceDueDate_']");
    const amount = document.querySelectorAll("[id^='invoiceAmount_']");
    if (date.length > 0) {
      member[0].setCustomValidity(member[0].value == "" ? "You must select a member" : "");
      date[0].setCustomValidity(date[0].validity.valueMissing ? "You must enter a valid date" : date[0].validity.rangeOverflow ? "You must enter a date before the due date" : "");
      dueDate[0].setCustomValidity(dueDate[0].validity.valueMissing ? "You must enter a valid due date" : dueDate[0].validity.rangeUnderflow ? "You must enter a due date after the date" : "");
      amount[0].setCustomValidity(amount[0].validity.valueMissing ? "You must enter a valid amount" : "");
    }
    const lines = document.querySelector("#inputs");
    if (lines) {
        const rows = document.querySelectorAll("#inputs tbody tr");
        for (let index=0; index < rows.length - 1; index++) {
            const students = document.querySelectorAll("[id^='invoiceLineStudent_']");
            for (let student of students) {
                student.setCustomValidity(student.value == "" ? "You must select a student" : "");
            }
            const eventTypes = document.querySelectorAll("[id^='invoiceLineEventType_']");
            for (let eventType of eventTypes) {
                eventType.setCustomValidity(eventType.value == "" ? "You must select a event type" : "");
            }
            const amounts = document.querySelectorAll("[id^='invoiceLineAmount_']");
            for (let amount of amounts) {
                amount.setCustomValidity(amount.validity.valueMissing ? "You must enter a valid amount" : "");
            }
        }
    }
  }
};
let documentReadyCallback = () => {
  inputLocal.initializeDataTable();
  inputLocal.setMinMax();
  inputLocal.validate();
  inputLocal.enableButtons("inputs");
  input.storePreviousValue({selectors: ["[id^='member_']", "[id^='invoiceDate_']", "[id^='invoiceDueDate_']", "[id^='invoiceAmount_']", "[id^='invoiceComment_']"]});
};
if (document.readyState === "complete" || (document.readyState !== "loading" && !document.documentElement.doScroll)) {
  documentReadyCallback();
} else {
  document.addEventListener("DOMContentLoaded", documentReadyCallback);
}
document.querySelectorAll("#dataTbl tbody tr")?.forEach(row => row.addEventListener("click", (event) => {
    if (event.target.localName != "a") {
        const selected = row.classList.contains("selected");
        document.querySelectorAll("[id^='modify']")?.forEach(btn => { btn.disabled = selected; });
        //document.querySelectorAll("[id^='delete']")?.forEach(btn => { btn.disabled = selected; });
        document.querySelectorAll("[id^='makePayment']")?.forEach(btn => { btn.disabled = selected; });
        document.querySelectorAll("[id^='createPDF']")?.forEach(btn => { btn.disabled = selected; });
        // if 1 row is already selected or clicking on link
        if (selected || document.querySelectorAll("#dataTbl tbody tr.selected").length == 1 || event.target.localName == "a") {
            row.classList.remove("selected");
        } else {
            row.classList.add("selected");
        }
    }
}));
document.addEventListener("change", (event) => {
    if (event.target && event.target.id.includes("member")) {
        document.querySelector("#frmManage").submit();
    } else if (event.target && event.target.id.includes("invoiceLineEventType")) {
        inputLocal.eventTypeChange(event.target.id);
    }
});
document.addEventListener("click", (event) => {
  inputLocal.validate();
  if (event.target && event.target.id.includes("addRow")) {
    inputLocal.addRow("inputs");
    inputLocal.validate();
  } else if (event.target && event.target.id.includes("removeRow")) {
    inputLocal.removeRow("inputs");
    inputLocal.validate();
  } else if (event.target && event.target.id.includes("makePayment")) {
      event.preventDefault();
      event.stopPropagation();
      const id = document.querySelector("table tbody tr.selected td:nth-child(1)").innerHTML;
      document.querySelector("#invoicePaymentAmount_" + id).value = document.querySelector("table tbody tr.selected td:nth-child(7)").innerHTML.replace("$", "");
      document.querySelector("#dialogInvoiceMakePayments_" + id).showModal();
  } else if (event.target && event.target.id.includes("savePayment")) {
      const id = event.target.id.split("_");
      document.querySelector("#ids").value = id[1];
      document.querySelector("#invoicePaymentAmount").value = document.querySelector("#invoicePaymentAmount_" + id[1]).value;
      document.querySelector("#invoicePaymentComment").value = document.querySelector("#invoicePaymentComment_" + id[1]).value;
      document.querySelector("#invoicePaymentDueDate").value = event.target.parentElement.parentElement.parentElement.children[1].children[1].innerHTML;
      document.querySelector("#mode").value = event.target.value.replace(" ", "").toLowerCase();
      document.querySelector("#frmManage").submit();
  } else if (event.target && event.target.id.includes("createPDF")) {
      document.querySelectorAll("#dataTbl tbody tr.selected")?.forEach(row => {
        const id = row.children[0].children[0].id.split("_");
        document.querySelector("#ids").value = id[3];
      });
      document.querySelector("#mode").value = event.target.value.replace(" ", "").toLowerCase();
  } else if (event.target && event.target.id.includes("reset")) {
    input.restorePreviousValue({selectors: ["[id^='invoiceDate_']", "[id^='invoiceDueDate_']", "[id^='invoiceAmount_']", "[id^='invoiceComment_']", "[id^='member_']"]});
  } else if (event.target && (event.target.id.includes("modify") || event.target.id.includes("delete"))) {
    inputLocal.setIds();
  }
});
document.addEventListener("input", (event) => {
  inputLocal.validate();
  inputLocal.setMinMax();
  if (event.target && event.target.id.includes("invoiceLineAmount")) {
      inputLocal.calculateTotal(event.target.id);
  }
});