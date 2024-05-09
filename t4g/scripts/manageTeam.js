"use strict";
import { dataTable, display, input } from "./import.js";
export const inputLocal = {
  initializeDataTable : function() {
    dataTable.initialize({tableId: "dataTbl", aryColumns: [{ "width": "10%" }, { "width": "35%" }, { "width": "45%" }, { "width": "10%" }], aryOrder: [[1, "asc"]], aryRowGroup: false, autoWidth: false, paging: false, scrollCollapse: true, scrollResize: true, scrollY: "400px", searching: true });
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
  sort : function(targetId) {
      const options = document.querySelectorAll("#" + targetId + " option");
      Array.from(options).sort((a, b) => {
        const aValue = a.text.split(" ");
        const bValue = b.text.split(" ");
        return aValue[1].localeCompare(bValue[1]);
      }).forEach((el) => {
        document.querySelector("#" + targetId).appendChild(el);
      });
  },
  validate : function() {
    const name = document.querySelectorAll("[id^='teamName_']");
    const description = document.querySelectorAll("[id^='teamDescription_']");
    if (name.length > 0) {
      name[0].setCustomValidity(name[0].validity.valueMissing ? "You must enter a valid name" : "");
      description[0].setCustomValidity(description[0].validity.valueMissing ? "You must enter a valid description" : "");
      const student = document.querySelectorAll("[id^='teamStudentSelected_']");
      student[0].setCustomValidity(student[0].length == 0 ? "You must select at least 1 student" : "");
    }
  }
};
let documentReadyCallback = () => {
  inputLocal.initializeDataTable();
  inputLocal.validate();
  inputLocal.sort("teamStudentSelected_" + document.querySelector("#ids").value);
  input.storePreviousValue({selectors: ["[id^='eventName_']", "[id^='eventDescription_']"]});
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
        document.querySelectorAll("[id^='delete']")?.forEach(btn => { btn.disabled = selected; });
        // if 1 row is already selected or clicking on group row or clicking on row in past or clicking on link
        if (selected || document.querySelectorAll("#dataTbl tbody tr.selected").length == 1 || event.target.localName == "a") {
            row.classList.remove("selected");
        } else {
            row.classList.add("selected");
        }
    }
}));
document.addEventListener("click", (event) => {
  inputLocal.validate();
  if (event.target && event.target.id.includes("add")) {
    document.querySelectorAll("[id^='teamStudent_']")?.forEach(select => {
      const idValues = select.id.split("_");
      document.querySelectorAll("#" + select.id + " option")?.forEach(option => {
        if (option.selected) {
          input.addOption(option.text, option.value, "teamStudentSelected_" + idValues[1]);
          input.removeOption(option.value, select.id);
          inputLocal.sort("teamStudentSelected_" + idValues[1]);
        }
      });
    });
    inputLocal.validate();
    event.preventDefault();
    return false;
  } else if (event.target && event.target.id.includes("remove")) {
    document.querySelectorAll("[id^='teamStudentSelected_']")?.forEach(select => {
      const idValues = select.id.split("_");
      document.querySelectorAll("#" + select.id + " option")?.forEach(option => {
        if (option.selected) {
          input.addOption(option.text, option.value, "teamStudent_" + idValues[1]);
          input.removeOption(option.value, select.id);
          inputLocal.sort("teamStudent_" + idValues[1]);
        }
      });
    });
    inputLocal.validate();
    event.preventDefault();
    return false;
  } else if (event.target && event.target.id.includes("save")) {
    document.querySelectorAll("[id^='teamStudentSelected_']")?.forEach(select => {
      document.querySelectorAll("#" + select.id + " option")?.forEach(option => {
        option.selected = true;
      });
    });
  } else if (event.target && event.target.id.includes("reset")) {
    input.restorePreviousValue({selectors: ["[id^='eventName_']", "[id^='eventDescription_']"]});
  } else if (event.target && (event.target.id.includes("modify") || event.target.id.includes("delete"))) {
    inputLocal.setIds();
  }
});
document.addEventListener("input", (event) => {
  inputLocal.validate();
});