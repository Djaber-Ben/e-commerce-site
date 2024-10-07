
document.getElementById('city-select').addEventListener('change', function () {
    var selectedOption = this.options[this.selectedIndex];
    var shippingPrice = selectedOption.getAttribute('data-price'); // Get the shipping price
    var cartTotal = parseFloat(document.getElementById('cart-total').textContent.replace('$', '')); // Get the cart total
    var finalTotalElement = document.getElementById('final-total');

    // If a city is selected, update the shipping price and calculate the total
    if (shippingPrice) {
        shippingPrice = parseFloat(shippingPrice).toFixed(2);
        document.getElementById('shipping-price').textContent = '$' + shippingPrice;

        // Calculate the final total (cart total + shipping price)
        var finalTotal = cartTotal + parseFloat(shippingPrice);
        finalTotalElement.textContent = '$' + finalTotal.toFixed(2);
    } else {
        // If no city is selected, reset the shipping price and total
        document.getElementById('shipping-price').textContent = '$0.00'; // Set default shipping price
        finalTotalElement.textContent = '$' + cartTotal.toFixed(2); // Reset final total
    }
});


    document.getElementById('checkout-btn').addEventListener('click', function (e) {
        var city = document.getElementById('city-select').value;
        var postalCode = document.getElementById('postal-code').value;
        var errorMessage = document.getElementById('error-message');
    
        // Clear any existing error message
        errorMessage.innerHTML = '';
    
        // Check if city is selected and postal code is filled
        if (!city || !postalCode) {
            e.preventDefault(); // Prevent proceeding to checkout
    
            // Display the appropriate error message
            if (!city && !postalCode) {
                errorMessage.innerHTML = 'Please select a city and fill in the postal code.';
            } else if (!city) {
                errorMessage.innerHTML = 'Please select a city.';
            } else {
                errorMessage.innerHTML = 'Please fill in the postal code.';
            }
        }
    });
    
