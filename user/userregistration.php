<?php
session_start();
if (isset($_SESSION["user"])) {
   header("Location: user.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">

</head>
<body class="body">
<div class="row my-5 mt-5 " >
  <div class="col-7 container-fluid ">
    <h1 class=" p-5 text-center head1">Book Bar</h1>
  </div>
<div class="container-fluid col-4 border1  p-5 shadow-lg boxback">
    <div class="w-100">
     <h3 class="w-100 text-center">Sign Up</h3>
     <?php
        if (isset($_POST["submit"])) {
           $name = $_POST["name"];
           $phonenumber = $_POST["phonenumber"];
           $email = $_POST["email"];
           $address = $_POST["address"];
           $password = $_POST["password"];           
           $errors = array();
           
           if (empty($name) OR empty($phonenumber) OR empty($email) OR empty($address) OR empty($password)) {
            array_push($errors,"All fields are required");
           }
           if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Email is not valid");
           }
           if (strlen($password)<8) {
            array_push($errors,"Password must be at least 8 charactes long");
           }
           if (strlen($phonenumber)!=11) {
            array_push($errors,"invalid phone number");
           }
           require_once "config.php";
           $result = mysqli_query($conn, "SELECT * FROM users WHERE E_MAIL = '$email'");
           $rowCount = mysqli_num_rows($result);
           if ($rowCount>0) {
            array_push($errors,"Email already exists!");
           }
           if (count($errors)>0) {
            foreach ($errors as  $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
           }else{
            
            $sql = "INSERT INTO users (NAME,PHONE_NUMBER,E_MAIL,ADDRESS,PASSWORD) VALUES ( ?, ?, ?, ?, ? )";
            $stmt = mysqli_stmt_init($conn);
            $prepareStmt = mysqli_stmt_prepare($stmt,$sql);
            if ($prepareStmt){
                mysqli_stmt_bind_param($stmt,"sssss",$name,$phonenumber, $email, $address, $password);
                mysqli_stmt_execute($stmt);
                echo "<div class='alert alert-success'>You are registered successfully.</div>";
            }else{
                die("Something went wrong");
            }
           }
        }
        ?>
     <form action="userregistration.php" method="post">
      <label class="form-label w-75 mx-3">Name</label>
      <input class="w-100 mx-3 mb-2 rounded" type="text"  name="name" placeholder="Enter your full name">
      <label class="form-label w-75 mx-3">Phone Number</label>
      <input class="w-100 mx-3 mb-2 rounded" type="text"  name="phonenumber" placeholder="Enter your Phone Number">
      <label class="form-label w-75 mx-3">E-mail</label>
      <input class="w-100 mx-3 mb-2 rounded" type="email"  name="email" placeholder="Enter your e-mail">
      <label class="form-label w-75 mx-3">Address</label>
      <input class="w-100 mx-3 mb-2 rounded" type="text"  name="address" placeholder="Enter your address">
      <label class="form-label w-75 mx-3">Password</label>
      <input class="w-100 mx-3 mb-2 rounded" type="password"  name="password" placeholder="Enter your password">
      <button type="submit"class="btn w-100 button mx-3 buttonback " name="submit">Sign Up</button>
      <p class="w-100 text-center">Don't have acount? <a href="userlogin.php">Login</a></p>
      </form> 
  </div>
 
</div>

</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" 
integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" 
crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" 
integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
</body>
</html>