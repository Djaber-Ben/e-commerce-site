<?php 
session_start();
include(__DIR__ . "/../main/config/dbcon.php");
include(__DIR__ . "/../main/config/functions/myfunctions.php");

if(isset($_POST['add_category-btn'])){
    $name = trim($_POST['name']);  
    $description = $_POST['description'];
    $image = $_FILES['image']['name'];  

    $path = "../../uploads/images/category";  

    // Check for file extension (optional)
    $images_ext = pathinfo($image, PATHINFO_EXTENSION);

    // Check if category name already exists (case-insensitive)
    $check_name_query = "SELECT name FROM categories WHERE LOWER(name) = LOWER('$name')";
    $check_name_query_run = mysqli_query($con, $check_name_query);

    if(mysqli_num_rows($check_name_query_run) > 0) {
        redirect("add-category.php", "Category already exists");
    } else {
        // Query to insert category with original file name
        $cat_query = "INSERT INTO categories (name, description, image) VALUES ('$name', '$description', '$image')";
        $cat_query_run = mysqli_query($con, $cat_query);

        if($cat_query_run){
            
            move_uploaded_file($_FILES['image']['tmp_name'], $path.'/'.$image);

            redirect("category.php", "Category Added Successfully");
        } else {
            redirect("add-category.php", "Something Went Wrong");
        }
    }
}

if(isset($_POST['add_shipping-btn'])){
    $price = $_POST['price'];  
    $place = $_POST['place'];

    // Check if the shipping place already exists
    $check_place_query = "SELECT place FROM shipping WHERE LOWER(place) = LOWER('$place')";
    $check_place_query_run = mysqli_query($con, $check_place_query);

    if(mysqli_num_rows($check_place_query_run) > 0) {
        // Set the session message and redirect to add-shipping.php
        $_SESSION['message'] = "Shipping already exists";
        header('Location: add-shipping.php');
        exit(); // Prevent further execution
    } else {
        // Insert the new shipping details
        $cat_query = "INSERT INTO shipping (place, price) VALUES ('$place', '$price')";
        $cat_query_run = mysqli_query($con, $cat_query);
        
        if($cat_query_run){
            // Set the success message and redirect to shipping.php
            $_SESSION['message'] = "Shipping Added Successfully";
            header('Location: shipping.php');
            exit(); // Prevent further execution
        } else {
            // Set the error message and redirect to add-shipping.php
            $_SESSION['message'] = "Something Went Wrong";
            header('Location: add-shipping.php');
            exit(); // Prevent further execution
        }
    }
   
}

if (isset($_POST['delete_shipping_btn'])) {
    $shipping_id = mysqli_real_escape_string($con, $_POST['id']); // Sanitize the input

    $delete_query = "DELETE FROM shipping WHERE id='$shipping_id'";
    $delete_query_run = mysqli_query($con, $delete_query);

    if ($delete_query_run) {
        $_SESSION['message'] = "Shipping place deleted successfully";
    } else {
        $_SESSION['message'] = "Failed to delete shipping place";
    }
    header('Location: shipping.php'); // Redirect back to the shipping page after deletion
    exit();
}

if (isset($_POST['Update_shipping-btn'])) {
    $shipping_id = mysqli_real_escape_string($con, $_POST['shippping_id']);
    $place = mysqli_real_escape_string($con, $_POST['name']);
    $price = mysqli_real_escape_string($con, $_POST['price']);

    // Check if fields are not empty
 
        // Prepare the update query
        $update_query = "UPDATE shipping SET place='$place', price='$price' WHERE id='$shipping_id'";

        // Execute the update query
        $update_query_run = mysqli_query($con, $update_query);

        if ($update_query_run) {
            $_SESSION['message'] = "Shipping details updated successfully";
            header('Location: shipping.php'); // Redirect back to the shipping page
            exit();
        } else {
            $_SESSION['message'] = "Failed to update shipping details";
            header('Location: edit-shipping.php?id=' . $shipping_id);
            exit();
        }
    }

