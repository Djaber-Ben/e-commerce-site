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
                <h4>Add Products</h4>
            </div>
            <div class="card-body">
                <form action="code.php" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">Select Category</label>
                            <select required name="category_id" class="form-control mb-2">
                                <option selected>select category</option>
                                <?php 
                                $categories = getAll("categories");
                                if(mysqli_num_rows($categories) > 0){
                                    foreach($categories as $item){
                                ?>
                                <option value="<?= $item['id'] ?>"><?= $item['name'] ?></option>
                                <?php 
                                    }
                                } else {
                                    echo "No Categories available";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="">Name</label>
                            <input required placeholder="Enter Name of product" type="text" name="name" class="form-control md-2">
                        </div>

                        <div class="col-md-6">
                            <label class="mb-0">Brand</label>
                            <input required placeholder="Enter brand of product" type="text" name="brand" class="form-control mb-2">
                        </div>

                        <div class="col-md-6">
                            <label class="mb-0">Quantity</label>
                            <input required placeholder="Enter quantity" type="number" name="qty" class="form-control mb-2">
                        </div>

                        <div class="col-md-6">
                            <label class="mb-0">Original Price</label>
                            <input required placeholder="Enter original price" type="number" name="original_price" class="form-control mb-2">
                        </div>

                        <div class="col-md-6">
                            <label class="mb-0">Selling Price</label>
                            <input required placeholder="Enter selling price" type="number" name="selling_price" class="form-control mb-2">
                        </div>

                        <div class="col-md-12">
                            <label class="mb-0">Description</label>
                            <textarea required rows="3" type="text" name="description" class="form-control mb-2"></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="mb-0">Width</label>
                            <input required placeholder="Enter width of product" type="number" name="width" class="form-control mb-2">
                        </div>

                        <div class="col-md-6">
                            <label class="mb-0">Height</label>
                            <input required placeholder="Enter height of product" type="number" name="height" class="form-control mb-2">
                        </div>

                        <div class="col-md-6">
                            <label class="mb-0">Colors</label>
                            <input required placeholder="Enter colors of product" type="text" name="color" class="form-control mb-2">
                        </div>

                        <div class="col-md-6">
                            <label class="mb-0">Size</label>
                            <input required placeholder="Enter size of product" type="number" name="size" class="form-control mb-2">
                        </div>

                        <div class="col-md-12">
                            <label class="mb-0">Upload Images</label>
                            <!-- Changed input type to accept multiple image uploads -->
                            <input required type="file" name="images[]" required multiple class="form-control mb-2">
                        </div>

                        <div class="row py-4">
                            <div class="col-md-3">
                                <label for="">Status</label>
                                <input type="checkbox" name="status">
                            </div>
                            <div class="col-md-3">
                                <label for="">Popular</label>
                                <input type="checkbox" name="popular">
                            </div>
                        </div>

                        <div class="col-md-12 py-4">
                            <button class="btn btn-primary" type="submit" name="add_product-btn">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
       </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
