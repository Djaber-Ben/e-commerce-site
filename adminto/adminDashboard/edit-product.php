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
       <?php
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            $products = getById("products",$id);
            if(mysqli_num_rows($products)> 0){
                $data = mysqli_fetch_array($products);
        ?>

        <div class="card">
            <div class="card-header">
                <h4>Edit Products</h4>
                <a href="products.php" class="btn btn-primary float-end">Back</a>
            </div>
            <div class="card-body">
                <form action="code.php" method="POST" enctype="multipart/form-data">
                <div class="row">
                <div class="col-md-6">
                <label for="">Select Category</label>

                <select required name="category_id" class="form-control mb-2" >
                  <option selected>select category</option>
                  <?php 
                  $categories = getAll("categories");

                  if(mysqli_num_rows($categories) > 0){
                    foreach($categories as $item){

                  ?>
                 <option value="<?= $item['id'] ?>" <?= $data['category_id'] ==  $item['id']? 'selected':'' ?>><?= $item['name'] ?></option>
               
                  <?php 
                    }
                }
                else {
                    echo "No Categories available";
                }
                  ?>

                </select>
                    </div>
                    <input type="hidden" name="product_id" value="<?= $data['id'] ?>" >
                    <div class="col-md-6">
                <label class="">Name</label>
                <input value="<?= $data['name'] ?>" required placeholder="Enter Name of product" type="text" name="name" class="form-control md-2">
                    </div>

                    <div class="col-md-6">
                <label class="mb-0">brand</label>
                <input value="<?= $data['brand'] ?>" required placeholder="Enter brand of product" type="text" name="brand" class="form-control mb-2">
                    </div>

                    <div class="col-md-6">
                <label class="mb-0">quantite</label>
                <input value="<?= $data['qty'] ?>" required placeholder="Enter quantite" type="number" name="qty" class="form-control mb-2">
                    </div>

                    <div class="col-md-6">
                <label class="mb-0">original price</label>
                <input value="<?= $data['original_price'] ?>" required placeholder="Enter original price" type="number" name="original_price" class="form-control mb-2">
                    </div>

                    <div class="col-md-6">
                <label class="mb-0">selling price</label>
                <input value="<?= $data['selling_price'] ?>" required placeholder="Enter selling price" type="number" name="selling_price" class="form-control mb-2">
                    </div>

                    <div class="col-md-12">
                <label class="mb-0">Description</label>
                <textarea value="" required rows="3" type="text" name="description" class="form-control mb-2"><?= $data['description'] ?> </textarea>
                    </div>

                    <div class="col-md-6">
                <label class="mb-0">width</label>
                <input value="<?= $data['width'] ?>" required placeholder="Enter width of product" type="number" name="width" class="form-control mb-2">
                    </div>

                    <div class="col-md-6">
                <label class="mb-0">height</label>
                <input value="<?= $data['height'] ?>" required placeholder="Enter height of product" type="number" name="height" class="form-control mb-2">
                    </div>

                    <div class="col-md-6">
                <label class="mb-0">colors</label>
                <input value="<?= $data['name'] ?>" required placeholder="Enter colors of product" type="text" name="color" class="form-control mb-2">
                    </div>

                    <div class="col-md-6">
                <label class="mb-0">size</label>
                <input value="<?= $data['size'] ?>" required placeholder="Enter size of product" type="number" name="size" class="form-control mb-2">
                    </div>

                    <div class="col-md-12">
    <label class="mb-0">Upload Images</label>
    <input type="hidden" name="old_images" value="<?= $data['images'] ?>" >
    <input type="file" name="images[]" class="form-control mb-2" multiple>

    <label for="">Current Images: </label>
    <div>
        <?php
        // Split the images string into an array
        $images = explode(',', $data['images']);  // Assuming images are stored as a comma-separated string

        // Loop through each image and display it
        foreach($images as $image) {
            ?>
            <img src="../../uploads/images/product/<?= $image ?>" height="100px" width="100px" alt="Product Image">
            <?php
        }
        ?>
    </div>
</div>

                  
                <div class="row py-4">
                    <div class="col-md-3">
                    <label for="">Status</label>
                    <input  type="checkbox" <?= $data['status'] == '0'?'':'checked' ?> name="status">
                    </div>
                    <div class="col-md-3">
                    <label for="">Popular</label>
                    <input type="checkbox" <?= $data['popular'] == '0'?'':'checked' ?> name="popular">
                    </div>
                </div>
                    <div class="col-md-12 py-4">
                        <button class="btn btn-primary" type="submit" name="update_product-btn">update</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
        <?php
            }
            else {
                echo "Product Not Found for the given Id";
            }
        }
        else {
            echo "id mising from url";
        }

        ?>
       </div>
    </div>
</div>
    <?php include('includes/footer.php'); ?>