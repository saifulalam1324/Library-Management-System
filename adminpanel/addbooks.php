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
    <title>Adding books</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">

</head>
<body class="body">
<div class="row my-5 mt-5 " >
  <div class="col-4">

  </div>
<div class="container-fluid col-4 border1  p-5 shadow-lg boxback">
    <div class="w-100">
     <h3 class="w-100 text-center">Add Book</h3>
     <?php
        if (isset($_POST["submit"])) {
           $bookname = $_POST["bookname"];
           $gener = $_POST["gener"];
           $quantity = $_POST["quantity"];
           $author = $_POST["author"];
           if (empty($bookname) || empty($gener) || empty($quantity) || empty($author)) {
           echo "<div class='alert alert-danger'>All fields are required.</div>";
           }else {
           require_once "config.php";

           $sql = "INSERT INTO books(BOOK_NAME, GENER, QUANTITY, AUTHOR) 
                   VALUES (?, ?, ?, ?)";
           
           $stmt = mysqli_stmt_init($conn);

           if (mysqli_stmt_prepare($stmt, $sql)) {
               
               mysqli_stmt_bind_param($stmt, "ssis", $bookname, $gener, $quantity, $author);
               
               if (mysqli_stmt_execute($stmt)) {
                   echo "<div class='alert alert-success'>Book Added Successfully!</div>";
               } else {
                   echo "<div class='alert alert-danger'>Error: Could not execute.</div>";
               }
           } else {
               echo "<div class='alert alert-danger'>Error: Could not prepare statement.</div>";
           }

           mysqli_stmt_close($stmt);
           mysqli_close($conn);
           }
        }
        ?>
     <form action="addbooks.php" method="post">
      <label class="form-label w-75 mx-3">Book Name</label>
      <input class="w-100 mx-3 mb-2 rounded" type="text"  name="bookname" placeholder="Book Name">
      <label class="form-label w-75 mx-3">Gener</label>
      <input class="w-100 mx-3 mb-2 rounded" type="text"  name="gener" placeholder="Gener">
      <label class="form-label w-75 mx-3">Quantity</label>
      <input class="w-100 mx-3 mb-2 rounded" type="number"  name="quantity" placeholder="Quantity">
      <label class="form-label w-75 mx-3">Author</label>
      <input class="w-100 mx-3 mb-2 rounded" type="text"  name="author" placeholder="Author">
      <button type="submit"class="btn w-100 button mx-3 buttonback " name="submit">Submit</button>
      </form> 
  </div>
 
</div>
<div class="col-4"></div>
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" 
integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" 
crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" 
integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
</body>
</html>