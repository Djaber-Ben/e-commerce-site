<?php
include("../adminto/main/config/functions/myfunctions.php");
session_start();
$cart_count = 0;

if (isset($_SESSION['cart'])) {
  $cart_count = count($_SESSION['cart']); // Count the number of products in the cart
}

if (isset($_SESSION['error_message'])) {
  echo "<div class='error'>{$_SESSION['error_message']}</div>";
  unset($_SESSION['error_message']); // Clear the message after displaying
}

if (isset($_SESSION['user_id'])) {
  $userId = $_SESSION['user_id'];

  // Query to count the number of items in the user's wishlist
  $query = "SELECT COUNT(*) AS wishlist_count FROM wishlist WHERE user_id = ?";
  $stmt = $con->prepare($query);
  $stmt->bind_param("i", $userId);
  $stmt->execute();
  $result = $stmt->get_result();
  
  // Fetch the count
  $wishlistCount = $result->fetch_assoc()['wishlist_count'];

  // Close the statement
  $stmt->close();
} else {
  // If the user is not logged in, set count to 0
  $wishlistCount = 0;
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!--=============== FLATICON ===============-->
    <link rel='stylesheet' 
    href='https://cdn-uicons.flaticon.com/2.5.1/uicons-regular-straight/css/uicons-regular-straight.css'>

    <!--=============== SWIPER CSS ===============-->
    <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
       />

    <!--=============== CSS ===============-->
    <link rel="stylesheet" href="assets/css/styles.css" />

    <title>Ecommerce Website</title>
  </head>
  <body>
    <!--=============== HEADER ===============-->
    <header class="header">
    <nav class="nav container">
        <a href="index.php" class="nav__logo">
            <img src="assets/img/logo.svg" alt="" class="nav__logo-img">
        </a>
        <div class="nav__menu" id="nav-menu">
            <div class="nav__menu-top">
                <a href="index.php" class="nav__menu-logo">
                    <img src="assets/img/logo.svg" alt="">
                </a>

                <div class="nav__close" id="nav-close">
                    <i class="fi fi-rs-cross-small"></i>
                </div>
            </div>
            <ul class="nav__list">
                <li class="nav__item">
                    <a href="index.php" class="nav__link ">Home</a>
                </li>

                <li class="nav__item">
                    <a href="shop.php" class="nav__link">Shop</a>
                </li>

                <li class="nav__item">
                    <!-- Check if the user is logged in -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="accounts.php" class="nav__link">My Account</a>
                    <?php else: ?>
                        <a href="login-register.php" class="nav__link">My Account</a>
                    <?php endif; ?>
                </li>

                <li class="nav__item">
                    <a href="contact.php" class="nav__link">Contact</a>
                </li>
            </ul>

            <div class="header__search">
                <input type="text" placeholder="Search for items..." class="form__input"/>
                <button class="search__btn">
                    <img src="assets/img/search.png" alt="">
                </button>
            </div>
        </div>

        <div class="header__user-action">
        <a href="wishlist/wishlist.php" class="header__action-btn">
    <img src="assets/img/icon-heart.svg" alt="">
    <span class="count"><?= $wishlistCount ?></span> <!-- Displaying the wishlist count -->
</a>
            <a href="cart/cart.php" class="header__action-btn">
    <img src="assets/img/icon-cart.svg" alt="Cart Icon">
    <span class="count"><?= $cart_count ?></span>  <!-- Display the total count here -->
</a>
            <div class="header__action-btn nav__toggle" id="nav-toggle">
                <img src="assets/img/menu-burger.svg" alt="">
            </div>
        </div>
    </nav>
</header>

    <!--=============== MAIN ===============-->
    <main class="main">
    <?php
// Include your database con

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $productResult = getProductById($id); // Call the combined function

    if (mysqli_num_rows($productResult) > 0) {
        $product = mysqli_fetch_array($productResult);

        // Assuming images are stored as a comma-separated string in the database
        $images = explode(',', $product['images']);
        $defaultImage = '../uploads/images/product/' . trim($images[0]); // Default image
?>
      <!--=============== BREADCRUMB ===============-->
      <section class="breadcrumb">
        <ul class="breadcrumb__list container">
         <li><a href="index.php" class="breadcrumb__link">Home</a></li>
         <li><span class="breadcrumb__link">></span></li>
         <li><span class="breadcrumb__link">Fashion</span></li>
         <li><span class="breadcrumb__link">></span></li>
         <li><span class="breadcrumb__link"><?= htmlspecialchars($product['name']) ?></span></li>
        </ul>
      </section>

      <!--=============== DETAILS ===============-->

          <section class="details">
    <div class="details__container container grid">
        <div class=" details__group">
            <!-- Default product image (large image) -->
            <img src="<?= $defaultImage ?>" alt="Product Image" class="details__img">

            <!-- Display all images as small images (including the main image) -->
            <div class="details__small-images grid">
                <?php foreach ($images as $img): ?>
                    <img src="../uploads/images/product/<?= trim($img) ?>" alt="Product Image" class="details__small-img">
                <?php endforeach; ?>
            </div>
        </div>

        <div class="details__info-group details__group">
            <h3 class="details__title"><?= htmlspecialchars($product['name']) ?></h3>
            <p class="details__brand">Brands: <span><?= htmlspecialchars($product['brand']) ?></span></p>

            <div class="details__price flex">
                <span class="new__price">$<?= number_format($product['selling_price'], 2) ?></span>
                <span class="old__price">$<?= number_format($product['original_price'], 2) ?></span>
                <span class="save__price">
                    <?= number_format((($product['original_price'] - $product['selling_price']) / $product['original_price']) * 100, 0) ?>% Off
                </span>
            </div>
            <p class="short__description"><?= htmlspecialchars($product['description']) ?></p>

            <ul class="product__list">
                <li class="list__item flex"><i class="fi-rs-crown"></i> 1 Year Warranty</li>
                <li class="list__item flex"><i class="fi-rs-refresh"></i> 30 Day Return Policy</li>
                <li class="list__item flex"><i class="fi-rs-credit-card"><?= htmlspecialchars($product['qty']) ?></i></li>
            </ul>

            <div class="details__color flex">
                <span class="details__color-title">Color</span>
                <ul class="color__list">
                    <li><a href="#" class="color__link" style="background-color: <?= htmlspecialchars($product['color']) ?>;"></a></li>
                </ul>
            </div>

            <div class="details__size flex">
                <span class="details__size-title">Size</span>
                <ul class="size__list">
                    <li><a href="#" class="size__link"><?= htmlspecialchars($product['size']) ?></a></li>
                </ul>
            </div>

            <div class="details__action">
                
                <form action="cart/cart_actions.php" method="POST">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
              <input type="hidden" name="product_name" value="<?= htmlspecialchars($product['name']) ?>">
               <input type="hidden" name="product_price" value="<?= $product['selling_price'] ?>">
               <input type="hidden" name="product_image" value="<?= $defaultImage ?>">
                 <input type="hidden" name="product_qty" value="<?= $product['qty'] ?>">
                <input type="number" name="quantity" class="quantity" value="1" min="1" max="10">
               <button type="submit" class="btn btn--sm">Add to Cart</button>
</form>

<?php if (isset($_SESSION['user_id'])): ?>
        <form action="wishlist/add_to_wishlist.php" method="POST" style="display: inline;">
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>"> <!-- Product ID -->
            <input type="hidden" name="product_image" value="<?= $defaultImage ?>"> <!-- Product Image -->
            <input type="hidden" name="selling_price" value="<?= $product['selling_price'] ?>"> <!-- Selling Price -->
            <input type="hidden" name="product_qty" value="<?= $product['qty'] ?>"> <!-- Quantity -->
            <button type="submit" class="details__action-btn" style="background: none; border: none; cursor: pointer;">
                <i class="fi fi-rs-heart"></i>
            </button>
        </form>
    <?php else: ?>
        <a href="login-register.php" class="nav__link"><i class="fi fi-rs-heart" style="margin-top:300px" ></i></a>
    <?php endif; ?>
            </div>

            <ul class="details__meta">
                <li class="meta__list flex"><span>SKU:</span>FWM15VKT</li>
                <li class="meta__list flex"><span>Tags:</span><?= htmlspecialchars($product['category_name']) ?></li>
                <li class="meta__list flex"><span>Availability:</span><?= $product['qty'] ?> Items In Stock</li>
            </ul>
        </div>
    </div>
          </section>



      <!--=============== DETAILS TAB ===============-->
      <section class="details__tab container">
        <div class="detail__tabs">
          <span class="detail__tab active-tab" data-target="#info">
            Additional Info
          </span>
          <span class="detail__tab" data-target="#reviews">Reviews(3)</span>
        </div>

        <div class="details__tabs-content">
          <div class="details__tab-content active-tab" content id="info">
            <table class="info__table">
              <tr>
                <th>Quantitie</th>
                <td><?= htmlspecialchars($product['qty']) ?></td>
              </tr>

              <tr>
                <th>Height</th>
                <td><?= htmlspecialchars($product['height']) ?></td>
              </tr>
               <tr> 
                <th>Width</th>
                <td><?= htmlspecialchars($product['width']) ?></td>
              </tr>
     

              <tr>
                <th>Color</th>
                <td><?= htmlspecialchars($product['color']) ?></td>
              </tr>
              <tr>
                <th>Size</th>
                <td><?= htmlspecialchars($product['brand']) ?></td>
              </tr>
            </table>
          </div>

          <div class="details__tab-content" content id="reviews">
            <div class="reviews__container grid">
              <div class="review__single">
                <div>
                  <img src="assets/img/avatar-1.jpg" 
                  alt=""
                  class="review__img"
                  />
                  <h4 class="review__title">Jacky Chan</h4>
                </div>
                <div class="review__data">
                  <div class="review__rating">
                    <i class="fi fi-rs-star"></i>
                    <i class="fi fi-rs-star"></i>
                    <i class="fi fi-rs-star"></i>
                    <i class="fi fi-rs-star"></i>
                    <i class="fi fi-rs-star"></i>
                  </div>
                  <p class="review__description">Thank you very fast shopping form poland
                    3 days</p>
                    <span class="review__date">December 4, 2020 at 3:12 pm</span>
                </div>
              </div>
              <div class="review__single">
                <div>
                  <img src="assets/img/avatar-2.jpg" 
                  alt=""
                  class="review__img"
                  />
                  <h4 class="review__title">Zakaria</h4>
                </div>
                <div class="review__data">
                  <div class="review__rating">
                    <i class="fi fi-rs-star"></i>
                    <i class="fi fi-rs-star"></i>
                    <i class="fi fi-rs-star"></i>
                    <i class="fi fi-rs-star"></i>
                    <i class="fi fi-rs-star"></i>
                  </div>
                  <p class="review__description">Great Low Price And Works Well</p>
                    <span class="review__data">December 4, 2020 at 3:12 pm</span>
                </div>
              </div>
              <div class="review__single">
                <div>
                  <img src="assets/img/avatar-3.jpg" 
                  alt=""
                  class="review__img"
                  />
                  <h4 class="review__title">Amine</h4>
                </div>
                <div class="review__data">
                  <div class="review__rating">
                    <i class="fi fi-rs-star"></i>
                    <i class="fi fi-rs-star"></i>
                    <i class="fi fi-rs-star"></i>
                    <i class="fi fi-rs-star"></i>
                    <i class="fi fi-rs-star"></i>
                  </div>
                  <p class="review__description">Authentic And Beautiful,Love this way more 
                    than ever expected they are great earphones
                  </p>
                    <span class="review__data">December 4, 2020 at 3:12 pm</span>
                </div>
              </div>
            </div>

            <div class="review__form">
              <h4 class="review__form-title">Add a Review</h4>
              <div class="review__data">
                <div class="review__product">
                  <i class="fi fi-rs-star"></i>
                  <i class="fi fi-rs-star"></i>
                  <i class="fi fi-rs-star"></i>
                  <i class="fi fi-rs-star"></i>
                  <i class="fi fi-rs-star"></i>
                </div>

                <form action="" class="form grid">
                <textarea class="form__input textarea" placeholder="Write a comment"></textarea>

                <div class="form__group grid">
                  <input type="text" class="form__input" placeholder="Name">
                  <input type="text" class="form__input" placeholder="Email">
                </div>

                <div class="form__btn">
                  <button class="btn">Submit A Review</button>
                </div>
                </form>
            </div>
          </div>
        </div>
      </section>

<?php 
    } else {
        echo "Product Not Found";
    }
} else {
    echo 'ID missing from the URL';
}
?>
      <!--=============== PRODUCTS ===============-->
      <?php
