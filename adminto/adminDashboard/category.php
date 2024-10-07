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
                <h4>Categories</h4>
            </div>
            <div class="card-body" id="category_table" >
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
                        $category = getAll("categories");

                        if(mysqli_num_rows($category) > 0) {

                            foreach($category as $item) 
                            {
                                ?>

                           <tr>
                            <td><?= $item['id'] ?></td>
                            <td><?= $item['name'] ?></td>
                            <td>
                                <img src="../../uploads/images/category/<?= $item['image'] ?>" width="100px" height="100" alt="">
                            </td>
                            <td>
                                <a href="edit-category.php?id=<?= $item['id']; ?>" class="btn btn-primary">Edit</a>
                               
                            </td>
                            <td>
                                
                                <button class="btn btn-danger delete_category_btn" type="button" value="<?= $item['id']; ?>">Delete</button>
                            </td>
                           </tr>
                                
                        <?php

                        }
                    }
                        else {

                            echo "No records Found";

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