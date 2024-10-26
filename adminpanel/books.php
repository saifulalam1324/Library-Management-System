<?php
session_start();
require_once "config.php";
if (!isset($_SESSION["use"]) || $_SESSION["role"] != "Admin") {
    header("Location: adminlogin.php");
    exit();
}
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $id = mysqli_real_escape_string($conn, $id);
    $sql = "DELETE FROM books WHERE BOOK_ID = $id";
    if (mysqli_query($conn, $sql)) {
        header("Location: books.php");
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
  }
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
    <div class=" p-3 mb-5 nav1  fixed-top">
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
          <div class="container mt-5 pt-5"></div>
          <div class="container mt-5 pt-3">

            <table class="table table1 table-bordered table-striped">
            <td><div class="row"><div class="col-2"><h4>Add book</h4></div><div class="col-10"><a href="addbooks.php"><img class="addpic" src="pictures/+.png" alt="" srcset=""></a></div></div></td>
          </table>
            
            </div>

    <div class="container mb-3">
        <?php
        if (isset($_SESSION['message'])) {
            echo '<div class="alert alert-info">' . htmlspecialchars($_SESSION['message'], ENT_QUOTES, 'UTF-8') . '</div>';
            unset($_SESSION['message']);
        }

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "SELECT * FROM books ORDER BY BOOK_ID";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                echo '<table class="table table1 table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr class="text-center">
                               <th>Book ID</th>
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
                    
                    echo '<tr>
                            <td>' . $row["BOOK_ID"]. '</td>
                            <td>' . $row["BOOK_NAME"]. '</td>
                            <td>' . $row["GENER"]. '</td>
                            <td>' . $row["AUTHOR"]. '</td>
                            <td>' . $status . '</td>
                            <td class="text-center">
                                <a href="editbook.php?update=' . $row['BOOK_ID'] . '" class="btn w-60 buttonback">Edit</a>
                                 <a href="books.php?delete=' . $row['BOOK_ID'] . '" class="btn w-60 buttonback2" onclick="return confirm(\'Are you sure you want to delete this book?\');">Delete</a>
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
