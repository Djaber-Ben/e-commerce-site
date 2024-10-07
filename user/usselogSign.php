<?php
session_start(); // Start the session
include("../middleware/adminMiddlware.php");

// Initialize message variable
$message = '';

// Registration Process
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sign'])) {
    // Retrieve form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $cpasswrd = $_POST['cpasswrd'];

    // Check if passwords match
    if ($password !== $cpasswrd) {
        $_SESSION['register_message'] = "Passwords do not match."; // Store registration message
        header("Location: login-register.php"); // Redirect to the account page
        exit;
    } else {
        // Check if the email already exists
        $checkEmailQuery = "SELECT * FROM users WHERE email = '$email'";
        $emailResult = mysqli_query($con, $checkEmailQuery);

        if (mysqli_num_rows($emailResult) > 0) {
            $_SESSION['register_message'] = "Email already exists. Please use a different email."; // Store registration message
            header("Location: login-register.php"); // Redirect to the account page
            exit;
        } else {
            // Hash the password for security
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert the new user into the database
            $insertQuery = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashedPassword')";
            $insertResult = mysqli_query($con, $insertQuery);

            if ($insertResult) {
                // Retrieve the new user's ID
                $newUserId = mysqli_insert_id($con);

                // Set session variables to log the user in immediately after registration
                $_SESSION['user_id'] = $newUserId; // Set the user ID
                $_SESSION['username'] = $username; // Set the username

                $_SESSION['register_message'] = "Registration successful!"; // Store registration message
                header("Location: accounts.php"); // Redirect to accounts.php after successful registration
                exit;
            } else {
                $_SESSION['register_message'] = "Error: " . mysqli_error($con); // Store registration message
                header("Location: login-register.php"); // Redirect to the account page
                exit;
            }
        }
    }
}


// Login Process
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['log'])) {
    // Retrieve form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the user exists
    $checkUserQuery = "SELECT * FROM users WHERE email = '$email'";
    $userResult = mysqli_query($con, $checkUserQuery);

    if (mysqli_num_rows($userResult) > 0) {
        $user = mysqli_fetch_assoc($userResult);

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Set session variables for logged-in user
            $_SESSION['user_id'] = $user['id']; // Assuming you have an 'id' field in your users table
            $_SESSION['username'] = $user['username']; // Store the username or any other user information you want
            $_SESSION['login_message'] = "Login successful!"; // Store login message
            header("Location: accounts.php"); // Redirect to the account page
            exit;
        } else {
            $_SESSION['login_message'] = "Incorrect password. Please try again."; // Store login message
            header("Location: login-register.php"); // Redirect to the account page
            exit;
        }
    } else {
        $_SESSION['login_message'] = "No account found with that email."; // Store login message
        header("Location: login-register.php"); // Redirect to the account page
        exit;
    }
}


if (isset($_POST["cUsername"])) {
    $username = trim($_POST["username"]);
    $user_id = $_SESSION['user_id'];

    if (empty($username)) {
        $_SESSION['message'] = "Username cannot be empty"; // Set the message in session
        header("Location: accounts.php"); // Redirect to the accounts page
        exit;
    }

    // Assuming $con is your database connection
    $stmt = $con->prepare("UPDATE users SET username=? WHERE id=?"); // Use your actual table name
    $stmt->bind_param("si", $username, $user_id);

    if ($stmt->execute()) {
        $_SESSION['username'] = $username; // Update the session variable with the new username
        redirect("accounts.php", "name changes succefuly"); // Redirect to the accounts page
    } else {
        redirect("accounts.php", "name changes succefuly"); // Redirect to the accounts page
    }

    $stmt->close();
}






