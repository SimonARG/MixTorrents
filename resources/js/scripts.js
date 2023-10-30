function buttonWarn(form, btn) {
    form.addEventListener("submit", function (event) {
        event.preventDefault();

        btn.style.backgroundColor = "var(--btn-red)"
        btn.innerHTML = "YOU SURE?";

        form.addEventListener("submit", function () {
            form.submit();
        });
    });
    return;
}

function toggleDropdown(drop, btn, el) {
    btn.addEventListener('click', function () {
        drop.classList.toggle("drop-show");
        btn.classList.toggle("user-btn--active");
    });
    
    window.addEventListener('click', function (event) {
        if (!event.target.closest(el)) {
            if (drop.classList.contains('drop-show')) {
                drop.classList.remove('drop-show');
                btn.classList.remove('user-btn--active');
            }
        }
    });
    return;
}

document.addEventListener("DOMContentLoaded", function () {

    // Toggle the user menu dropdown and highlight the button
    const userBtn = document.querySelector(".user-btn");
    const userDrop = document.querySelector(".user-dropdown");
    const target = document.querySelector(".user-drop");

    toggleDropdown(userDrop, userBtn, target);

    // Toggle the vertical user menu dropdown and highlight the button
    const userBtnV = document.querySelector(".user-btn--v");
    const userDropV = document.querySelector(".user-dropdown--v");
    const targetV = document.querySelector(".user-drop--v");

    toggleDropdown(userDropV, userBtnV, targetV);

    // On page load, show and hide flash message
    const holder = document.querySelector(".flash-holder");

    if (holder) {
        holder.classList.add("flash-show");

        setTimeout(function () { document.querySelector(".flash-holder").classList.remove("flash-show") }, 3000);
    }

    // Replace text field value on file upload
    if (document.querySelector("#torrent_file")) {
        const elem = document.querySelector("#torrent_file");
        elem.addEventListener('change', getFileData);
    }

    if (document.querySelector("#avatar")) {
        const elem = document.querySelector("#avatar");
        elem.addEventListener('change', getFileData);
    }

    // Get uploaded file name and write it to the value attribute
    function getFileData() {
        const files = this.files;
        const child = document.querySelector(".upload-field");

        for (let i = 0; i < files.length; i++) {
            child.setAttribute('value', files[i].name);
        }
    }

    // Define categories and subcategories for upload category dropdown
    const categoryObject = [
        {
            item: "Select a category",
            subitems: [
            ]
        },
        {
            item: "Anime",
            subitems: {
                1:"Anime - Standalone Anime Movie",
                2:"Anime - OVA",
                3:"Anime - ONA",
                4:"Anime - Anime Series",
                5:"Anime - Anime Short",
                6:"Anime - AMV"
            }
        },
        {
            item: "Movies",
            subitems: {
                7:"Movies - Standalone Movie",
                8:"Movies - Movie Series",
                9:"Movies - Short Film",
                10:"Movies - Teaser/Trailer",
                11:"Movies - Documentary"
            }
        },
        {
            item: "TV",
            subitems: {
                12:"TV - Show",
                13:"TV - Episode",
                14:"TV - Documentary Series",
                15:"TV - Commercial Break"
            }
        },
        {
            item: "Games",
            subitems: {
                16:"Games - Videogame",
                17:"Games - Visual Novel",
                18:"Games - Point & Click",
                18:"Games - Flash & Web",
                20:"Games - ROM"
            }
        },
        {
            item: "Software",
            subitems: {
                21:"Software - Creative",
                22:"Software - Office",
                23:"Software - Utility",
                24:"Software - Communications",
                25:"Software - OS"
            }
        },
        {
            item: "NSFW",
            subitems: {
                26:"Anime - Live Action",
                27:"Anime - Animated",
                28:"Anime - Pictures"
            }
        },
        {
            item: "Music",
            subitems: {
                29:"Music - Discography",
                30:"Music - Album",
                31:"Music - Single",
                32:"Music - Live",
                33:"Music - EP",
                34:"Music - Music Video"
            }
        }
    ];

    // Define the variables for the category dropdowns
    const catSel = document.getElementById("category");
    const subCatSel = document.getElementById("subcategory");

    // Change subcategory dropdown depending on selected category 
    // and populate value fields
    if (catSel) {
        for (let x in categoryObject) {
            catSel.options[catSel.options.length] = new Option(categoryObject[x].item, x);
        }

        catSel.onchange = function () {
            subCatSel.length = 1;

            const temp = Object.values(categoryObject[this.value].subitems);
            const subCatVals = Object.keys(categoryObject[this.value].subitems);
            console.log(subCatVals);

            for (let y of temp) {
                subCatSel.options[subCatSel.options.length] = new Option(y);
            }

            const options = subCatSel.children;

            for (let i = 0; i < options.length; i++) {
                if (!options[i].hasAttribute("value")) {
                    options[i].setAttribute('value', subCatVals[i - 1]);
                }
            }
        }
    }

    // Replace upload submit button on click with loading animation
    const uploadForm = document.querySelector(".upload-form");
    const submitBtn = document.querySelector(".end-btn");
    const loadingIcon = document.querySelector(".dot-container");

    if (uploadForm) {
        uploadForm.addEventListener("submit", function () {
            submitBtn.style.display = 'none';
            loadingIcon.style.display = 'flex';
        });
    }

    // Show warning on click of account delete button
    if (document.querySelector("#acc-del")) {
        const accDeleteForm = document.querySelector("#acc-del");
        const accDelBtn = document.querySelector(".del-btn");

        buttonWarn(accDeleteForm, accDelBtn);
    }

    // Show warning on click of upload delete button
    if (document.querySelector("#up-del")) {
        const upDeleteForm = document.querySelector("#up-del");
        const upDelBtn = document.querySelector(".del-btn");

        buttonWarn(upDeleteForm, upDelBtn);
    }

    // Show warning on click of each comment delete button
    if (document.querySelectorAll(".comment-controls")) {
        const comments = document.querySelectorAll(".comment-controls");

        comments.forEach(comment => {
            const deleteForm = comment.querySelector("#comment-del");
            const delBtn = comment.querySelector(".com-del-btn");

            buttonWarn(deleteForm, delBtn);
        });
    }

    // Custom search select dropdowns
    let x, i, j, l, ll, selElmnt, a, b, c, span;

    // Look for any elements with the class "custom-select":
    x = document.getElementsByClassName("custom-select");

    l = x.length;

    for (i = 0; i < l; i++) {
        selElmnt = x[i].getElementsByTagName("select")[0];
        ll = selElmnt.length;
        // For each element, create a new DIV that will act as the selected item:
        a = document.createElement("DIV");
        span = document.createElement("span");
        a.setAttribute("class", "select-selected");
        span.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
        x[i].appendChild(a).appendChild(span);
        // For each element, create a new DIV that will contain the option list:
        b = document.createElement("DIV");
        b.setAttribute("class", "select-items select-hide");

        // For each option in the original select element,create a new DIV that will act as an option item:
        for (j = 1; j < ll; j++) {
            c = document.createElement("DIV");
            span = document.createElement("span");
            span.innerHTML = selElmnt.options[j].innerHTML;

            // When an item is clicked, update the original select box, and the selected item:
            span.addEventListener("click", function (e) {
                let y, i, k, s, h, sl, yl;
                s = this.parentNode.parentNode.parentNode.getElementsByTagName("select")[0];
                sl = s.length;
                h = this.parentNode.parentNode.previousSibling;
                for (i = 0; i < sl; i++) {
                    if (s.options[i].innerHTML == this.innerHTML) {
                        let span2 = document.createElement("span");
                        s.selectedIndex = i;
                        span2.innerHTML = this.innerHTML;
                        span2.setAttribute('class', 'select-box')
                        h.removeChild(h.firstChild);
                        h.appendChild(span2);
                        y = this.parentNode.parentNode.getElementsByClassName("same-as-selected");
                        yl = y.length;
                        for (k = 0; k < yl; k++) {
                            y[k].removeAttribute('class');
                        }
                        this.setAttribute("class", "same-as-selected");
                        span2.innerHTML = span2.innerHTML.replace(/⮞/g, '');
                        span2.innerHTML = span2.innerHTML.replace(/&nbsp;/g, '');
                        break;
                    }
                }
                h.click();
            });

            b.appendChild(c).appendChild(span);
        }

        x[i].appendChild(b);

        // When the select box is clicked, close any other select boxes, and open/close the current select box:
        a.addEventListener("click", function (e) {
            e.stopPropagation();
            closeAllSelect(this);
            this.nextSibling.classList.toggle("select-hide");
            this.classList.toggle("select-arrow-active");
        });
    }

    // Close all select boxes in the document, except the current select box:
    function closeAllSelect(elmnt) {
        let x, y, i, xl, yl, arrNo = [];

        x = document.getElementsByClassName("select-items");
        y = document.getElementsByClassName("select-selected");
        xl = x.length;
        yl = y.length;

        for (i = 0; i < yl; i++) {
            if (elmnt == y[i]) {
                arrNo.push(i)
            } else {
                y[i].classList.remove("select-arrow-active");
            }
        }

        for (i = 0; i < xl; i++) {
            if (arrNo.indexOf(i)) {
                x[i].classList.add("select-hide");
            }
        }
    }

    // Get hamburger button and topnav
    const ham = document.querySelector(".hamburger");
    const topnav = document.getElementById("topnav");

    // Toggle topnav responsive style on hamburger click
    ham.addEventListener("click", function() {
        topnav.classList.toggle("responsive");
    });

    // If the user clicks anywhere outside the select box, then close all select boxes:
    document.addEventListener("click", closeAllSelect);

    if (document.querySelector(".folder-title")) {

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

    }

        // Live convert description textarea content to Markdown and send it to preview window
        if (document.querySelector(".description-tab")) {
            const inputTab = document.querySelector(".input-tab");
            const descriptionTab = document.querySelector(".description-tab");
            const target = document.getElementById('description-preview');
            const inputDiv = document.getElementById('description').parentElement;
            const dislayDiv = target.parentElement;
            const converter = new showdown.Converter();
    
            converter.setFlavor('github');
    
            inputTab.addEventListener('click', function () {
                if (inputTab.classList.contains("tab-inactive")) {
                    inputTab.classList.remove("tab-inactive");
                    inputTab.classList.add("tab-active");
                    descriptionTab.classList.remove("tab-active");
                    descriptionTab.classList.add("tab-inactive");
    
                    dislayDiv.classList.remove("active");
                    inputDiv.classList.add("active");
                }
            });
    
            descriptionTab.addEventListener('click', function () {
                if (descriptionTab.classList.contains("tab-inactive")) {
                    inputTab.classList.remove("tab-active");
                    inputTab.classList.add("tab-inactive");
                    descriptionTab.classList.remove("tab-inactive");
                    descriptionTab.classList.add("tab-active");
    
                    dislayDiv.classList.add("active");
                    inputDiv.classList.remove("active");
                }
    
                const text = document.getElementById('description').value;
                const html = converter.makeHtml(text);
    
                target.innerHTML = html;
            });
        }

});