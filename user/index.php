<?php
include("../adminto/main/config/functions/myfunctions.php");
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

// Count the total number of unique products in the cart
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
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.5.1/uicons-regular-straight/css/uicons-regular-straight.css'>

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
                    <a href="index.php" class="nav__link active-link">Home</a>
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
      <!--=============== HOME ===============-->
      <section class="home section--lg">
        <div class="home__container container grid">
          <div class="home__content">
          <span class="home__subtitle">Hot promotions</span>
          <h1 class="home__title">Fashion Trending <span>Great Collection</span></h1>
          <p class="home__description">save more with coupons and save up to 20%</p>
          <a href="shop.php" class="btn">Shop Now</a>
        </div>

        <img src="assets/img/home-img.png" alt="" class="home__img">
        </div>
      </section>

      <!--=============== CATEGORIES ===============-->
      <section class="categories container section">
    <h3 class="section__title"><span>Popular</span> Categories</h3>
    <div class="categories__container swiper">
        <div class="swiper-wrapper">
            <?php 
              $categories = getAll("categories");
            if(mysqli_num_rows($categories) > 0) {
                foreach($categories as $category) { ?>
                   <a href="shop.php?category_id=<?= $category['id'] ?>" class="category__item swiper-slide">
                <img src="../uploads/images/category/<?= $category['image'] ?>" alt="" class="category__img">
             <h3 class="category__title"><?= $category['name'] ?></h3> 
</a>

                <?php }
            } else {
                echo "<p>No categories available.</p>";
            }
            ?>
        </div>
        <div class="swiper-button-next"><i class="fi fi-rs-angle-small-right"></i></div>
        <div class="swiper-button-prev"><i class="fi fi-rs-angle-small-left"></i></div>
    </div>
