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
    
});
