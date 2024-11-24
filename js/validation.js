// validation.js

// Validation de la question
function validateQuestion() {
    const questionInput = document.querySelector("textarea[name='question_text']");
    if (!questionInput) {
        alert("Erreur : le champ question est introuvable !");
        return false;
    }

    if (questionInput.value.trim() === "") {
        alert("La question ne peut pas être vide !");
        return false;
    }
    if (questionInput.value.trim().length < 5) {
        alert("La question doit contenir au moins 5 caractères !");
        return false;
    }
    return true;
}

// Validation de la réponse
function validateResponse(form) {
    const responseInput = form.querySelector("textarea[name='response_text']");
    if (!responseInput) {
        alert("Erreur : le champ réponse est introuvable !");
        return false;
    }

    if (responseInput.value.trim() === "") {
        alert("La réponse ne peut pas être vide !");
        return false;
    }
    if (responseInput.value.trim().length < 2) {
        alert("La réponse doit contenir au moins 2 caractères !");
        return false;
    }
    return true;
}
