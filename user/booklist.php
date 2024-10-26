<?php
session_start();
require_once "config.php";

if (!isset($_SESSION["user"])) {
    header("Location: userlogin.php");
    exit();
}

if (isset($_POST["borrow"])) {
    $book_id = $_POST["book_id"];
    $book_name = $_POST["book_name"];
    $user_email = $_SESSION["user_email"];  
    $user_result = mysqli_query($conn, "SELECT USER_ID, NAME FROM users WHERE E_MAIL = '$user_email'");
    $user_data = mysqli_fetch_assoc($user_result);
    $user_id = $user_data["USER_ID"];
    $user_name = $user_data["NAME"];
    $sql = "INSERT INTO borrow_record (user_id, book_id) VALUES ('$user_id', '$book_id')"; 
    if (mysqli_query($conn, $sql)) {
      header("Location: booklist.php");
      exit();
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>books</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>
<body class="body">
    <div class="p-3 mb-5 nav1 fixed-top">
        <nav class="navbar navbar-expand-lg navbar-light">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" 
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="col-3">
            <h3 class="nav2">Book Bar</h3>
            </div>
            <div class="col-6">
                <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                    <ul class="navbar-nav">
                      <li class="nav-item active">
                        <a class="nav-link navbar-brand" href="user.php">Home<span class="sr-only">(current)</span></a>
                      </li>
                      <li class="nav-item active">
                        <a class="nav-link navbar-brand" href="booklist.php">Books</a>
                      </li>
                      <li class="nav-item active">
                        <a class="nav-link navbar-brand" href="userinfo.php">
                            <img class="adminimg" src="pictures/user logo.png" alt="User Logo">
                        </a>
                      </li>
                      <li class="nav-item active">
                        <a class="nav-link navbar-brand" href="userlogout.php">Logout</a>
                      </li>
                    </ul>
                  </div>
            </div>
            <div class="col-3">
             
            </div>
          </nav>
    </div>
    <div class="container mt-5 pt-5">

</div>
    <div class="container mt-5 pt-3">
    <?php
       require_once "config.php";

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "SELECT * FROM books ORDER by BOOK_NAME";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                echo '<table class="table table1 table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr class="text-center">
                                <th>Book Name</th>
                                <th>Genre</th>
                                <th>Author</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>';
                while ($row = mysqli_fetch_assoc($result)) {
                  $status = (intval($row["QUANTITY"]) > 0) ? 'Available' : 'Unavailable';
                  $buttonDisabled = (intval($row["QUANTITY"]) > 0) ? '' : 'disabled';

                  echo '<tr>
                          <td>' . $row["BOOK_NAME"] . '</td>
                          <td>' . $row["GENER"] . '</td>
                          <td>' . $row["AUTHOR"] . '</td>
                          <td>' . $status . '</td>
                          <td class="text-center">
                              <form action="booklist.php" method="post">
                                  <input type="hidden" name="book_id" value="' . $row['BOOK_ID'] . '">

                                  <button type="submit" class="btn w-60 buttonback" name="borrow" ' . $buttonDisabled . 'onclick="return confirm(\'Request sent to admin\');" >Borrow</button>
                              </form>
                          </td>
                      </tr>';
              }

                echo '</tbody></table>';
            } else {
                echo '<div class="alert alert-info">No records found.</div>';
            }
        } else {
            echo '<div class="alert alert-danger">Error: ' . mysqli_error($conn) . '</div>';
        }

        mysqli_close($conn);
    ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" 
            integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" 
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" 
            crossorigin="anonymous"></script>
</body>
</html>
