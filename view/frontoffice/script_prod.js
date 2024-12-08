// Fonction pour afficher/masquer les produits par catégorie
function showCategory(categoryName) {
    // Cache toutes les sections des produits
    const allProductSections = document.querySelectorAll('.product-category');
    allProductSections.forEach(function(section) {
        section.style.display = 'none'; // Masque toutes les catégories
    });

    // Affiche la section correspondant à la catégorie
    const selectedCategorySection = document.getElementById(categoryName);
    if (selectedCategorySection) {
        selectedCategorySection.style.display = 'block'; // Affiche la catégorie sélectionnée
    }
}
