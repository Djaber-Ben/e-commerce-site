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
                <h4>Inbox</h4>
            </div>
            <div class="card-body" id="message_table">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>first_name</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>View Message</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $inbox = getAll("inbox");

                        if(mysqli_num_rows($inbox) > 0) {

                            foreach($inbox as $item) 
                            {
                                ?>

                           <tr>
                            <td><?= $item['id'] ?></td>
                            <td><?= $item['first_name'] ?></td>
                            <td><?= $item['email'] ?></td>
                            <td>0<?= $item['phone_number'] ?></td>
                             <td>
                                <a href="viewMessage.php?id=<?= $item['id']; ?>" class="btn btn-primary">View Message</a>
                               </td>
                               <td>
                                
                               <button class="btn btn-danger delete_message_btn" type="button" value="<?= $item['id']; ?>">Delete</button>

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