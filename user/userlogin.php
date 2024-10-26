<?php
session_start();
if (isset($_SESSION["user"])) {
   header("Location: user.php");
}

if (isset($_POST["login"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $errors = array();
    require_once "config.php";
    $result = mysqli_query($conn, "SELECT * FROM users WHERE E_MAIL = '$email'");
    $rowCount = mysqli_num_rows($result);
    
    if ($rowCount == 0) {
        array_push($errors,"Email does not exist!");
    } else {
        $user = mysqli_fetch_assoc($result);
        if ($user['PASSWORD'] !== $password) {
            array_push($errors, "Incorrect password");
        }
    }        
    if (count($errors) > 0) {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    } else {
        session_start();
        $_SESSION["user"] = "yes"; 
        $_SESSION["user_email"] = $email;
        header("Location: user.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">

</head>
<body class="body">
 
<div class="row my-5 mt-5 " >
  <div class="col-7 container-fluid ">
  <h1 class=" p-5 text-center head1">Book Bar</h1>
  </div>
  <div class="col-4 my-0 mr-5">
      <div class="w-100 container-fluid  border1  p-5 my-5 shadow-lg boxback mr-5">
        <h3 class="w-100 text-center">Login</h3>
       
        <form action="userlogin.php" method="post">
        <label  for="E-mail" class="form-label w-75 mx-3">E-mail</label>
        <input class="w-100 mx-3 mb-2 rounded" type="email"  name="email" placeholder="Enter your e-mail">
        <label  for="Password" class="form-label w-75 mx-3">Password</label>
        <input class="w-100 mx-3 mb-2 rounded" type="password"  name="password" placeholder="Enter your password">
        <button type="login" class="btn w-100 button mx-3 buttonback" name="login">Login</button>
        <p class="w-100 text-center">Don't have acount? <a href="userregistration.php">sign up</a></p>
        </form>
    </div>
  
    
  </div>
  

</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" 
integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" 
crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" 
integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" 
crossorigin="anonymous"></script>
</body>
</html>