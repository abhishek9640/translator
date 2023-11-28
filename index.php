<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>Language Translator</title>
  <link rel="stylesheet" href="style.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
</head>

<body>
  <div class="container">
    <div class="wrapper">
      <div class="text-input">
        <textarea spellcheck="false" class="from-text" placeholder="Enter text"></textarea>
        <textarea spellcheck="false" readonly disabled class="to-text" placeholder="Translation"></textarea>
      </div>
      <ul class="controls">
        <li class="row from">
          <select></select>
        </li>
        <li class="exchange"><i class="fas fa-exchange-alt"></i></li>
        <li class="row to">
          <select></select>
        </li>
      </ul>
    </div>
    <input type="file" id="fileInput" accept=".txt, .doc, .docx, .pdf">
    <button id="translateBtn">Translate Text</button>
  </div>
  <script>
    const language = {
      "bn-IN": "Bengali",
      "en-GB": "English",
      "gu-IN": "Gujarati",
      "hi-IN": "Hindi",
      "pa-IN": "Panjabi",
    }

    document.addEventListener("DOMContentLoaded", function () {
      const fromText = document.querySelector(".from-text");
      const toText = document.querySelector(".to-text");
      const exchangeIcon = document.querySelector(".exchange");
      const selectTag = document.querySelectorAll("select");
      const translateBtn = document.getElementById("translateBtn");
      const fileInput = document.getElementById("fileInput");

      selectTag.forEach((tag, id) => {
        for (let language_code in language) {
          let selected = id == 0 ? (language_code == "en-GB" ? "selected" : "") : language_code == "hi-IN" ? "selected" : "";
          let option = `<option ${selected} value="${language_code}">${language[language_code]}</option>`;
          tag.insertAdjacentHTML("beforeend", option);
        }
      });

      exchangeIcon.addEventListener("click", () => {
        let tempText = fromText.value,
          tempLang = selectTag[0].value;
        fromText.value = toText.value;
        toText.value = tempText;
        selectTag[0].value = selectTag[1].value;
        selectTag[1].value = tempLang;
      });

      fromText.addEventListener("keyup", () => {
        if (!fromText.value) {
          toText.value = "";
        }
      });

      translateBtn.addEventListener("click", () => {
        let text = fromText.value.trim(),
          translateFrom = selectTag[0].value,
          translateTo = selectTag[1].value;
        if (!text) return;
        toText.setAttribute("placeholder", "Translating...");
        let apiUrl = `https://api.mymemory.translated.net/get?q=${text}&langpair=${translateFrom}|${translateTo}`;
        fetch(apiUrl)
          .then(res => res.json())
          .then(data => {
            toText.value = data.responseData.translatedText;
            data.matches.forEach(data => {
              if (data.id === 0) {
                toText.value = data.translation;
              }
            });
            toText.setAttribute("placeholder", "Translation");
          });
      });

      fileInput.addEventListener("change", () => {
        const file = fileInput.files[0];

        if (file) {
          const reader = new FileReader();

          reader.onload = function (e) {
            fromText.value = e.target.result;
          };

          reader.readAsText(file);
        }
      });
    });
  </script>
</body>

</html>