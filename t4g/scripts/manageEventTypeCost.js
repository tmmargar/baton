"use strict";
import { dataTable, display, input } from "./import.js";
export const inputLocal = {
  enableButtons : function(objId) {
    document.querySelectorAll("[id^='removeRow']").forEach(obj => {
        obj.disabled = document.querySelectorAll("#" + objId + " tbody tr").length == 2 ? true : false;
    });
  },
  initializeDataTable : function() {
      const aryRowGroup = {
            startRender: null,
            endRender: function ( rows, group ) {
              const objRow = document.createElement("tr");
              const objColumn2 = document.createElement("td");
              objColumn2.classList.add("bold");
              objColumn2.innerHTML = rows.data().pluck(1)[0];
              objRow.appendChild(objColumn2);
              const objColumn3 = document.createElement("td");
              objColumn3.classList.add("bold");
              objColumn3.innerHTML = rows.data().pluck(2)[0];
              objRow.appendChild(objColumn3);
              const objColumn4 = document.createElement("td");
              objRow.appendChild(objColumn4);
              const objColumn5 = document.createElement("td");
              objRow.appendChild(objColumn5);
              return objRow;
            },
            dataSrc: 5
          };
    dataTable.initialize({tableId: "dataTbl", aryColumns: [{ "searchable": false, "visible": false }, { "width": "25%" }, { "width": "25%" }, { "width": "25%" }, { "width": "25%" }, { "searchable": false, "visible": false }], aryOrder: [[1, "asc"], [2, "desc"], [3, "asc"]], aryRowGroup: aryRowGroup, autoWidth: false, paging: false, scrollCollapse: true, scrollResize: true, scrollY: "400px", searching: true });
  },
  setId : function({selectedRow} = {}) {
    return selectedRow[5] + "::" + selectedRow[3];
  },
  setIds : function() {
    let ids = "";
    const selectedRows = dataTable.getSelectedRowsData({jQueryTableApi: $("#dataTbl").DataTable()});
    for (let idx = 0; idx < selectedRows.length; idx++) {
        const selectedRow = selectedRows[idx];
        ids += inputLocal.setId({selectedRow: selectedRow}) + ", ";
    }
    document.querySelector("#ids").value = ids.substring(0, ids.length - 2);
  },
  setMinMax : function() {
    if (document.querySelector("[id^='eventTypeTimeLength_']")) {
      document.querySelector("[id^='eventTypeTimeLength_']").min = 1;
      document.querySelector("[id^='eventTypeTimeLength_']").max = 999;
    }
    if (document.querySelector("[id^='eventTypeStudentCount_']")) {
      document.querySelector("[id^='eventTypeStudentCount_']").min = 0;
      document.querySelector("[id^='eventTypeStudentCount_']").max = 99;
    }
    if (document.querySelector("[id^='eventTypeCost_']")) {
      document.querySelector("[id^='eventTypeCost_']").min = 1;
      document.querySelector("[id^='eventTypeCost_']").max = 999;
    }
  },
  validate : function() {
    const eventType = document.querySelectorAll("[id^='eventType_']");
    const timeLength = document.querySelectorAll("[id^='eventTypeTimeLength_']");
    const studentCount = document.querySelectorAll("[id^='eventTypeStudentCount_']");
    const cost = document.querySelectorAll("[id^='eventTypeCost_']");
    if (eventType.length > 0) {
      eventType[0].setCustomValidity(eventType[0].value == "" ? "You must select a event type" : "");
      timeLength[0].setCustomValidity(timeLength[0].validity.valueMissing ? "You must enter a time length" : timeLength[0].validity.rangeUnderflow ? "You must enter a time length >= " + timeLength[0].min + " and <= " + timeLength[0].max: "");
      studentCount[0].setCustomValidity(studentCount[0].validity.valueMissing ? "You must enter a valid student count" : studentCount[0].validity.rangeUnderflow ? "You must enter a student count >= " + studentCount[0].min + " and <= " + studentCount[0].max : "");
      cost[0].setCustomValidity(cost[0].validity.valueMissing ? "You must enter a valid cost" : cost[0].validity.rangeUnderflow ? "You must enter a cost >= " + cost[0].min + " and <= " + cost[0].max : "");
    }
  }
};
let documentReadyCallback = () => {
  inputLocal.initializeDataTable();
  inputLocal.setMinMax();
  inputLocal.validate();
  inputLocal.enableButtons("inputs");
  input.storePreviousValue({selectors: ["[id^='eventType_']", "[id^='eventTypeTimeLength_']", "[id^='eventTypeStudentCount_']", "[id^='eventTypeCost_']"]});
};
if (document.readyState === "complete" || (document.readyState !== "loading" && !document.documentElement.doScroll)) {
  documentReadyCallback();
} else {
  document.addEventListener("DOMContentLoaded", documentReadyCallback);
}
document.querySelectorAll("#dataTbl tbody tr")?.forEach(row => row.addEventListener("click", (event) => {
    if (event.target.localName != "a") {
        const selected = row.classList.contains("selected") || row.classList.contains("dtrg-group");
        document.querySelectorAll("[id^='modify']")?.forEach(btn => { btn.disabled = selected; });
        document.querySelectorAll("[id^='delete']")?.forEach(btn => { btn.disabled = selected; });
        // if 1 row is already selected or clicking on group row or clicking on link
        if (selected || document.querySelectorAll("#dataTbl tbody tr.selected").length == 1 || event.target.localName == "a") {
            row.classList.remove("selected");
        } else {
            row.classList.add("selected");
        }
    }
}));
document.addEventListener("click", (event) => {
  inputLocal.validate();
  if (event.target && event.target.id.includes("reset")) {
    input.restorePreviousValue({selectors: ["[id^='eventType_']", "[id^='eventTypeTimeLength_']", "[id^='eventTypeStudentCount_']", "[id^='eventTypeCost_']"]});
  } else if (event.target && (event.target.id.includes("modify") || event.target.id.includes("delete"))) {
    inputLocal.setIds();
  }
});
document.addEventListener("input", (event) => {
  inputLocal.validate();
  inputLocal.setMinMax();
});