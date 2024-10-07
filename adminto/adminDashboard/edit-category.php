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
       <di class="col-md-12">
        <?php
           if(isset($_GET['id'])){
              $id = $_GET['id'];
              $category = getById("categories",$id);

              if(mysqli_num_rows($category)> 0){
                $data = mysqli_fetch_array($category);
         
        ?>
        <div class="card">
            <div class="card-header">
                <h4>Edit Category</h4>
                <a href="category.php" class="btn btn-primary float-end">Back</a>
            </div>
            <div class="card-body">
                <form action="code.php" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" name="category_id" value="<?= $data['id'] ?>" >
                <label for="">Name</label>
                <input value="<?= $data['name'] ?>" placeholder="Enter Name of Category" type="text" name="name" class="form-control">
                    </div>
                    <div class="col-md-12">
                <label for="">Description</label>
                <textarea  rows="3" type="text" name="description" class="form-control"><?= $data['description'] ?></textarea>
                    </div>
                    <div class="col-md-12">
                <label for="">Upload Image</label>
                <input type="file" name="image" class="form-control" >
                <label for="">Current Image:        </label>
                <input type="hidden" name="old_image" value="<?= $data['image'] ?>" >
                <label for="mb-0">Current Image</label>
                <img src="../../uploads/images/category/<?= $data['image'] ?>" height="100px" width="100px" alt="">
                    </div>
                    <div class="col-md-12 py-4">
                        <button class="btn btn-primary" name="Update_category-btn">Update</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
        <?php 
              }
              else{
                echo "Categroy Not Found";
              }
        }
         else {
            echo'Id missing from the url';
         }
        ?>
       </div>
    </div>
</div>
    <?php include('includes/footer.php'); ?>