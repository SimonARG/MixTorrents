document.addEventListener("DOMContentLoaded", function () {
    
    // Show warning on click of upload delete button
    const upDeleteForm = document.querySelector("#up-del");
    const upDelBtn = document.querySelector(".del-btn");

    buttonWarn(upDeleteForm, upDelBtn);

    // Show warning on click of each comment delete button
    const comments = document.querySelectorAll(".comment-controls");

    comments.forEach(comment => {
        const deleteForm = comment.querySelector("#comment-del");
        const delBtn = comment.querySelector(".com-del-btn");

        buttonWarn(deleteForm, delBtn);
    });


    // Get file list
    const master = document.querySelector(".folder-title");
    const files = document.querySelector(".file-ul").getElementsByClassName("file");
    const folders = document.querySelector(".file-ul").getElementsByClassName("sub-folder-title");
    const margins = document.querySelector(".file-ul").getElementsByClassName("sub-folder");

    // Apply margins to file structure view
    for (let i = 0; i < folders.length; i++) {
        margins[i].style.paddingLeft = '21px';
    }
    for (let i = 0; i < files.length; i++) {
        files[i].style.paddingLeft = '25px';
    }

    // Open and close root folder
    master.addEventListener("click", function () {
        const children = this.parentElement.childNodes[3].childNodes;
        const icon = this.childNodes[1];

        for (let i = 0; i < children.length; i++) {
            children[i].classList.toggle("inactive");
        }

        if (icon.innerHTML == "🗀") {
            icon.innerHTML = "🗁";
        } else {
            icon.innerHTML = "🗀"
        }
    });

    // Open and close sub-folders
    for (let i = 0; i < folders.length; i++) {
        folders[i].addEventListener("click", function () {
            const children = this.parentElement.childNodes[1].childNodes;
            const icon = this.childNodes[0];

            for (let i = 0; i < children.length; i++) {
                children[i].classList.toggle("inactive");
            }

            if (icon.innerHTML == "🗀") {
                icon.innerHTML = "🗁";
            } else {
                icon.innerHTML = "🗀"
            }
        });
    }

    // Root folder open by default
    const masterChildren = master.parentElement.childNodes[3].childNodes;

    for (let i = 0; i < masterChildren.length; i++) {
        masterChildren[i].classList.toggle("inactive");
    }

});