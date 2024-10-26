<?php
session_start();
require_once "config.php";
if (!isset($_SESSION["use"]) || $_SESSION["role"] != "Admin") {
    header("Location: adminlogin.php");
    exit();
}
?>
<?php
require_once "config.php";
$sql = "SELECT COUNT(SERIAL_NO) AS total FROM borrow_record WHERE ACCEPT = 0";
$result = mysqli_query($conn, $sql);
$totalCount = 0; 
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $totalCount = $row['total'];
}
?>
<?php
require_once "config.php";
$sql = "SELECT COUNT(SERIAL_NO) AS total FROM borrow_record WHERE OVERDUE = 1";
$result = mysqli_query($conn, $sql);
$totalCount1 = 0; 
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $totalCount1= $row['total'];
}
?>
<?php
require_once "config.php";
$sql = "SELECT COUNT(SERIAL_NO) AS total FROM borrow_record WHERE ACCEPT = 1 AND RECEIVED = 1";
$result = mysqli_query($conn, $sql);
$totalCount2 = 0; 
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $totalCount2= $row['total'];
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
    <div class="p-3 mb-5 nav1 fixed-top">
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
            <td><div class="row"><div class="col-8"><h4>Pending Requests <a href="accept&deletereq.php" class="btn buttonback4">See more..</a></h4></div><div class="col-2"></div><div class="col-2 text-end"><h4>Total: <?php echo $totalCount?></h4></div></div></td>
        </table>
    </div>
    <div class="container mb-3" id="div1">
    

    </div>
    <div class="container">
        <table class="table table1 table-bordered table-striped">
            <td><div class="row"><div class="col-8"><h4>Overdues<a onclick="toggleDiv('div2')" class="btn buttonback4">See more..</a></h4></div><div class="col-2"></div><div class="col-2 text-end"><h4>Total: <?php echo $totalCount1?></h4></div></div></td>
        </table>
    </div>
    <div class="container mb-3" id="div2">
    <?php
       require_once "config.php";

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM borrow_record as br,books as b,users as u  WHERE br.OVERDUE = 1 and b.BOOK_ID = br.BOOK_ID and u.USER_ID = br.USER_ID ORDER by NAME";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                echo '<table class="table table1 table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr class="text-center">
                                <th>User ID</th>
                                <th>User Name</th>
                                <th>Book ID</th>
                                <th>Book Name</th>
                                <th>Received Date</th>
                            </tr>
                        </thead>
                        <tbody>';            
                        while ($row = mysqli_fetch_assoc($result)) {
                          echo '<tr>
                                  <td>' . $row['USER_ID'] . '</td>
                                  <td>' . $row['NAME'] . '</td>
                                  <td>' . $row['BOOK_ID']. '</td>
                                  <td>' . $row['BOOK_NAME'] . '</td>
                                  <td>' . $row['RECEIVED_DATE'] . '</td>
                              </tr>';
                        }
                echo '</tbody></table>';
            } else {
                echo '<div class="alert alert-info">No records found.</div>';
            }
        } else {
            echo '<div class="alert alert-danger">Error: ' . mysqli_error($conn) . '</div>';
        }
    ?>
    </div>
    <div class="container">
        <table class="table table1 table-bordered table-striped">
            <td><div class="row"><div class="col-8"><h4>Borrowed Books<a onclick="toggleDiv('div3')" class="btn buttonback4">See more..</a></h4></div><div class="col-2"></div><div class="col-2 text-end"><h4>Total: <?php echo $totalCount2?></h4></div></div></td>
        </table>
    </div>
    <div class="container mb-3" id="div3">
    <?php
       require_once "config.php";

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM borrow_record as br, users as u, books as b WHERE br.ACCEPT = 1 AND br.RECEIVED = 1 AND u.USER_ID = br.USER_ID AND b.BOOK_ID = br.BOOK_ID ORDER by NAME";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                echo '<table class="table table1 table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr class="text-center">
                                <th>User ID</th>
                                <th>User Name</th>
                                <th>Book ID</th>
                                <th>Book Name</th>
                            </tr>
                        </thead>
                        <tbody>';            
                        while ($row = mysqli_fetch_assoc($result)) {
                          echo '<tr>
                                  <td>' . $row['USER_ID'] . '</td>
                                  <td>' . $row['NAME'] . '</td>
                                  <td>' . $row['BOOK_ID']. '</td>
                                  <td>' . $row['BOOK_NAME'] . '</td>';
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
    <div class="container mt-3">
        <a href="add&updaterole.php" class="btn buttonback">Add Employee</a>
    </div>
    <script>
        window.onload = function() {
        document.getElementById("div1").style.display = "none";
        document.getElementById("div2").style.display = "none";
        document.getElementById("div3").style.display = "none";
    };
    function toggleDiv(divId) {
        var element = document.getElementById(divId);
        if (element.style.display === "none") {
            element.style.display = "block";
        } else {
            element.style.display = "none";
        }
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" 
    integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
    crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" 
    crossorigin="anonymous"></script>
</body>
</html>
