document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");

    form.addEventListener("submit", function (event) {
        let isValid = true; // Start by assuming the form is valid

        // Clear previous error messages
        const errorMessages = form.querySelectorAll(".error-message");
        errorMessages.forEach(function (message) {
            message.remove();
        });

        // Validate all required fields
        const fields = form.querySelectorAll("input[required], textarea[required]");
        fields.forEach(function (field) {
            if (!field.value.trim()) { // If the field is empty
                isValid = false;

                // Create and insert error message
                const errorMessage = document.createElement("div");
                errorMessage.textContent = `${field.previousElementSibling.textContent} is required.`;
                errorMessage.style.color = "red";
                errorMessage.classList.add("error-message");
                field.parentNode.insertBefore(errorMessage, field.nextSibling);
            }
        });

        // Validate "price" field (positive number)
        const priceField = form.querySelector("input[name='prix']");
        if (priceField && (parseFloat(priceField.value) <= 0 || isNaN(priceField.value))) {
            isValid = false;
            const errorMessage = document.createElement("div");
            errorMessage.textContent = "Price must be a positive number.";
            errorMessage.style.color = "red";
            errorMessage.classList.add("error-message");
            priceField.parentNode.insertBefore(errorMessage, priceField.nextSibling);
        }

        // Validate "quantity" field (positive integer)
        const quantityField = form.querySelector("input[name='qte']");
        if (quantityField && (parseInt(quantityField.value, 10) <= 0 || isNaN(quantityField.value))) {
            isValid = false;
            const errorMessage = document.createElement("div");
            errorMessage.textContent = "Quantity must be a positive number.";
            errorMessage.style.color = "red";
            errorMessage.classList.add("error-message");
            quantityField.parentNode.insertBefore(errorMessage, quantityField.nextSibling);
        }

        // If any validation fails, prevent form submission
        if (!isValid) {
            event.preventDefault(); // Stops form from submitting
        }
    });
});
