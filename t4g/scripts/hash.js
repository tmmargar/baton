"use script";
import { dataTable, display, input } from "./import.js";
document.addEventListener("click", (event) => {
  if (event.target && event.target.id == "generate") {
    document.querySelector("#mode").value = event.target.innerText.trim().toLowerCase();
  }
});
document.addEventListener("input", (event) => {
  const value = document.querySelector("#value");
  username.setCustomValidity(username.validity.valueMissing ? "You must enter a value" : "");
});