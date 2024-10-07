<?php 

include(__DIR__ . "/../dbcon.php"); 
function getAll($table){

    global $con;
    $query = "SELECT * FROM $table";
    return $query_run = mysqli_query($con, $query);

}


function getPopularProductsByCategory($table) {
    global $con;
    $query = "SELECT p.*, c.name AS category_name 
              FROM $table p 
              LEFT JOIN categories c ON p.category_id = c.id 
              WHERE p.popular = 1";  // Filter only popular products
    return mysqli_query($con, $query);
}



function getById($table, $id){

    global $con;
    $query = "SELECT * FROM $table WHERE id='$id'";
    return $query_run = mysqli_query($con, $query);

}

function redirect($url, $message) {
    $_SESSION['message'] = $message; // Store the message in the session
    header('Location: ' . $url); // Redirect to the specified URL
     // Terminate the script to prevent further execution
}


function getpbyc($table) {
    global $con;
    $query = "SELECT p.*, c.name AS category_name 
              FROM $table p 
              LEFT JOIN categories c ON p.category_id = c.id";
    return mysqli_query($con, $query);
}

function getProductsByCategory($category_id) {
    global $con;
    $query = "SELECT p.*, c.name AS category_name 
              FROM products p 
              LEFT JOIN categories c ON p.category_id = c.id 
              WHERE p.category_id = " . intval($category_id); // Secure by using intval() to prevent SQL injection
    
    return mysqli_query($con, $query);
}


function getLast9ProductsByCategory($table) {
    global $con;
    $query = "SELECT p.*, c.name AS category_name 
              FROM $table p 
              LEFT JOIN categories c ON p.category_id = c.id 
              ORDER BY p.created_at DESC 
              LIMIT 9"; 
    return mysqli_query($con, $query);
}


function getNameActive($table, $name){
    global $con;
    $query = "SELECT * FROM $table WHERE id='$name' LIMIT 1";
    return mysqli_query($con, $query);
}


function getAllProducts() {
    global $con;
    $query = "SELECT p.*, c.name AS category_name 
              FROM products p 
              LEFT JOIN categories c ON p.category_id = c.id";
    return mysqli_query($con, $query);
}


function getProductById($id) {
    global $con;
    $query = "SELECT p.*, c.name AS category_name 
              FROM products p 
              LEFT JOIN categories c ON p.category_id = c.id
              WHERE p.id = '$id'";
    return mysqli_query($con, $query);
}

?>