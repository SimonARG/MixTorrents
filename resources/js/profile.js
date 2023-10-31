document.addEventListener("DOMContentLoaded", function () {

    // Show warning on click of account delete button
    const accDeleteForm = document.querySelector("#acc-del");
    const accDelBtn = document.querySelector(".del-btn");

    buttonWarn(accDeleteForm, accDelBtn);

});