<?php

include("../middleware/adminMiddlware.php");
include("../adminto/main/config/dbcon.php");
session_start();
$errorMessage = "";

if (isset($_POST['add-message'])) {

    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['number'];
    $message = $_POST['message'];

    // Check if the email or phone number already exists
    $check_query = "SELECT * FROM inbox WHERE email='$email' OR phone_number='$phoneNumber' LIMIT 1";
    $check_result = mysqli_query($con, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Email or phone number already exists
        $errorMessage = "Email or phone number already exists. Please use a different one.";
    } else {
        // Proceed to insert the new message
        $inbox_query = "INSERT INTO inbox(first_name, last_name, email, phone_number, message) 
                        VALUES('$firstName','$lastName','$email','$phoneNumber','$message')";

        $inbox = mysqli_query($con, $inbox_query);
        if ($inbox) {
          $errorMessage = "Message sent succefully";
        }
    }
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
                    <a href="contact.php" class="nav__link active-link">Contact</a>
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
         <li><span class="breadcrumb__link">Contact</span></li>
        </ul>
      </section>

      <!--=============== contact ===============-->
        <section class="contact">
          <div class="bkcolor">
            <div class="contactUs">
            <div class="title">
              <h2>Get in Touch</h2>
              </div>
              <div class="box">
                <div class="contact form">
                     <form method="POST" >
                     <?php if (!empty($errorMessage)) { ?>
                <div class="row100">
                    <div class="error-message" style="color: red; margin-bottom: 50px;">
                        <?php echo $errorMessage; ?>
                    </div>
                </div>
            <?php } ?>
                  <h3>Send a Message</h3>
                  
                  <div class="formBox">
                  <div class="row50">
                  <div class="inputBox">
                  <span>First Name</span>  
                  <input type="text" placeholder="Ex:John" name="firstName" required >
                  </div>
                  <div class="inputBox">
                    <span>Last Name</span>  
                    <input type="text" placeholder="Ex:Doe" name="lastName" required >
                    </div>
                  </div>
                  <div class="row50">
                    <div class="inputBox">
                    <span>Email</span>  
                    <input type="text" placeholder="Ex:JohnDoe@email.com" name="email" required >
                    </div>
                    <div class="inputBox">
                      <span>Mobile</span>  
                      <input type="text" placeholder="Ex:0551239279" name="number" required >
                      </div>
                    </div>
                    <div class="row100">
                      <div class="inputBox">
                      <span>Message</span>  
                      <textarea placeholder="write  your message here" name="message" required ></textarea>
                     
                      </div>
                    </div>
                    
                    <div class="row100">
                      <div class="inputBox"> 
                      <input type="submit" value="Send" name="add-message" id="send" >
                      
                      </div>
                    </div>
                </div>
              </form>
                </div>
                <div class="contact info">
                <h3>Contact Info</h3>
                <div class="infoBox">
                  <div>
                    <span><ion-icon name="location"></ion-icon></span>
                    <p>145 logement, Draria, Alger <br>ALGERIA</p>
                  </div>
                  <div>
                    <span><ion-icon name="mail"></ion-icon></span>
                    <a href="mailto:Mebtouche.manel@gmail.com">Mebtouche.manel@gmail.com</a>
                  </div>
                  <div>
                    <span><ion-icon name="call"></ion-icon></span>
                    <a href="tel:+0549939880">+0549 93 98 80</a>
                  </div>
                  <!--social media liks-->
                  <ul class="sci">
                    <li><a href="#" class="icon"><ion-icon name="logo-facebook"></ion-icon></a></li>
                    <li><a href="https://www.instagram.com/linenshome.lingedemaison/" class="icon"><ion-icon name="logo-instagram"></ion-icon></a></li>
                  </ul>
                </div>
                </div>
                <div class="contact map">
                  <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2873.5954170631553!2d3.0046672584167324!3d36.71207339019682!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x128fafc460b9b6e9%3A0xf52e1f36e0b23132!2sLinens%20home%20linge%20de%20maison!5e1!3m2!1sar!2sdz!4v1725130593217!5m2!1sar!2sdz" 
                  style="border:0;" allowfullscreen="" loading="lazy" 
                  referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
              </div>
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

  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    

  </body>
</html>
