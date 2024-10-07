<?php 

  session_start();

  include("../dbcon.php");
  include(__DIR__ . "/../functions/myfunctions.php");
if(isset($_POST['register_btn'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']) ;
    $phone = mysqli_real_escape_string($con, $_POST['phone']) ; 
    $email =  mysqli_real_escape_string($con, $_POST['email']) ;
    $password = mysqli_real_escape_string($con, $_POST['password']) ;
    $cpassword = mysqli_real_escape_string($con, string: $_POST['cpassword']) ;

    $check_email_query = "SELECT email FROM admin WHERE email='$email'";
    $check_email_query_run = mysqli_query($con, $check_email_query);

    if(mysqli_num_rows($check_email_query_run) > 0) {

        $_SESSION['message'] = "email already registreated";
        header('Location: ../../register.php');

    }

    else {
        if($password == $cpassword) {

    $inset_query = "INSERT INTO admin(name,email,phone,password) VALUES ('$name','$email', '$phone','$password')";
     $inset_result = mysqli_query($con, $inset_query);

     if($inset_result){
        $_SESSION['message'] = "Registerd succefully";
        header('Location: ../../login.php');
     }
      else {

        $_SESSION['message'] = "Something went wrong";
        header('Location: ../../register.php');

      }
    } 
    else {

     $_SESSION['message'] = "password does not match";
     header('Location: ../../register.php');

    }
    }

    

}

else if(isset($_POST['login_btn']))
{
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    $login_query = "SELECT * FROM admin WHERE email= '$email' AND password='$password'";
    $login_query_run = mysqli_query($con, $login_query);

    if(mysqli_num_rows($login_query_run)){
        $_SESSION['auth'] = true;

        $userdata = mysqli_fetch_array($login_query_run);
        $username = $userdata['name'];
        $useremail = $userdata['email'];
       

        $_SESSION['auth_user'] = [
            'username'=> $username,
            'email'=> $useremail
        ];
        

      

        redirect( "../../../adminDashboard/category.php", "logged in succesfuly");
        

        

    }
    else {
        redirect("../../login.php", "wrong email or pasword");
      
    }
}
?>