//Update Method
else if(isset($_POST['Update_category-btn'])) {
    $category_id = $_POST['category_id'];
    $name = trim($_POST['name']);  
    $description = $_POST['description'];
    $new_image = $_FILES['image']['name'];
    $old_image = $_POST['old_image'];

    if($new_image != ""){
        $update_filename = $new_image;
    }
    else {
       $update_filename = $old_image;
    }

    $path = "../../uploads/images/category"; 

    $update_query = "UPDATE categories SET name='$name', description='$description', image='$update_filename'
    WHERE id='$category_id'";

    $update_query_run = mysqli_query($con, $update_query);

    if($update_query_run){
        if($_FILES['image']['name'] != ""){
            move_uploaded_file($_FILES["image"]["tmp_name"], $path.'/'.$new_image);
            if(file_exists($path.'/'.$old_image)){
                unlink($path.'/'.$old_image);
            }
        }
        redirect("category.php?id=$category_id", "Category Updated succesfuly");
    }
    else {
        redirect("edit-category.php?id=$category_id", "Something went wrong");
    }
}

else if(isset($_POST['delete_category_btn'])) {
    $category_id = mysqli_real_escape_string($con, $_POST['category_id']);

    $category_query = "SELECT * FROM categories WHERE id='$category_id'";
    $category_query_run =  mysqli_query($con, $category_query);
    $category_data = mysqli_fetch_array($category_query_run);
    $image = $category_data['image'];

    $delete_query = "DELETE FROM categories WHERE id='$category_id'";
    $delete_query_run = mysqli_query($con, $delete_query);

    $path = "../../uploads/images/category";  

    if($delete_query_run){
        if(file_exists($path.'/'.$image)){

            unlink($path.'/'.$image);

        }
        // redirect("category.php", "category deleted succesfuly");
        echo 200;
    }
    else {
        // redirect("category.php", "Something went wrong");
        echo 500;
    }
}

 
 else if (isset($_POST["add_product-btn"])) {
    $category_id = $_POST['category_id'];
    $name = $_POST['name'];
    $brand = $_POST['brand'];
    $qty = $_POST['qty'];
    $original_price = $_POST['original_price'];
    $selling_price = $_POST['selling_price'];
    $description = $_POST['description'];
    $width = $_POST['width'];
    $height = $_POST['height'];
    $color = $_POST['color'];
    $size = $_POST['size'];
    $status = isset($_POST['status']) ? '1' : '0';
    $popular = isset($_POST['popular']) ? '1' : '0';

    // Directory where images will be uploaded
    $path = "../../uploads/images/product";

    // Array to store image names
    $image_names = [];

    // Loop through multiple images
    foreach ($_FILES['images']['name'] as $key => $image_name) {
        $image_tmp_name = $_FILES['images']['tmp_name'][$key];

        // Move the uploaded image with its original name
        if (move_uploaded_file($image_tmp_name, $path . '/' . $image_name)) {
            $image_names[] = $image_name;  // Store the uploaded image name
        } else {
            redirect("add-product.php", "Image upload failed for one or more images.");
        }
    }

    // Convert image names array to a string (you can store them as comma-separated in the database)
    $images = implode(',', $image_names);

    // Insert product data including images into the database
    $product_query = "INSERT INTO products (category_id, name, description, images, brand, qty, original_price, selling_price,
    width, height, color, size, status, popular) VALUES ('$category_id', '$name', '$description', '$images', '$brand', '$qty',
    '$original_price', '$selling_price', '$width', '$height', '$color', '$size', '$status', '$popular')";

    $product_query_run = mysqli_query($con, $product_query);

    if ($product_query_run) {
        echo "Redirecting to products.php";  // Debugging message
        redirect("products.php", "Product Added Successfully");
    } else {
        echo "Redirecting to add-product.php";  // Debugging message
        redirect("add-product.php", "Something Went Wrong: " . mysqli_error($con));
    }
    
}


