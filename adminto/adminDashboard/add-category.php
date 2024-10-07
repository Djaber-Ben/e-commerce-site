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
                <h4>Add Category</h4>
            </div>
            <div class="card-body">
                <form action="code.php" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-12">
                <label for="">Name</label>
                <input placeholder="Enter Name of Category" type="text" name="name" class="form-control">
                    </div>
                    <div class="col-md-12">
                <label for="">Description</label>
                <textarea rows="3" type="text" name="description" class="form-control"> </textarea>
                    </div>
                    <div class="col-md-12">
                <label for="">Upload Image</label>
                <input placeholder="Enter Image" type="file" name="image" class="form-control">
                    </div>
                    <div class="col-md-12 py-4">
                        <button class="btn btn-primary" name="add_category-btn">save</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
       </div>
    </div>
</div>
    <?php include('includes/footer.php'); ?>