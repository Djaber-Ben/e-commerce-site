<?php
session_start();
include("../../middleware/adminMiddlware.php");
// Update cart item quantity if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $id => $quantity) {
        if ($quantity > 0) {
            $_SESSION['cart'][$id]['quantity'] = (int)$quantity; // Update quantity
        } else {
            // Optionally remove item if quantity is zero
            unset($_SESSION['cart'][$id]);
        }
    }
}

// Calculate total price of products in the cart
$totalPrice = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $totalPrice += $item['price'] * $item['quantity']; // Accumulate total
    }
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
    <link rel="stylesheet" href="../assets/css/styles.css" />

    <title>Ecommerce Website</title>
  </head>
  <body>
    <!--=============== HEADER ===============-->
    <header class="header">
    <nav class="nav container">
        <a href="../index.php" class="nav__logo">
            <img src="../assets/img/logo.svg" alt="" class="nav__logo-img">
        </a>
        <div class="nav__menu" id="nav-menu">
            <div class="nav__menu-top">
                <a href="../index.php" class="nav__menu-logo">
                    <img src="../assets/img/logo.svg" alt="">
                </a>

                <div class="nav__close" id="nav-close">
                    <i class="fi fi-rs-cross-small"></i>
                </div>
            </div>
            <ul class="nav__list">
                <li class="nav__item">
                    <a href="../index.php" class="nav__link ">Home</a>
                </li>

                <li class="nav__item">
                    <a href="../shop.php" class="nav__link">Shop</a>
                </li>

                <li class="nav__item">
                    <!-- Check if the user is logged in -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="../accounts.php" class="nav__link">My Account</a>
                    <?php else: ?>
                        <a href="../login-register.php" class="nav__link">My Account</a>
                    <?php endif; ?>
                </li>

                <li class="nav__item">
                    <a href="../contact.php" class="nav__link">Contact</a>
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
        <a href="../wishlist/wishlist.php" class="header__action-btn">
    <img src="../assets/img/icon-heart.svg" alt="">
    <span class="count"><?= $wishlistCount ?></span> <!-- Displaying the wishlist count -->
</a>
    
            <div class="header__action-btn nav__toggle" id="nav-toggle">
                <img src="../assets/img/menu-burger.svg" alt="">
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
         <li><span class="breadcrumb__link">Profile</span></li>
         <li><span class="breadcrumb__link">></span></li>
         <li><span class="breadcrumb__link">Cart</span></li>
        </ul>
      </section>

      <!--=============== CART ===============-->
   <section class="cart section--lg container">
    <div class="table__container">
        <form action="" method="POST"> <!-- Form for updating cart -->
            <table class="table">
                <tr>
                    <th>Images</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Remove</th>
                </tr>

                <!-- Display cart items -->
                <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                    <?php foreach ($_SESSION['cart'] as $id => $item): ?>
                        <tr>
                            <td><img src="../<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="table__img"></td>
                            <td><h3 class="table__title"><?= htmlspecialchars($item['name']) ?></h3></td>
                            <td><span class="table__price">$<?= number_format($item['price'], 2) ?></span></td>
                            <td><input type="number" name="quantity[<?= $id ?>]" value="<?= htmlspecialchars($item['quantity']) ?>" class="quantity"></td>
                            <td><span class="table__subtotal">$<?= number_format($item['price'] * $item['quantity'], 2) ?></span></td>
                            <td><a href="remove_from_cart.php?id=<?= $item['id'] ?>" class="table__trash"><i class="fi fi-rs-trash"></i></a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">Your cart is empty.</td>
                    </tr>
                <?php endif; ?>
            </table>

            <div class="cart__actions">
                <button type="submit" name="update_cart" class="btn flex btn--md">Update Cart</button>
                <a href="../shop.php" class="btn flex btn--md">
                    <i class="fi-rs-shuffle"></i> Continue Shopping
                </a>
            </div>
        </form>
    </div>

    <div class="divider">
        <i class="fi fi-rs-fingerprint"></i>
    </div>

    <div class="cart__group grid">
        <div>
            <div class="cart__shipping">
                <h3 class="section__title">Calculate Shipping</h3>
                <form action="" class="form grid">
                    <select id="city-select" name="city" class="form__input">
                        <option value="">Select City</option>
                        <?php
                        $shippingPlaces = getAll('shipping'); // Fetch data from the 'shipping' table
                        if (mysqli_num_rows($shippingPlaces) > 0) {
                            foreach ($shippingPlaces as $place) {
                                ?>
                                <option value="<?= $place['id']; ?>" data-price="<?= $place['price']; ?>">
                                    <?= $place['place']; ?>
                                </option>
                                <?php
                            }
                        } else {
                            echo "<option value=''>No cities available</option>";
                        }
                        ?>
                    </select>

                    <div class="form__group grid">
                        <input type="text" placeholder="Postal Code" class="form__input" id="postal-code">
                    </div>

                    <div class="form__btn">
                        <button class="btn flex btn--sm">
                            <i class="fi-rs-shuffle"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="cart__total">
            <h3 class="section__title">Cart Totals</h3>
            <table class="cart__total-table">
                <tr>
                    <td><span class="cart__total-title">Cart Total</span></td>
                    <td><span class="cart__total-price" id="cart-total">$<?= number_format($totalPrice, 2) ?></span></td>
                </tr>
                <tr>
                    <td><span class="cart__total-title">Shipping</span></td>
                    <td><span class="cart__total-price" id="shipping-price">00</span></td>
                </tr>
                <tr>
                    <td><span class="cart__total-title">Total</span></td>
                    <td><span class="cart__total-price" id="final-total">$<?= number_format($totalPrice + 0, 2) ?></span></td>
                </tr>
            </table>

            <!-- Form for proceeding to checkout -->
            <form action="checkout.php" method="POST">
                <input type="hidden" name="cart_data" value="<?= htmlspecialchars(json_encode($_SESSION['cart'])) ?>">
                <button type="submit" class="btn flex btn--md" id="checkout-btn">
                    <i class="fi fi-rs-box-alt"></i> Proceed To Checkout
                </button>
            </form>

            <div id="error-message" style="color: red; margin-top: 10px;"></div>
        </div>
    </div>
</section>



      <!--=============== NEWSLETTER ===============-->
      <section class="newsletter section">
        <div class="newsletter__container container grid">
          <h3 class="newsletter__title flex">
            <img src="../assets/img/icon-email.svg" alt="" class="newsletter__icon">
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
          <img src="../assets/img/logo.svg" alt="" class="footer__logo-img">
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
            <a href=""> <img src="../assets/img/icon-facebook.svg" alt="" 
              class="footer__social-icon"></a>
            <a href=""> <img src="../assets/img/icon-instagram.svg" 
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

    <!--=============== SCROLL REVEAL ===============-->
    <script src="https://unpkg.com/scrollreveal"></script>

    <!--=============== MAIN JS ===============-->
    <script src="../assets/js/main.js"></script>
    <script src="assets/js.js"></script> 
  

  </body>

  <style>
select {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  background: url('path/to/custom-arrow.svg') no-repeat right center;
  background-size: 12px; /* Size of the custom arrow */
  padding-right: 30px; /* Adjust based on the size of your custom arrow */
}

  </style>
</html>