</section>


      <!--=============== PRODUCTS ===============-->
      <section class="products section container">
        <div class="tab__btns">
          <span class="tab__btn active-tab" data-target="#featured">All</span>
          <span class="tab__btn" data-target="#popular">Popular</span>
          <span class="tab__btn" data-target="#new-added">New added</span>

        </div>
        <div class="tab__items">
          <div class="tab__item active-tab" content id="featured">
            <div class="products__container grid">
              
            <?php
       $products = getpbyc('products');
        if (mysqli_num_rows($products) > 0) {
    while ($product = mysqli_fetch_assoc($products)) {
        $images = explode(',', $product['images']);
        $defaultImage = '../uploads/images/product/' . trim($images[0]);
        $hoverImage = '../uploads/images/product/' . trim($images[1]);

        // Debugging output (optional)
  

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
          </div>
          <div class="tab__item"  content id="popular">
            <div class="products__container grid">       
            <?php
$products = getPopularProductsByCategory('products');
if (mysqli_num_rows($products) > 0) {
    while ($product = mysqli_fetch_assoc($products)) {
        $images = explode(',', $product['images']);
        $defaultImage = '../uploads/images/product/' . trim($images[0]);
        $hoverImage = '../uploads/images/product/' . trim($images[1]);

        // Debugging output (optional)
  

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
          </div>
          <div class="tab__item" id="new-added">
          <div class="products__container grid">
   
    <?php
       $products = getLast9ProductsByCategory('products');
       if ($products === false) {
           echo 'Error retrieving products: ' . mysqli_error($con);
       } elseif (mysqli_num_rows($products) > 0) {
           while ($product = mysqli_fetch_assoc($products)) {
               $images = explode(',', $product['images']);
               $defaultImage = isset($images[0]) ? '../uploads/images/product/' . trim($images[0]) : '../path/to/default/image.jpg';
               $hoverImage = isset($images[1]) ? '../uploads/images/product/' . trim($images[1]) : $defaultImage;

               echo '
               <div class="product__item">
                   <div class="product__banner">
                       <a href="details.php?id=' . $product['id'] . '" class="product__image">
                           <img src="' . $defaultImage . '" alt="Product Image 1" class="product__img default">
                           <img src="' . $hoverImage . '" alt="Product Image 2" class="product__img hover">
                       </a>
                       <div class="product__actions">
                           <a href=""details.php?id='. $product['id'] .'"" class="action__btn" aria-label="Quick View">
                               <i class="fi fi-rs-eye"></i>
                           </a>
                           <a href="#" class="action__btn" aria-label="Add To Wishlist">
                               <i class="fi fi-rs-heart"></i>
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
        </div>
        </div>
      </section>

      <!--=============== DEALS ===============-->
      <section class="deals section">
        <div class="deals__container container grid">
          <div class="deals__item">
            <div class="deals__group">
              <h3 class="deals__brand">Deal of The Day</h3>
              <span class="deals__category">Limited quantities</span>
            </div>
            <h4 class="deals__title">Summer Collection New Modern Design</h4>

            <div class="deals__price flex">
              <span class="new__price">$139.00</span>
              <span class="old__price">$160.99</span>
            </div>

            <div class="deals__group">
              <p class="deals__countdown-text">Hurry up! Offer End In:</p>

              <div class="countdown">
                <div class="countdown__amount">
                  <p class="countdown__period">02</p>
                  <span class="unit">Days</span>
                </div>
                <div class="countdown__amount">
                  <p class="countdown__period">22</p>
                  <span class="unit">Hours</span>
                </div>
                <div class="countdown__amount">
                  <p class="countdown__period">57</p>
                  <span class="unit">Mins</span>
                </div>
                <div class="countdown__amount">
                  <p class="countdown__period">24</p>
                  <span class="unit">Sec</span>
                </div>
              </div>
            </div>
            <div class="deals__btn">
              <a href="" class="btn btn-md">Shop Now</a>
            </div>
          </div>
          <div class="deals__item">
            <div class="deals__group">
              <h3 class="deals__brand">Women Clothing</h3>
              <span class="deals__category">Shirt & Bag</span>
            </div>
            <h4 class="deals__title">Try Something New on vacation</h4>

            <div class="deals__price flex">
              <span class="new__price">$178.00</span>
              <span class="old__price">$256.99</span>
            </div>

            <div class="deals__group">
              <p class="deals__countdown-text">Hurry up! Offer End In:</p>

              <div class="countdown">
                <div class="countdown__amount">
                  <p class="countdown__period">02</p>
                  <span class="unit">Days</span>
                </div>
                <div class="countdown__amount">
                  <p class="countdown__period">22</p>
                  <span class="unit">Hours</span>
                </div>
                <div class="countdown__amount">
                  <p class="countdown__period">57</p>
                  <span class="unit">Mins</span>
                </div>
                <div class="countdown__amount">
                  <p class="countdown__period">24</p>
                  <span class="unit">Sec</span>
                </div>
              </div>
            </div>
            <div class="deals__btn">
              <a href="" class="btn btn-md">Shop Now</a>
            </div>
          </div>
        </div>
      </section>

      <!--=============== NEW ARRIVALS ===============-->
      <section class="new__arrivals container section">
        <h3 class="section__title"><span>New</span> Arrivals</h3>
        <div class="new__container swiper">
        <div class="swiper-wrapper">
          <div class="product__item swiper-slide">
            <div class="product__banner">
              <a href="details.html" class="product__image">
                <img src="assets/img/product-1-1.jpg" alt="Product Image 1" class="product__img default">
                <img src="assets/img/product-1-2.jpg" alt="Product Image 2" class="product__img hover">
              </a>
              <div class="product__actions">
                <a href="#" class="action__btn" aria-label="Quick View">
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
              <span class="product__category">Clothing</span>
              <a href="details.html">
                <h3 class="product__title">Colorful Pattern Shirts</h3>
              </a>
              <div class="product__rating">
                <i class="fi fi-rs-star"></i>
                <i class="fi fi-rs-star"></i>
                <i class="fi fi-rs-star"></i>
                <i class="fi fi-rs-star"></i>
                <i class="fi fi-rs-star"></i>
              </div>
              <div class="product__price flex">
                <span class="new__price">$238.85</span>
                <span class="old__price">$245.80</span>
              </div>
              <a href="#" class="action__btn cart__btn" aria-label="Add To Cart">
                <i class="fi fi-rs-shopping-bag-add"></i>
              </a>
            </div>
          </div>
          
          <div class="product__item swiper-slide">
            <div class="product__banner">
              <a href="details.html" class="product__image">
                <img src="assets/img/product-2-1.jpg" alt="Product Image 1" class="product__img default">
                <img src="assets/img/product-2-2.jpg" alt="Product Image 2" class="product__img hover">
              </a>
              <div class="product__actions">
                <a href="#" class="action__btn" aria-label="Quick View">
                  <i class="fi fi-rs-eye"></i>
                </a>
                <a href="#" class="action__btn" aria-label="Add To Wishlist">
                  <i class="fi fi-rs-heart"></i>
                </a>
                <a href="#" class="action__btn" aria-label="contact">
                  <i class="fi fi-rs-shuffle"></i>
                </a>
              </div>
              <div class="product__badge light-green">Hot</div>
            </div>
            <div class="product__content">
              <span class="product__category">Clothing</span>
              <a href="details.html">
                <h3 class="product__title">Colorful Pattern Shirts</h3>
              </a>
              <div class="product__rating">
                <i class="fi fi-rs-star"></i>
                <i class="fi fi-rs-star"></i>
                <i class="fi fi-rs-star"></i>
                <i class="fi fi-rs-star"></i>
                <i class="fi fi-rs-star"></i>
              </div>
              <div class="product__price flex">
                <span class="new__price">$238.85</span>
                <span class="old__price">$245.80</span>
              </div>
              <a href="#" class="action__btn cart__btn" aria-label="Add To Cart">
                <i class="fi fi-rs-shopping-bag-add"></i>
              </a>
            </div>
          </div>
          
          <div class="product__item swiper-slide">
            <div class="product__banner">
              <a href="details.html" class="product__image">
                <img src="assets/img/product-3-1.jpg" alt="Product Image 1" class="product__img default">
                <img src="assets/img/product-3-2.jpg" alt="Product Image 2" class="product__img hover">
              </a>
              <div class="product__actions">
                <a href="#" class="action__btn" aria-label="Quick View">
                  <i class="fi fi-rs-eye"></i>
                </a>
                <a href="#" class="action__btn" aria-label="Add To Wishlist">
                  <i class="fi fi-rs-heart"></i>
                </a>
                <a href="#" class="action__btn" aria-label="contact">
                  <i class="fi fi-rs-shuffle"></i>
                </a>
              </div>
              <div class="product__badge light-orange">Hot</div>
            </div>
            <div class="product__content">
              <span class="product__category">Clothing</span>
              <a href="details.html">
                <h3 class="product__title">Colorful Pattern Shirts</h3>
              </a>
              <div class="product__rating">
                <i class="fi fi-rs-star"></i>
                <i class="fi fi-rs-star"></i>
                <i class="fi fi-rs-star"></i>
                <i class="fi fi-rs-star"></i>
                <i class="fi fi-rs-star"></i>
              </div>
              <div class="product__price flex">
                <span class="new__price">$238.85</span>
                <span class="old__price">$245.80</span>
              </div>
              <a href="#" class="action__btn cart__btn" aria-label="Add To Cart">
                <i class="fi fi-rs-shopping-bag-add"></i>
              </a>
            </div>
          </div>
          
          <div class="product__item swiper-slide">
            <div class="product__banner">
              <a href="details.html" class="product__image">
                <img src="assets/img/product-4-1.jpg" alt="Product Image 1" class="product__img default">
                <img src="assets/img/product-4-2.jpg" alt="Product Image 2" class="product__img hover">
              </a>
              <div class="product__actions">
                <a href="#" class="action__btn" aria-label="Quick View">
                  <i class="fi fi-rs-eye"></i>
                </a>
                <a href="#" class="action__btn" aria-label="Add To Wishlist">
                  <i class="fi fi-rs-heart"></i>
                </a>
                <a href="#" class="action__btn" aria-label="contact">
                  <i class="fi fi-rs-shuffle"></i>
                </a>
              </div>
              <div class="product__badge light-blue">Hot</div>
            </div>
            <div class="product__content">
              <span class="product__category">Clothing</span>
              <a href="details.html">
                <h3 class="product__title">Colorful Pattern Shirts</h3>
              </a>
              <div class="product__rating">
                <i class="fi fi-rs-star"></i>
                <i class="fi fi-rs-star"></i>
                <i class="fi fi-rs-star"></i>
                <i class="fi fi-rs-star"></i>
                <i class="fi fi-rs-star"></i>
              </div>
              <div class="product__price flex">
                <span class="new__price">$238.85</span>
                <span class="old__price">$245.80</span>
              </div>
              <a href="#" class="action__btn cart__btn" aria-label="Add To Cart">
                <i class="fi fi-rs-shopping-bag-add"></i>
              </a>
            </div>
          </div>
      
          <div class="product__item swiper-slide">
            <div class="product__banner">
              <a href="details.html" class="product__image">
                <img src="assets/img/product-5-1.jpg" alt="Product Image 1" class="product__img default">
                <img src="assets/img/product-5-2.jpg" alt="Product Image 2" class="product__img hover">
              </a>
              <div class="product__actions">
                <a href="#" class="action__btn" aria-label="Quick View">
                  <i class="fi fi-rs-eye"></i>
                </a>
                <a href="#" class="action__btn" aria-label="Add To Wishlist">
                  <i class="fi fi-rs-heart"></i>
                </a>
                <a href="#" class="action__btn" aria-label="contact">
                  <i class="fi fi-rs-shuffle"></i>
                </a>
              </div>
              <div class="product__badge light-pink">-22%</div>
            </div>
            <div class="product__content">
              <span class="product__category">Clothing</span>
              <a href="details.html">
                <h3 class="product__title">Colorful Pattern Shirts</h3>
              </a>
              <div class="product__rating">
                <i class="fi fi-rs-star"></i>
                <i class="fi fi-rs-star"></i>
                <i class="fi fi-rs-star"></i>
                <i class="fi fi-rs-star"></i>
                <i class="fi fi-rs-star"></i>
              </div>
              <div class="product__price flex">
                <span class="new__price">$238.85</span>
                <span class="old__price">$245.80</span>
              </div>
              <a href="#" class="action__btn cart__btn" aria-label="Add To Cart">
                <i class="fi fi-rs-shopping-bag-add"></i>
              </a>
            </div>
          </div>
        
          <div class="product__item swiper-slide">
            <div class="product__banner">
              <a href="details.html" class="product__image">
                <img src="assets/img/product-6-1.jpg" alt="Product Image 1" class="product__img default">
                <img src="assets/img/product-6-2.jpg" alt="Product Image 2" class="product__img hover">
              </a>
              <div class="product__actions">
                <a href="#" class="action__btn" aria-label="Quick View">
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
              <span class="product__category">Clothing</span>
              <a href="details.html">
                <h3 class="product__title">Colorful Pattern Shirts</h3>
              </a>
              <div class="product__rating">
                <i class="fi fi-rs-star"></i>
                <i class="fi fi-rs-star"></i>
                <i class="fi fi-rs-star"></i>
                <i class="fi fi-rs-star"></i>
                <i class="fi fi-rs-star"></i>
              </div>
              <div class="product__price flex">
                <span class="new__price">$238.85</span>
                <span class="old__price">$245.80</span>
              </div>
              <a href="#" class="action__btn cart__btn" aria-label="Add To Cart">
                <i class="fi fi-rs-shopping-bag-add"></i>
              </a>
            </div>
          </div>
        
          <div class="product__item swiper-slide">
            <div class="product__banner">
              <a href="details.html" class="product__image">
                <img src="assets/img/product-7-1.jpg" alt="Product Image 1" class="product__img default">
                <img src="assets/img/product-7-2.jpg" alt="Product Image 2" class="product__img hover">
              </a>
              <div class="product__actions">
                <a href="#" class="action__btn" aria-label="Quick View">
                  <i class="fi fi-rs-eye"></i>
                </a>
                <a href="#" class="action__btn" aria-label="Add To Wishlist">
                  <i class="fi fi-rs-heart"></i>
                </a>
                <a href="#" class="action__btn" aria-label="contact">
                  <i class="fi fi-rs-shuffle"></i>
                </a>
              </div>
              <div class="product__badge light-green">-30%</div>
            </div>
            <div class="product__content">
              <span class="product__category">Clothing</span>
              <a href="details.html">
                <h3 class="product__title">Colorful Pattern Shirts</h3>
              </a>
              <div class="product__rating">
                <i class="fi fi-rs-star"></i>
                <i class="fi fi-rs-star"></i>
                <i class="fi fi-rs-star"></i>
                <i class="fi fi-rs-star"></i>
                <i class="fi fi-rs-star"></i>
              </div>
              <div class="product__price flex">
                <span class="new__price">$238.85</span>
                <span class="old__price">$245.80</span>
              </div>
              <a href="#" class="action__btn cart__btn" aria-label="Add To Cart">
                <i class="fi fi-rs-shopping-bag-add"></i>
              </a>
            </div>
          </div>
        
          <div class="product__item swiper-slide">
            <div class="product__banner">
              <a href="details.html" class="product__image">
                <img src="assets/img/product-8-1.jpg" alt="Product Image 1" class="product__img default">
                <img src="assets/img/product-8-2.jpg" alt="Product Image 2" class="product__img hover">
              </a>
              <div class="product__actions">
                <a href="#" class="action__btn" aria-label="Quick View">
                  <i class="fi fi-rs-eye"></i>
                </a>
                <a href="#" class="action__btn" aria-label="Add To Wishlist">
                  <i class="fi fi-rs-heart"></i>
                </a>
                <a href="#" class="action__btn" aria-label="contact">
                  <i class="fi fi-rs-shuffle"></i>
                </a>
              </div>
              
            </div>
            <div class="product__content">
              <span class="product__category">Clothing</span>
              <a href="details.html">
                <h3 class="product__title">Colorful Pattern Shirts</h3>
              </a>
              <div class="product__rating">
                <i class="fi fi-rs-star"></i>
                <i class="fi fi-rs-star"></i>
                <i class="fi fi-rs-star"></i>
                <i class="fi fi-rs-star"></i>
                <i class="fi fi-rs-star"></i>
              </div>
              <div class="product__price flex">
                <span class="new__price">$238.85</span>
                <span class="old__price">$245.80</span>
              </div>
              <a href="#" class="action__btn cart__btn" aria-label="Add To Cart">
                <i class="fi fi-rs-shopping-bag-add"></i>
              </a>
            </div>
          </div>
        </div> 
        <div class="swiper-button-next"><i class="fi fi-rs-angle-small-right"></i></div>
        <div class="swiper-button-prev"><i class="fi fi-rs-angle-small-left"></i></div>
        </div>
      </section>

      <!--=============== SHOWCASE ===============-->
      <section class="showcase section">
        <div class="showcase__container container grid">
          <div class="showcase__wrapper">
            <h3 class="section__title">Hot Releases</h3>
            <div class="showcase__item">
              <a href="details.html" class="showcase__img-box">
               <img src="assets/img/showcase-img-7.jpg" alt="" class="showcase__img">
              </a>
              <div class="showcase__content">
                <a href="details.html" class="showcase__title">
                  <h4 class="showcase__title">Floral print casual Cotton Dress</h4>
                </a>
                <div class="showcase__price flex">
                  <span class="new__price">$238.85</span>
                  <span class="old__price">$244.85</span>
                </div>
              </div>
            </div>
            <div class="showcase__item">
              <a href="details.html" class="showcase__img-box">
               <img src="assets/img/showcase-img-8.jpg" alt="" class="showcase__img">
              </a>
              <div class="showcase__content">
                <a href="details.html" class="showcase__title">
                  <h4 class="showcase__title">Ruffled solid long sleeve</h4>
                </a>
                <div class="showcase__price flex">
                  <span class="new__price">$238.85</span>
                  <span class="old__price">$244.85</span>
                </div>
              </div>
            </div>
            <div class="showcase__item">
              <a href="details.html" class="showcase__img-box">
               <img src="assets/img/showcase-img-9.jpg" alt="" class="showcase__img">
              </a>
              <div class="showcase__content">
                <a href="details.html" class="showcase__title">
                  <h4 class="showcase__title">Multi-color Print V-neck</h4>
                </a>
                <div class="showcase__price flex">
                  <span class="new__price">$238.85</span>
                  <span class="old__price">$244.85</span>
                </div>
              </div>
            </div>
          </div>
          <div class="showcase__wrapper">
            <h3 class="section__title">Deals & Oulet</h3>
            <div class="showcase__item">
              <a href="details.html" class="showcase__img-box">
               <img src="assets/img/showcase-img-1.jpg" alt="" class="showcase__img">
              </a>
              <div class="showcase__content">
                <a href="details.html" class="showcase__title">
                  <h4 class="showcase__title">Fresh Print Patched T-shirt</h4>
                </a>
                <div class="showcase__price flex">
                  <span class="new__price">$238.85</span>
                  <span class="old__price">$244.85</span>
                </div>
              </div>
            </div>
            <div class="showcase__item">
              <a href="details.html" class="showcase__img-box">
               <img src="assets/img/showcase-img-2.jpg" alt="" class="showcase__img">
              </a>
              <div class="showcase__content">
                <a href="details.html" class="showcase__title">
                  <h4 class="showcase__title">Vintage Floral Print Dress</h4>
                </a>
                <div class="showcase__price flex">
                  <span class="new__price">$238.85</span>
                  <span class="old__price">$244.85</span>
                </div>
              </div>
            </div>
            <div class="showcase__item">
              <a href="details.html" class="showcase__img-box">
               <img src="assets/img/showcase-img-3.jpg" alt="" class="showcase__img">
              </a>
              <div class="showcase__content">
                <a href="details.html" class="showcase__title">
                  <h4 class="showcase__title">multi_color Stripe Circle</h4>
                </a>
                <div class="showcase__price flex">
                  <span class="new__price">$238.85</span>
                  <span class="old__price">$244.85</span>
                </div>
              </div>
            </div>
          </div>
          <div class="showcase__wrapper">
            <h3 class="section__title">Top selling</h3>
            <div class="showcase__item">
              <a href="details.html" class="showcase__img-box">
               <img src="assets/img/showcase-img-4.jpg" alt="" class="showcase__img">
              </a>
              <div class="showcase__content">
                <a href="details.html" class="showcase__title">
                  <h4 class="showcase__title">Geometric Printed long neck</h4>
                </a>
                <div class="showcase__price flex">
                  <span class="new__price">$238.85</span>
                  <span class="old__price">$244.85</span>
                </div>
              </div>
            </div>
            <div class="showcase__item">
              <a href="details.html" class="showcase__img-box">
               <img src="assets/img/showcase-img-5.jpg" alt="" class="showcase__img">
              </a>
              <div class="showcase__content">
                <a href="details.html" class="showcase__title">
                  <h4 class="showcase__title">Print Patchwork Maxi Drink</h4>
                </a>
                <div class="showcase__price flex">
                  <span class="new__price">$238.85</span>
                  <span class="old__price">$244.85</span>
                </div>
              </div>
            </div>
            <div class="showcase__item">
              <a href="details.html" class="showcase__img-box">
               <img src="assets/img/showcase-img-6.jpg" alt="" class="showcase__img">
              </a>
              <div class="showcase__content">
                <a href="details.html" class="showcase__title">
                  <h4 class="showcase__title">Daisy Floral Print Straps thing</h4>
                </a>
                <div class="showcase__price flex">
                  <span class="new__price">$238.85</span>
                  <span class="old__price">$244.85</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!--=============== NEWSLETTER ===============-->
      <section class="newsletter section home__newsletter">
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