// Fetch the selected product's ID from the URL


// Fetch the category ID for the selected product
$query = "SELECT category_id FROM products WHERE id = $id LIMIT 1";
$result = mysqli_query($con, $query);
$selectedProduct = mysqli_fetch_assoc($result);
$categoryId = $selectedProduct['category_id'];

// Fetch related products based on the category ID using the provided function
$relatedProductsQuery = getpbyc('products');
?>

<section class="products container section--lg">
    <h3 class="section__title"><span>Related </span>Products</h3>

    <div class="products__container grid">
        <?php
        // Loop through related products and display only those matching the category ID
        if ($relatedProductsQuery && mysqli_num_rows($relatedProductsQuery) > 0) {
            while ($product = mysqli_fetch_assoc($relatedProductsQuery)) {
                if ($product['category_id'] == $categoryId && $product['id'] != $id) {
                    $images = explode(',', $product['images']);
                    $defaultImage = '../uploads/images/product/' . trim($images[0]);
                    $hoverImage = '../uploads/images/product/' . trim($images[1]);

                    echo '
                    <div class="product__item">
                        <div class="product__banner">
                            <a href="details.php?id=' . $product['id'] . '" class="product__image">
                                <img src="' . $defaultImage . '" alt="Product Image 1" class="product__img default">
                                <img src="' . $hoverImage . '" alt="Product Image 2" class="product__img hover">
                            </a>
                            <div class="product__actions">
                                <a href="details.php?id=' . $product['id'] . '" class="action__btn" aria-label="Quick View">
                                    <i class="fi fi-rs-eye"></i>
                                </a>
                                <a href="#" class="action__btn" aria-label="Add To Wishlist">
                                    <i class="fi fi-rs-heart"></i>
                                </a>
                                <a href="#" class="action__btn" aria-label="contact">
                                    <i class="fi fi-rs-shuffle"></i>
                                </a>
                            </div>
                            <div class="product__badge light-pink">Hot</div>
                        </div>
                        <div class="product__content">
                            <span class="product__category">' . htmlspecialchars($product['category_name']) . '</span>
                            <a href="details.php?id=' . $product['id'] . '">
                                <h3 class="product__title">' . htmlspecialchars($product['name']) . '</h3>
                            </a>
                            <div class="product__rating">
                                ' . str_repeat('<i class="fi fi-rs-star"></i>', 5) . '
                            </div>
                            <div class="product__price flex">
                                <span class="new__price">$' . number_format($product['selling_price'], 2) . '</span>
                                <span class="old__price">$' . number_format($product['original_price'], 2) . '</span>
                            </div>
                            <a href="#" class="action__btn cart__btn" aria-label="Add To Cart">
                                <i class="fi fi-rs-shopping-bag-add"></i>
                            </a>
                        </div>
                    </div>';
                }
            }
        } else {
            echo '<p>No related products found.</p>';
        }
        ?>
    </div>
