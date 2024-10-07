<?php

include("../../middleware/adminMiddlware.php");
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id']; // Get user ID from session
    $product_id = $_POST['product_id'];
    $image = $_POST['product_image'];
    $selling_price = $_POST['selling_price'];
    $qty = $_POST['product_qty'];

    // Check if the product already exists in the wishlist
    $check_query = "SELECT * FROM wishlist WHERE user_id = ? AND product_id = ?";
    $check_stmt = $con->prepare($check_query);
    $check_stmt->bind_param("is", $user_id, $product_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Product already exists in the wishlist
        $_SESSION['error_message'] = 'This product is already in your wishlist.';
        header("Location: " . $_SERVER['HTTP_REFERER']); // Redirect back to the previous page
        exit(); // Make sure to exit after the redirect
    } else {
        // Insert into wishlist
        $query = "INSERT INTO wishlist (user_id, product_id, image, selling_price, qty) VALUES (?, ?, ?, ?, ?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param("issdi", $user_id, $product_id, $image, $selling_price, $qty);

        if ($stmt->execute()) {
            // Success message or redirect
            header("Location: wishlist.php");
            exit(); // Make sure to exit after the redirect
        } else {
            // Error handling
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $check_stmt->close();
}
$con->close();
?>
