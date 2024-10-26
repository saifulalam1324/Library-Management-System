<?php
session_start();
require_once "config.php";
if (!isset($_SESSION["use"]) || $_SESSION["role"] != "Admin") {
    header("Location: adminlogin.php");
    exit();
}
?>
<?php
function checkOverdueBooks($conn) {
    $sql = "UPDATE borrow_record 
            SET OVERDUE = 1 
            WHERE RECEIVED = 1 
            AND RETURNED = 0 
            AND TIMESTAMPDIFF(DAY, RECEIVED_DATE, NOW()) > 1";
    mysqli_query($conn, $sql);
    $sql_reset = "UPDATE borrow_record 
                  SET OVERDUE = 0 
                  WHERE RETURNED = 1";
    mysqli_query($conn, $sql_reset);
}
checkOverdueBooks($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>
<body class="body">
    <div class=" p-3 mb-5 nav1 fixed-top">
        <nav class="navbar navbar-expand-lg navbar-light">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="col-3">
            <a class="nav-link navbar-brand" href="admin.php"><h3 class="nav2">Admin Panel</h3><span class="sr-only">(current)</span></a>
            </div>
            <div class="col-6">
                <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                    <ul class="navbar-nav">
                      <li class="nav-item active">
                      <a class="nav-link navbar-brand" href="books.php">Books</a>
                      </li>
                      <li class="nav-item active">
                        <a class="nav-link navbar-brand" href="users.php">Users</a>
                      </li>
                      <li class="nav-item active">
                        <a class="nav-link navbar-brand" href="requestlist.php">Records</a>
                      </li>
                      <li class="nav-item active">
                        <a class="nav-link navbar-brand" href="adminlogout.php">Logout</a>
                      </li>
                    </ul>
                  </div>
            </div>
            <div class="col-3">

            </div>
          </nav>
          </div>
          <div class="container mt-3 p-5"></div>
          <div class="container mt-5 pt-5">
            <h1 class=" p-5 text-center head2">Admin Panel</h1>
          </div>
    
    
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" 
    integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
    crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" 
    crossorigin="anonymous"></script>
</body>
</html>
