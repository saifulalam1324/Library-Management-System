<?php
session_start();
require_once "config.php";
if (!isset($_SESSION["use"]) || $_SESSION["role"] != "Admin") {
    header("Location: adminlogin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add role</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">

</head>
<body class="body">
<div class="row my-5 mt-5 " >
  <div class="col-4">

  </div>
<div class="container-fluid col-4 border1  p-5 shadow-lg boxback">
    <div class="w-100">
     <h3 class="w-100 text-center">Add Employee</h3>
     <?php
        if (isset($_POST["submit"])) {
           $name = $_POST["name"];
           $email = $_POST["email"];
           $password = $_POST["password"];
           $role = $_POST["role"];
           if (empty($name) || empty($email) || empty($password) || empty($role)) {
           echo "<div class='alert alert-danger'>All fields are required.</div>";
           }else {
           require_once "config.php";

           $sql = "INSERT INTO admin(admin_name,e_mail,password,role) 
                   VALUES (?, ?, ?, ?)";
           
           $stmt = mysqli_stmt_init($conn);

           if (mysqli_stmt_prepare($stmt, $sql)) {
               
               mysqli_stmt_bind_param($stmt, "ssss",$name, $email, $password, $role);
               
               if (mysqli_stmt_execute($stmt)) {
                   echo "<div class='alert alert-success'>Employee Added Successfully!</div>";
               } else {
                   echo "<div class='alert alert-danger'>Error: Could not execute.</div>";
               }
           } else {
               echo "<div class='alert alert-danger'>Error: Could not prepare statement.</div>";
           }
           
           }
        }
        ?>
     <form action="add&updaterole.php" method="post">
      <label class="form-label w-75 mx-3">Name</label>
      <input class="w-100 mx-3 mb-2 rounded" type="text"  name="name" placeholder="Name">
      <label class="form-label w-75 mx-3">Email</label>
      <input class="w-100 mx-3 mb-2 rounded" type="text"  name="email" placeholder="Email">
      <label class="form-label w-75 mx-3">Password</label>
      <input class="w-100 mx-3 mb-2 rounded" type="password"  name="password" placeholder="Password">
      <label class="form-label w-75 mx-3">Role</label>
      <input class="w-100 mx-3 mb-2 rounded" type="text"  name="role" placeholder="Role">
      <button type="submit"class="btn w-100 button mx-3 buttonback " name="submit">Submit</button>
      </form> 
  </div>
</div>
<div class="col-4"></div>
</div>

<div class="container-fluid col-4 border1 p-5 shadow-lg boxback">
    <div class="w-100">
        <h3 class="w-100 text-center">Update Role</h3>
        <?php
        if (isset($_POST["submit1"])) {
            $ID = $_POST["ID"];
            $role = $_POST["role"];
            if (empty($ID) || empty($role)) {
                echo "<div class='alert alert-danger'>All fields are required.</div>";
            } else {
                require_once "config.php";
                
                $sql = "UPDATE admin SET role='$role' WHERE admin_id='$ID'";
                if (mysqli_query($conn, $sql)) {
                    header("Location: add&updaterole.php");
                    exit(); 
                } else {
                    echo "Error updating record: " . mysqli_error($conn);
                }
            }
        }
        ?>
        <form action="add&updaterole.php" method="post">
            <label class="form-label w-75 mx-3">Admin Id</label>
            <input class="w-100 mx-3 mb-2 rounded" type="text" name="ID" placeholder="Admin Id">
            <label class="form-label w-75 mx-3">Role</label>
            <input class="w-100 mx-3 mb-2 rounded" type="text" name="role" placeholder="Role">
            <button type="submit" class="btn w-100 button mx-3 buttonback" name="submit1">Submit</button>
        </form>
    </div>
</div>
<div class="container mt-1 mb-5">
    <a href="requestlist.php" class="btn buttonback">Back</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" 
integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" 
crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" 
integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
</body>
</html>