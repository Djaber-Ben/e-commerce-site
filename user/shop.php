<?php
session_start();
include("../adminto/main/config/functions/myfunctions.php");


//fetching product by category
if (isset($_GET['category_id'])) {
  // Sanitize input
  $category_id = intval($_GET['category_id']); 

  // Fetch products for the selected category
  $products = getProductsByCategory($category_id); // Pass the category ID to your function

  // Fetch the category name
  $category_query = "SELECT name FROM categories WHERE id = $category_id";
  $category_result = mysqli_query($con, $category_query);
  if ($category_result && mysqli_num_rows($category_result) > 0) {
      $category = mysqli_fetch_assoc($category_result);
      $category_name = htmlspecialchars($category['name']);
  } else {
      $category_name = "Unknown"; // Fallback if no category is found
  }
} else {
  // No category selected, fetch all products
  $products = getAllProducts(); 
  $category_name = "All Products"; // Set the default category name to "All Products"
}

$cart_count = 0;

if (isset($_SESSION['cart'])) {
    $cart_count = count($_SESSION['cart']); // Count the number of products in the cart
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
                    <a href="index.php" class="nav__link">Home</a>
                </li>

                <li class="nav__item">
                    <a href="shop.php" class="nav__link active-link">Shop</a>
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
      <!--=============== BREADCRUMB ===============-->
      <section class="breadcrumb">
        <ul class="breadcrumb__list container">
         <li><a href="index.php" class="breadcrumb__link">Home</a></li>
         <li><span class="breadcrumb__link">></span></li>
         <li><span class="breadcrumb__link">Shop</span></li>
         <li><span class="breadcrumb__link">></span></li>
         <li><span class="breadcrumb__link"><?= htmlspecialchars($category_name) ?></span></li>
        </ul>
      </section>

      <!--=============== PRODUCTS ===============-->
     <section class="products">
    <p class="total__products">
        We found 
        <span>
            <?= isset($products) ? mysqli_num_rows($products) : 0 ?>
        </span> items for you
    </p>

    <div class="products__container grid">
    <?php
    if ($products && mysqli_num_rows($products) > 0) {
        while ($product = mysqli_fetch_assoc($products)) {
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
                        <a href="details.php?id='. $product['id'] .'" class="action__btn" aria-label="Quick View">
                            <i class="fi fi-rs-eye"></i>
                        </a>
                        <a href="#" class="action__btn" aria-label="Add To Wishlist">
                            <i class="fi fi-rs-heart"></i>
                        </a>
                      
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
                 
                </div>
            </div>';
        }
    } else {
        echo '<p>No products found.</p>';
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