</section>


      <!--=============== NEWSLETTER ===============-->
      <section class="newsletter section">
        <div class="newsletter__container container grid">
          <h3 class="newsletter__title flex">
            <img src="assets/img/icon-email.svg" alt="" class="newsletter__icon">
            Sign up to NewsLetter
          </h3>
          <p class="newslettter__description">
             ...and receive $25 coupon for first shopping.
          </p>
          <form action="" class="newsletter__form">
            <input type="text" placeholder="Enter your email" 
            class="newsletter__input"/>
            <button type="submit" class="newsletter__btn">Subscribe</button>
          </form>
        </div>
      </section>
    </main>

    <!--=============== FOOTER ===============-->
    <footer class="footer container">
    <div class="footer__container grid">
      <div class="footer__content">
        <a href="index.php" class="footer__logo">
          <img src="assets/img/logo.svg" alt="" class="footer__logo-img">
        </a>

        <p class="footer__description">
          <span>Address:</span> 88 rue mezouar abdelkader birkhadem
        </p>

        <h4 class="footer__subtitle">Contact</h4>
        <p class="footer__description">
          <span>Phone:</span> +213 551239279 / +91 01 2345 6789 
        </p>

        <p class="footer__description">
          <span>Hours:</span> 10:00 - 18:00, Mon - Sat
        </p>

        <div class="footer__social">
          <h4 class="footer__subtitle ">Follow Me</h4>

          <div class="footer__social-links flex">
            <a href=""> <img src="assets/img/icon-facebook.svg" alt="" 
              class="footer__social-icon"></a>
            <a href=""> <img src="assets/img/icon-instagram.svg" 
              class="footer__social-icon"></a>
          </div>
        </div>
      </div>

      <div class="footer__content">
        <h3 class="footer__title">Address</h3>
        <ul class="footer__links">
          <li><a href="" class="footer__link">About Us</a></li>
          <li><a href="" class="footer__link">Delivery Information</a></li>
          <li><a href="" class="footer__link">Privacy Policy</a></li>
          <li><a href="" class="footer__link">Terms & Conditions</a></li>
          <li><a href="" class="footer__link">Contact Us</a></li>
        </ul>
      </div>

      <div class="footer__content">
        <h3 class="footer__title">My Account</h3>
        <ul class="footer__links">
          <li><a href="" class="footer__link">Sign In</a></li>
          <li><a href="" class="footer__link">View Cart</a></li>
          <li><a href="" class="footer__link">My Wislist</a></li>
          <li><a href="" class="footer__link">Help</a></li>
          <li><a href="" class="footer__link">Order</a></li>
        </ul>
      </div>
    </div>

    <div class="fottor__bottom">
      <p class="copyright">&copy; 2023 Amine, All right reversed</p>
      <span class="desinger">Designed by CrypticalCoder</span>
    </div>
  </footer>

    <!--=============== SWIPER JS ===============-->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!--=============== MAIN JS ===============-->
    <script src="assets/js/main.js"></script>
  </body>
</html>
