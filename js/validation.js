// Validation of the question
function validateQuestion() {
    const questionInput = document.querySelector("textarea[name='question_text']");
    if (!questionInput) {
        alert("Erreur : le champ question est introuvable !");
        return false;
    }

    const questionText = questionInput.value.trim();

    if (questionText === "") {
        alert("La question ne peut pas être vide !");
        return false;
    } else if (questionText.length < 5) {
        alert("La question doit contenir au moins 5 caractères !");
        return false;
    }
    return true;
}

// Validation of the response
function validateResponse(form) {
    const responseInput = form.querySelector("textarea[name='response_text']");
    if (!responseInput) {
        alert("Erreur : le champ réponse est introuvable !");
        return false;
    }

    const responseText = responseInput.value.trim();

    if (responseText === "") {
        alert("La réponse ne peut pas être vide !");
        return false;
    }
    if (responseText.length < 2) {
        alert("La réponse doit contenir au moins 2 caractères !");
        return false;
    }
    return true;
}

// Validation of the suggestion (Not used in the current code but included for completeness)
function validateSuggestion() {
    const suggestionInput = document.querySelector("textarea[name='suggestion_text']");
    if (!suggestionInput) {
        alert("Erreur : le champ suggestion est introuvable !");
        return false;
    }

    const suggestionText = suggestionInput.value.trim();

    if (suggestionText === "") {
        alert("La suggestion ne peut pas être vide !");
        return false;
    }
    if (suggestionText.length < 3) {
        alert("La suggestion doit contenir au moins 3 caractères !");
        return false;
    }
    return true;
}
