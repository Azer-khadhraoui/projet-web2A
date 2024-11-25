function validatefroms() {
    // Récupérer les champs du formulaire
    const titre = document.getElementById('nom_prod').value.trim();
    const description = document.getElementById('description').value.trim();
    const prix = document.getElementById('prix').value;
    const qte = document.getElementById('qte').value.trim();
    const  url_img= document.getElementById('url_img').value.trim();
    const cat = document.getElementById('cat').value.trim();

// Validation
let test = true;

// Validation du titre (doit contenir uniquement des lettres et espaces)
let expr = /^[A-Za-z\s]+$/;
if (!expr.test(titre) || titre === "") {
    alert("Le titre n'est pas valide. Il doit contenir uniquement des lettres et des espaces !! .");
    test = false;
}

// Validation de la description (doit être remplie)
else if (description === "" ) {
    alert("La description ne peut pas être vide !! .");
    test = false;
}

// Validation de la catégorie (doit être remplie)
else if (cat === "" || NaN(cat) ) {
    alert("La catégorie ne peut pas etre validée !! .");
    test = false;
}

// Validation de l'image (doit être sélectionnée)
else if (url_img === "") {
    alert("Vous devez sélectionner une image !! .");
    test = false;
}

// Validation du prix 
else if (prix === "" || NaN(prix)) {
    alert("le prix n'est pas validée !! .");
    test = false;
}

// Validation du quantitée 
else if (qte === "" || NaN(qte)) {
    alert("Vous devez sélectionner une image.");
    test = false;
}

// Retourne false si une validation échoue pour empêcher la soumission du formulaire
return test;
    }



    function validate_cat(){
        const cat = document.getElementById('nom_categorie').value.trim();

        if (!expr.test(cat) || cat === "") {
            alert("La categorie n'est pas valide. elle doit contenir uniquement des lettres et des espaces !! .");
            test = false;
        }
        return test;
    }