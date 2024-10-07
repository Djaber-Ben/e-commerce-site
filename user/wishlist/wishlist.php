<?php
session_start();
include("../../middleware/adminMiddlware.php");

// Fetching the user's wishlist products
$user_id = $_SESSION['user_id']; // Assuming you have the user ID stored in the session

$query = "SELECT p.*, w.* FROM wishlist w INNER JOIN products p ON w.product_id = p.id WHERE w.user_id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_count = 0;

if (isset($_SESSION['cart'])) {
    $cart_count = count($_SESSION['cart']); // Count the number of products in the cart
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!--=============== CSS ===============-->
    <link rel="stylesheet" href="../assets/css/styles.css" />
    <title>Ecommerce Website</title>
</head>
<body>
    <!--=============== HEADER ===============-->
    <header class="header">
        <nav class="nav container">
            <a href="index.html" class="nav__logo">
                <img src="../assets/img/logo.svg" alt="" class="nav__logo-img">
            </a>
            <div class="nav__menu" id="nav-menu">
                <ul class="nav__list">
                    <li class="nav__item"><a href="../index.php" class="nav__link ">Home</a></li>
                    <li class="nav__item"><a href="../shop.php" class="nav__link">Shop</a></li>
                    <li class="nav__item">
                    <!-- Check if the user is logged in -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="../accounts.php" class="nav__link">My Account</a>
                    <?php else: ?>
                        <a href="../login-register.php" class="nav__link">My Account</a>
                    <?php endif; ?>
                </li>
                    <li class="nav__item"><a href="../contact.php" class="nav__link">Contact</a></li>
                   
                </ul>
                <div class="header__search">
                    <input type="text" placeholder="Search for items..." class="form__input"/>
                    <button class="search__btn">
                        <img src="assets/img/search.png" alt="">
                    </button>
                </div>
            </div>
            <div class="header__user-action">
               
            <a href="../cart/cart.php" class="header__action-btn">
    <img src="../assets/img/icon-cart.svg" alt="Cart Icon">
    <span class="count"><?= $cart_count ?></span>  <!-- Display the total count here -->
</a>
            </div>
        </nav>
    </header>

    <!--=============== MAIN ===============-->
    <main class="main">
        <!--=============== BREADCRUMB ===============-->
        <section class="breadcrumb">
            <ul class="breadcrumb__list container">
                <li><a href="index.html" class="breadcrumb__link">Home</a></li>
                <li><span class="breadcrumb__link">></span></li>
                <li><span class="breadcrumb__link">Profile</span></li>
                <li><span class="breadcrumb__link">></span></li>
                <li><span class="breadcrumb__link">Wishlist</span></li>
            </ul>
        </section>

        <!--=============== WISHLIST ===============-->
        <section class="wishlist section--lg container">
            <div class="table__container" style="margin-top: 2rem; margin-bottom: 5.5rem;">
                <table class="table">
                    <tr>
                        <th>Images</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Stock Status</th>
                        <th>Action</th>
                        <th>Remove</th>
                    </tr>
                    
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <img src="../<?= $row['image'] ?>" alt="" class="table__img"> <!-- Adjust image path -->
                                </td>
                                <td>
                                    <h3 class="table__title"><?= $row['name'] ?></h3>
                                    <p class="table__description"><?= $row['description'] ?></p>
                                </td>
                                <td><span class="table__price">$<?= $row['selling_price'] ?></span></td>
                                <td><span class="table__Stock"><?= $row['qty'] ?></span></td>
                                <td>
                  <form action="../cart/cart_actions.php" method="POST" >
                <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
              <input type="hidden" name="product_name" value="<?= htmlspecialchars($row['name']) ?>">
               <input type="hidden" name="product_price" value="<?= $row['selling_price'] ?>">
               <input type="hidden" name="product_image" value="<?= $row['image'] ?>">
                 <input type="hidden" name="quantity" value="<?= $row['qty'] ?>">
                <button type="submit" class="btn btn btn--sm">Add To Cart </button>
                                    </form>
                                    </a></td>
                                <td>
    <form action="remove_from_wishlist.php" method="POST" style="display: inline;">
        <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>"> <!-- Use $row here -->
        <button type="submit" class="btn-remove" style="background: none; border: none; cursor: pointer;">
            <i class="fi fi-rs-trash table__trash"></i>
        </button>
    </form>
                        </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <td colspan="6" style="text-align: center;">No products in your wishlist.</td>
                    <?php else: ?>
                        <td colspan="6" style="text-align: center;">You Should login first</td>
                    <?php endif; ?>
                            
                        </tr>
                    <?php endif; ?>
                </table>
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
                    <input type="text" placeholder="Enter your email" class="newsletter__input"/>
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

    <!--=============== MAIN JS ===============-->
    <script src="../assets/js/main.js"></script>
</body>
</html>