if (isset($_POST['update_product-btn'])) {
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $brand = $_POST['brand'];
    $qty = $_POST['qty'];
    $original_price = $_POST['original_price'];
    $selling_price = $_POST['selling_price'];
    $description = $_POST['description'];
    $width = $_POST['width'];
    $height = $_POST['height'];
    $color = $_POST['color'];
    $size = $_POST['size'];
    $category_id = $_POST['category_id']; // Capture the new category ID
    $status = isset($_POST['status']) ? '1' : '0';
    $popular = isset($_POST['popular']) ? '1' : '0';

    $old_images = $_POST['old_images']; // Old images from the form
    $uploaded_images = [];

    // Handle image upload logic (same as before)
    if (isset($_FILES['images']['name'][0]) && !empty($_FILES['images']['name'][0])) {
        $target_dir = "../../uploads/images/product/";
        $old_images_array = explode(',', $old_images);

        foreach ($_FILES['images']['name'] as $key => $val) {
            $image_name = $_FILES['images']['name'][$key];
            $image_tmp = $_FILES['images']['tmp_name'][$key];
            $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($image_ext, $allowed_ext)) {
                $new_image_name = time() . '-' . $image_name;
                $upload_path = $target_dir . $new_image_name;

                if (move_uploaded_file($image_tmp, $upload_path)) {
                    $uploaded_images[] = $new_image_name;
                }
            }
        }

        foreach ($old_images_array as $old_image) {
            $old_image_path = $target_dir . $old_image;
            if (file_exists($old_image_path)) {
                unlink($old_image_path);
            }
        }
    }

    $final_images = !empty($uploaded_images) ? implode(',', $uploaded_images) : $old_images;

    // Update the product data in the database, including category change
    $query = "UPDATE products SET 
              name='$name', brand='$brand', qty='$qty', original_price='$original_price',
              selling_price='$selling_price', description='$description', width='$width', 
              height='$height', color='$color', size='$size', images='$final_images', 
              category_id='$category_id', status='$status', popular='$popular' 
              WHERE id='$product_id'";

    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $_SESSION['message'] = "Product Updated Successfully";
        header("Location: products.php");
        exit(0);
    } else {
        $_SESSION['message'] = "Product Update Failed";
        header("Location: edit-product.php?id=$product_id");
        exit(0);
    }
}



else if(isset($_POST["delete_product_btn"])) {

    $product_id = mysqli_real_escape_string($con, $_POST['product_id']);

    // Fetch product data to get the images
    $product_query = "SELECT * FROM products WHERE id='$product_id'";
    $product_query_run = mysqli_query($con, $product_query);

    if($product_data = mysqli_fetch_array($product_query_run)) {
        $images = $product_data['images'];  // Get the images (comma-separated string)

        // Split the images string into an array
        $imageArray = explode(',', $images);

        // Define the image path directory
        $path = "../../uploads/images/product";

        // Loop through the image filenames and delete each one
        foreach($imageArray as $image) {
            $imagePath = $path . '/' . $image;
            if(file_exists($imagePath)) {
                unlink($imagePath);  // Delete the image
            }
        }

        // Delete the product from the database
        $delete_query = "DELETE FROM products WHERE id='$product_id'";
        $delete_query_run = mysqli_query($con, $delete_query);

        if($delete_query_run) {
            echo 200;  // Success response
        } else {
            echo 500;  // Failure response
        }
    }
}

else if (isset($_POST['delete_message_btn'])) {
    // Ensure you're passing the right id
    $message_id = mysqli_real_escape_string($con, $_POST['message_id']); 

    // Query to delete the message (update table name if necessary)
    $delete_query = "DELETE FROM inbox WHERE id='$message_id'"; // Change 'messages' to the correct table name if needed
    $delete_query_run = mysqli_query($con, $delete_query);

    if ($delete_query_run) {
        echo 200; // Successful deletion
    } else {
        echo 500; // Something went wrong
    }
}



?>
