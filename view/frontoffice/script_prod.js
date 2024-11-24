// Function to toggle visibility of product categories
function showCategory(category) {
    // Hide all categories
    document.querySelectorAll('.product-category').forEach(section => {
        section.style.display = 'none';
    });

    // Show the selected category
    document.getElementById(category).style.display = 'block';
}
