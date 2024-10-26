<?php
session_start();
require_once "config.php";
if (!isset($_SESSION["use"]) || $_SESSION["role"] != "Librarian") {
    header("Location: adminlogin.php");
    exit();
}
?>
<?php
require_once "config.php";
$sql = "SELECT COUNT(USER_ID) AS total FROM Users";
$result = mysqli_query($conn, $sql);
$totalCount = 0; 
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $totalCount = $row['total'];
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
<div class=" p-3 mb-5 nav1 fixed-top">
        <nav class="navbar navbar-expand-lg navbar-light">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="col-3">
            <a class="nav-link navbar-brand" href="admin.php"><h3 class="nav2">Librarian</h3><span class="sr-only">(current)</span></a>
            </div>
            <div class="col-6">
                <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                    <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link navbar-brand" href="lusers.php">Users</a>
                      </li>
                      <li class="nav-item active">
                        <a class="nav-link navbar-brand" href="lrecords.php">Records</a>
                      </li>
                      <li class="nav-item active">
                        <a class="nav-link navbar-brand" href="librarianlogout.php">Logout</a>
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
            <td><div class="row"><div class="col-4"><h4>Users</h4></div><div class="col-6"></div><div class="col-2 text-end"><h4>Total: <?php echo $totalCount;?></h4></div></div></td>
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
        $sql = "SELECT * FROM users ORDER BY NAME";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                echo '<table class="table table1 table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr class="text-center">
                               <th>User ID</th>
                                <th>Name</th>
                                <th>Phone Number</th>
                                <th>Address</th>
                                <th>Borrowed</th>
                                <th>Overdues</th>
                            </tr>
                        </thead>
                        <tbody>';
                while ($row = mysqli_fetch_assoc($result)) {
                    $userId = $row['USER_ID'];
                    $borrowedSql = "SELECT COUNT(SERIAL_NO) as total_borrowed FROM borrow_record as bw ,books as b WHERE USER_ID = '$userId' and bw.BOOK_ID = b.BOOK_ID and bw.ACCEPT = 1 AND RECEIVED = 1";
                    $borrowedResult = mysqli_query($conn, $borrowedSql);
                    $totalBorrowed = 0;
                    if ($borrowedResult) {
                    $borrowedRow = mysqli_fetch_assoc($borrowedResult);
                    $totalBorrowed = $borrowedRow['total_borrowed'];
                   }   
                   $overdueSql = "SELECT COUNT(SERIAL_NO) as total_overdue FROM borrow_record as bw ,books as b WHERE USER_ID = '$userId' and bw.BOOK_ID = b.BOOK_ID and OVERDUE = 1";
                   $overdueResult = mysqli_query($conn, $overdueSql);
                   $totalOverdue = 0;
                   if ($overdueResult) {
                   $overdueRow = mysqli_fetch_assoc($overdueResult);
                   $totalOverdue = $overdueRow['total_overdue'];
                  }                    
                    echo '<tr>
                            <td>' . $row["USER_ID"]. '</td>
                            <td>' . $row["NAME"]. '</td>
                            <td>' . $row["PHONE_NUMBER"]. '</td>
                            <td>' . $row["ADDRESS"]. '</td>
                            <td>' .$totalBorrowed. '</td>
                            <td>' . $totalOverdue. '</td>
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