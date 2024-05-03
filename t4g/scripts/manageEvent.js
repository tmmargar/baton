"use strict";
import { dataTable, display, input } from "./import.js";
export const inputLocal = {
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
    dataTable.initialize({tableId: "dataTbl", aryColumns: [{ "width": "9%" }, { "width": "13%" }, { "render" : function (data, type, row, meta) { return display.formatActive({value: data, meta: meta, tableId: "dataTbl"}); }, "width": "13%" }, { "width": "13%" }, { "width": "13%" }, { "width": "13%" }, { "width": "13%" }, { "width": "13%" }], aryOrder: [[2, "desc"], [3, "desc"], [4, "asc"]], aryRowGroup: false, autoWidth: false, paging: false, scrollCollapse: true, scrollResize: true, scrollY: "400px", searching: true });
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
    if (document.querySelector("[id^='eventStartDate_']")) {
      if (document.querySelector("[id^='eventEndDate_']:valid")) {
        document.querySelector("[id^='eventStartDate_']").max = input.formatDate({value: document.querySelector("[id^='eventEndDate_']").value, field: "minutes", operation: "-", amount: 1});
      } else {
        document.querySelector("[id^='eventStartDate_']").removeAttribute("max");
      }
    }
    if (document.querySelector("[id^='eventEndDate_']")) {
      if (document.querySelector("[id^='eventStartDate_']:valid")) {
        document.querySelector("[id^='eventEndDate_']").min = input.formatDate({value: document.querySelector("[id^='eventStartDate_']").value, field: "minutes", operation: "+", amount: 1});
      } else {
        document.querySelector("[id^='eventEndDate_']").removeAttribute("min");
      }
    }
    if (document.querySelector("[id^='eventRepeatsEndDate_']")) {
      if (document.querySelector("[id^='eventStartDate_']:valid")) {
        document.querySelector("[id^='eventRepeatsEndDate_']").min = input.formatDate({value: document.querySelector("[id^='eventStartDate_']").value, field: "minutes", operation: "+", amount: 1});
      } else {
        document.querySelector("[id^='eventRepeatsEndDate_']").removeAttribute("min");
      }
    }
  },
  validate : function() {
    const eventType = document.querySelectorAll("[id^='eventType_']");
    const startDate = document.querySelectorAll("[id^='eventStartDate_']");
    const endDate = document.querySelectorAll("[id^='eventEndDate_']");
    const name = document.querySelectorAll("[id^='eventName_']");
    const description = document.querySelectorAll("[id^='eventDescription_']");
    const location = document.querySelectorAll("[id^='eventLocation_']");
    const locationUrl = document.querySelectorAll("[id^='eventLocationUrl_']");
    const url = document.querySelectorAll("[id^='eventUrl_']");
    const repeatsEndDate = document.querySelectorAll("[id^='eventRepeatsEndDate_']");
    if (eventType.length > 0) {
      eventType[0].setCustomValidity(eventType[0].value == "" ? "You must select a event type" : "");
      startDate[0].setCustomValidity(startDate[0].validity.valueMissing ? "You must enter a start date" : startDate[0].validity.rangeUnderflow || startDate[0].validity.rangeOverflow ? "You must enter a start date >= " + startDate[0].min + " and <= " + startDate[0].max: "");
      endDate[0].setCustomValidity(endDate[0].validity.valueMissing ? "You must enter a valid end date" : endDate[0].validity.rangeUnderflow || endDate[0].validity.rangeOverflow ? "You must enter an end date >= " + endDate[0].min + " and <= " + endDate[0].max : "");
      name[0].setCustomValidity(name[0].validity.valueMissing ? "You must enter a valid name" : "");
      description[0].setCustomValidity(description[0].validity.valueMissing ? "You must enter a valid description" : "");
      location[0].setCustomValidity(location[0].validity.valueMissing ? "You must enter a valid location" : "");
      if (locationUrl[0].value.length > 0 && !input.isUrlValid(locationUrl[0].value)) {
          locationUrl[0].setCustomValidity("You must enter a valid url");
      } else {
          locationUrl[0].setCustomValidity("");
      }
      if (url[0].value.length > 0 && !input.isUrlValid(url[0].value)) {
          url[0].setCustomValidity("You must enter a valid url");
      } else {
          url[0].setCustomValidity("");
      }
      if (repeatsEndDate.length > 0 && !repeatsEndDate.disabled) {
        repeatsEndDate[0].setCustomValidity(repeatsEndDate[0].validity.valueMissing ? "You must enter a valid end date" : repeatsEndDate[0].validity.rangeUnderflow || repeatsEndDate[0].validity.rangeOverflow ? "You must enter an end date >= " + repeatsEndDate[0].min : "");
      }
    }
  }
};
let documentReadyCallback = () => {
  inputLocal.initializeDataTable();
  inputLocal.setMinMax();
  inputLocal.validate();
  input.storePreviousValue({selectors: ["[id^='eventType_']", "[id^='eventStartDate_']", "[id^='eventEndDate_']", "[id^='eventName_']", "[id^='eventDescription_']", "[id^='eventLocation_']", "[id^='eventUrl_']"]});
};
if (document.readyState === "complete" || (document.readyState !== "loading" && !document.documentElement.doScroll)) {
  documentReadyCallback();
} else {
  document.addEventListener("DOMContentLoaded", documentReadyCallback);
}
document.querySelectorAll("#dataTbl tbody tr")?.forEach(row => row.addEventListener("click", (event) => {
    if (event.target.localName != "a") {
        const selected = row.classList.contains("selected") || row.classList.contains("dtrg-group") || row.classList.contains("inactive");
        document.querySelectorAll("[id^='modify']")?.forEach(btn => { btn.disabled = selected; });
        document.querySelectorAll("[id^='delete']")?.forEach(btn => { btn.disabled = selected; });
        // if 1 row is already selected or clicking on group row or clicking on row in past or clicking on link
        if (selected || document.querySelectorAll("#dataTbl tbody tr.selected").length == 1 || event.target.localName == "a") {
            row.classList.remove("selected");
        } else {
            row.classList.add("selected");
        }
    }
}));
document.addEventListener("change", (event) => {
  if (event.target && event.target.id.includes("eventRepeats")) {
    document.querySelectorAll("[id^='eventFrequency']")?.forEach(input => {
      const idValues = input.id.split("_");
      input.disabled = document.querySelector("[id^='eventRepeats_" + idValues[1] + "']").value == "repeats" ? false : true;
    });
    document.querySelectorAll("[id^='eventRepeatsEndDate']")?.forEach(input => {
      const idValues = input.id.split("_");
      input.disabled = document.querySelector("[id^='eventRepeats_" + idValues[1] + "']").value == "repeats" ? false : true;
    });
  }
});
document.addEventListener("click", (event) => {
  inputLocal.validate();
  if (event.target && event.target.id.includes("reset")) {
    input.restorePreviousValue({selectors: ["[id^='eventType_']", "[id^='eventStartDate_']", "[id^='eventEndDate_']", "[id^='eventName_']", "[id^='eventDescription_']", "[id^='eventLocation_']", "[id^='eventUrl_']"]});
  } else if (event.target && (event.target.id.includes("modify") || event.target.id.includes("delete"))) {
    inputLocal.setIds();
  }
});
document.addEventListener("input", (event) => {
  inputLocal.validate();
  inputLocal.setMinMax();
});