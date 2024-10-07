<?php

include('../../middleware/adminMiddlware.php');

if (!isset($_SESSION['auth'])) {
    $_SESSION['message'] = "Login required to access the admin dashboard";
    header('Location: ../main/login.php');
    exit();
}



// Handle delete request
if (isset($_POST['delete_shipping_btn'])) {
    $shipping_id = mysqli_real_escape_string($con, $_POST['id']); // Make sure to sanitize input

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
include('includes/header.php');

?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Shipping</h4>
                    <a href="add-shipping.php" class="btn btn-primary float-end">Add Shipping Place</a>
                </div>
                <div class="card-body" id="shipping_table">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Place</th>
                                <th>Price</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $shipping = getAll("shipping");

                            if (mysqli_num_rows($shipping) > 0) {
                                foreach ($shipping as $item) {   
                                    ?>
                                    <tr>
                                        <td><?= $item['id'] ?></td>
                                        <td><?= $item['place'] ?></td>
                                        <td><?= $item['price'] ?></td>
                                        <td>
                                            <a href="edit-shipping.php?id=<?= $item['id']; ?>" class="btn btn-primary">Edit</a>
                                        </td>
                                        <td>
                                            <form action="" method="POST" style="display:inline;">
                                                <input type="hidden" name="id" value="<?= $item['id']; ?>">
                                                <button name="delete_shipping_btn" class="btn btn-danger" type="submit">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
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
