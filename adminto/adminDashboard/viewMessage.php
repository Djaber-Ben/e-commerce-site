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
                <h4>Message</h4>
                <a href="inbox.php" class="btn btn-primary float-end">Back</a>
            </div>
            <div class="card-body" >
            <?php
           if(isset($_GET['id'])){
              $id = $_GET['id'];
              $inbox = getById("inbox",$id);

              if(mysqli_num_rows($inbox)> 0){
                $data = mysqli_fetch_array($inbox);
         
        ?>
            <?= $data['message'] ?>

        <?php
              }
              else{
                echo "message Not Found";
              }
            }
         ?>
            </div>
        </div>
       </div>
    </div>
</div>
    <?php include('includes/footer.php'); ?>