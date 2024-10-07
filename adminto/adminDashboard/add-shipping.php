<?php
include('includes/header.php');


?>

<div class="container">
    <div class="row">
       <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Add New Shipping Place</h4>
            </div>
            <div class="card-body">
                <form action="code.php" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-12">
                <label for="">place</label>
                <input required placeholder="Enter place of shipping" type="text" name="place" class="form-control">
                    </div>
                    <div class="col-md-12">
                <label for="">price</label>
                <input required placeholder="Enter price of shipping" type="number" name="price" class="form-control">
                    </div>         
                    <div class="col-md-12 py-4">
                        <button class="btn btn-primary" name="add_shipping-btn">save</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
       </div>
    </div>
</div>
<?php include('includes/footer.php'); ?>