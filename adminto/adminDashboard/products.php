<?php 
include('../../middleware/adminMiddlware.php');


if(!isset($_SESSION['auth'])) {
    $_SESSION['message'] = "Login required to access the admin dashboard";
    header('Location: ../main/login.php');
    exit();
}

include('includes/header.php');

?>

<div class="container">
    <div class="row">
       <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>products</h4>
            </div>
            <div class="card-body" id="product_table" >
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Image</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $products = getAll("products");

                        if(mysqli_num_rows($products) > 0) {

                            foreach($products as $item) 
                            {
                                ?>

                           <tr>
                            <td><?= $item['id'] ?></td>
                            <td><?= $item['name'] ?></td>
                            <td>
                            <?php 
    // Split the images string into an array
                            $imagesArray = explode(',', $item['images']); 
    // Use the first image from the array
                           $firstImage = $imagesArray[0]; 
                           ?>
                            <img src="../../uploads/images/product/<?= $firstImage ?>" width="100px" height="100px" alt="Product Image">

                            </td>
                            <td>
                                <a href="edit-product.php?id=<?= $item['id']; ?>" class="btn btn-primary">Edit</a>
                               
                            </td>
                            <td>
                                
                            <button class="btn btn-danger delete_product_btn" type="button" value="<?= $item['id']; ?>">Delete</button>
                                
                            </td>
                            
                           </tr>
                                
                        <?php

                        }
                    }
                        else {

                            echo "<tr><td colspan='5'>No shipping Found</td></tr>";

                        }
                         ?>
                        
                    </tbody>
                </table>
            </div>
        </div>
       </div>
    </div>
</div>
    <?php include('includes/footer.php'); ?>