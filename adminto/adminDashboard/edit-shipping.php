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
              $shippping = getById("shipping",$id);

              if(mysqli_num_rows($shippping)> 0){
                $data = mysqli_fetch_array($shippping);
         
        ?>
        <div class="card">
            <div class="card-header">
                <h4>Edit shippping</h4>
                <a href="shipping.php" class="btn btn-primary float-end">Back</a>
            </div>
            <div class="card-body">
                <form action="code.php" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" name="shippping_id" value="<?= $data['id'] ?>" >
                <label for="">place</label>
                <input value="<?= $data['place'] ?>" placeholder="Enter place of shippping" type="text" name="name" class="form-control">
                    </div>
                    <div class="col-md-12">
                <label for="">price</label>
                <textarea  rows="3" type="text" name="price" class="form-control"><?= $data['price'] ?></textarea>
                    </div>
                
                    <div class="col-md-12 py-4">
                        <button class="btn btn-primary" name="Update_shipping-btn">Update</button>
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