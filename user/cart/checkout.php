<?php
session_start();
include("../../middleware/adminMiddlware.php");
// Check if cart data is passed
$cartData = isset($_POST['cart_data']) ? json_decode($_POST['cart_data'], true) : [];

// Calculate totals
$totalPrice = 0;
foreach ($cartData as $item) {
    $totalPrice += $item['price'] * $item['quantity'];
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
        <a href="index.php" class="nav__logo">
          <img src="assets/img/logo.svg" alt="" class="nav__logo-img">
        </a>
        <div class="nav__menu" id="nav-menu">
          <ul class="nav__list">
            <li class="nav__item">
              <a href="index.php" class="nav__link ">Home</a>
            </li>

            <li class="nav__item">
              <a href="shop.html" class="nav__link">Shop</a>
            </li>

            <li class="nav__item">
              <a href="accountsr.php" class="nav__link">My Account</a>
            </li>

            <li class="nav__item">
              <a href="contact.php" class="nav__link">contact</a>
            </li>

            <li class="nav__item">
              <a href="login-register.php" class="nav__link">Login</a>
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
          <a href="wishlist.html" class="header__action-btn">
          <img src="assets/img/icon-heart.svg" alt="">
          <span class="count">3</span>
          </a>

          <a href="cart.html" class="header__action-btn">
            <img src="assets/img/icon-cart.svg" alt="">
            <span class="count">3</span>
            </a>
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
         <li><span class="breadcrumb__link">Cart</span></li>
         <li><span class="breadcrumb__link">></span></li>
         <li><span class="breadcrumb__link">Checkout</span></li>
        </ul>
      </section>

      <!--=============== CHECKOUT ===============-->
      <section class="checkout section--lg">
    <div class="checkout__container container grid">
        <div class="checkout__group">
            <h3 class="section__title">Billing Details</h3>
            <form action="process_checkout.php" method="POST" class="form grid">
                <input type="text" name="name" placeholder="Name" class="form__input" required>
                <input type="text" name="address" placeholder="Address" class="form__input" required>
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
                <input type="text" name="postcode" placeholder="Postcode" class="form__input" required>
                <input type="text" name="phone" placeholder="Phone" class="form__input" required>
                <input type="email" name="email" placeholder="Email" class="form__input" required>

                <h3 class="checkout__title">Additional Information</h3>
                <textarea name="order_note" placeholder="Order Note" cols="30" rows="10" class="form__input textarea"></textarea>
        </div>

        <div class="checkout__group">
            <h3 class="section__title">Cart Totals</h3>
            <table class="order__table">
                <tr>
                    <th colspan="2">Products</th>
                    <th>Total</th>
                </tr>

                <!-- Display cart items -->
                <?php if (!empty($cartData)): ?>
                    <?php foreach ($cartData as $item): ?>
                        <tr>
                            <td><img src="../<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="order__img"></td>
                            <td>
                                <h3 class="table__title"><?= htmlspecialchars($item['name']) ?></h3>
                                <p class="table__quantity">X <?= htmlspecialchars($item['quantity']) ?></p>
                            </td>
                            <td><span class="table__price">$<?= number_format($item['price'] * $item['quantity'], 2) ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No products in the cart.</td>
                    </tr>
                <?php endif; ?>

                <tr>
        <td><span class="order__subtitle">SubTotal</span></td>
        <td colspan="2"><span class="table__price" id="cart-total">$280.00</span></td>
    </tr>
    <tr>
        <td><span class="order__subtitle">Shipping</span></td>
        <td colspan="2"><span class="table__price" id="shipping-price">$0.00</span></td> <!-- Shipping price here -->
    </tr>
    <tr>
        <td><span class="order__subtitle">Total</span></td>
        <td colspan="2"><span class="order__grand-total" id="final-total">$280.00</span></td> <!-- Final total here -->
    </tr>
            </table>
        </div>
    </div>

    <button type="submit" class="btn flex btn--md">Place Order</button>
    </form>
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
