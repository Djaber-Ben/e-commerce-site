<?php
session_start();

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Loop through the cart and remove the selected product
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
}

// Redirect back to the cart page
header('Location: cart.php');
exit();
?>
