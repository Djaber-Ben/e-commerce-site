<?php
session_start();

// Check if cart session exists, if not, create one
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Retrieve product details from the form
$product_id = $_POST['product_id'];
$product_name = $_POST['product_name'];
$product_price = $_POST['product_price'];
$product_image = $_POST['product_image'];
$quantity = $_POST['quantity'];  // User-selected quantity

// Prepare the product data to add to the cart
$product = [
    'id' => $product_id,
    'name' => $product_name,
    'price' => $product_price,
    'image' => $product_image,
    'quantity' => $quantity  // Store the user-selected quantity
];

// Check if product is already in the cart (by ID)
$found = false;
foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $product_id) {
        // If product is already in the cart, update the quantity
        $item['quantity'] += $quantity;
        $found = true;
        break;
    }
}

// If the product is not found in the cart, add it
if (!$found) {
    $_SESSION['cart'][] = $product;
}

// Redirect to the cart page
header('Location: cart.php');
exit();
?>
