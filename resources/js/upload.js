const categoryObject = [
    {
        "item": "Select a category",
        "subitems": [
        ]
    },
    {
        "item": "Anime",
        "subitems": {
            "1":"Anime - Standalone Anime Movie",
            "2":"Anime - OVA",
            "3":"Anime - ONA",
            "4":"Anime - Anime Series",
            "5":"Anime - Anime Short",
            "6":"Anime - AMV"
        }
    },
    {
        "item": "Movies",
        "subitems": {
            "7":"Movies - Standalone Movie",
            "8":"Movies - Movie Series",
            "9":"Movies - Short Film",
            "10":"Movies - Teaser/Trailer",
            "11":"Movies - Documentary"
        }
    },
    {
        "item": "TV",
        "subitems": {
            "12":"TV - Show",
            "13":"TV - Episode",
            "14":"TV - Documentary Series",
            "15":"TV - Commercial Break"
        }
    },
    {
        "item": "Games",
        "subitems": {
            "16":"Games - Videogame",
            "17":"Games - Visual Novel",
            "18":"Games - Point & Click",
            "19":"Games - Flash & Web",
            "20":"Games - ROM"
        }
    },
    {
        "item": "Software",
        "subitems": {
            "21":"Software - Creative",
            "22":"Software - Office",
            "23":"Software - Utility",
            "24":"Software - Communications",
            "25":"Software - OS"
        }
    },
    {
        "item": "NSFW",
        "subitems": {
            "26":"Anime - Live Action",
            "27":"Anime - Animated",
            "28":"Anime - Pictures"
        }
    },
    {
        "item": "Music",
        "subitems": {
            "29":"Music - Discography",
            "30":"Music - Album",
            "31":"Music - Single",
            "32":"Music - Live",
            "33":"Music - EP",
            "34":"Music - Music Video"
        }
    }
];

document.addEventListener("DOMContentLoaded", function () {

    // Replace text field value on file upload
    const fileText = document.querySelector("#torrent_file");
    fileText.addEventListener('change', getFileData);

    // Get uploaded file name and write it to the value attribute
    function getFileData() {
        const files = this.files;
        const child = document.querySelector(".upload-field");

        for (let i = 0; i < files.length; i++) {
            child.setAttribute('value', files[i].name);
        }
    }

    // Define the variables for the category dropdowns
    const catSel = document.getElementById("category");
    const subCatSel = document.getElementById("subcategory");

    // Change subcategory dropdown depending on selected category 
    // and populate value fields
    for (let x in categoryObject) {
        catSel.options[catSel.options.length] = new Option(categoryObject[x].item, x);
    }

    catSel.onchange = function () {
        subCatSel.length = 1;

        const temp = Object.values(categoryObject[this.value].subitems);
        const subCatVals = Object.keys(categoryObject[this.value].subitems);

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

    // Replace upload submit button on click with loading animation
    const uploadForm = document.querySelector(".upload-form");
    const submitBtn = document.querySelector(".end-btn");
    const loadingIcon = document.querySelector(".dot-container");

    uploadForm.addEventListener("submit", function () {
        submitBtn.style.display = 'none';
        loadingIcon.style.display = 'flex';
    });

    // Live convert description textarea content to Markdown and send it to preview window
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

});