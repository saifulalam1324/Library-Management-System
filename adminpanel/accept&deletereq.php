<?php
session_start();
require_once "config.php";
if (!isset($_SESSION["use"]) || $_SESSION["role"] != "Admin") {
    header("Location: adminlogin.php");
    exit();
}
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $sql = "DELETE FROM borrow_record WHERE SERIAL_NO = $id";
  if (mysqli_query($conn, $sql)) {
      header("Location: accept&deletereq.php");
      exit();
  } else {
      echo "Error deleting record: " . mysqli_error($conn);
  }
}
if (isset($_GET['accept'])) {
  $id = $_GET['accept'];
  $sql = "UPDATE borrow_record SET ACCEPT = 1,ACCEPT_DATE=CURDATE() WHERE SERIAL_NO = $id";
  if (mysqli_query($conn, $sql)) {
      header("Location: accept&deletereq.php");
      exit();
  } else {
      echo "Error updating record: " . mysqli_error($conn);
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Request</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="body">

  <div class="container mt-5">
  <?php
       require_once "config.php";

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM borrow_record as br, books as b,users as u  WHERE br.ACCEPT=0 and b.BOOK_ID = br.BOOK_ID AND u.USER_ID = br.USER_ID ORDER by NAME";
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
                                <th>Borrowed</th>
                                <th>Overdues</th>
                                <th>Action</th>
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
                                  <td>' . $row['USER_ID'] . '</td>
                                  <td>' . $row['NAME'] . '</td>
                                  <td>' . $row['BOOK_ID']. '</td>
                                  <td>' . $row['BOOK_NAME'] . '</td>
                                  <td>' . $totalBorrowed . '</td>
                                  <td>' . $totalOverdue . '</td>
                                  <td class="text-center">
                                      <a href="accept&deletereq.php?accept= '.$row["SERIAL_NO"] .'" class="btn w-60 buttonback3">Accept</a>
                                      <a href="accept&deletereq.php?delete=' . $row['SERIAL_NO'] . '" class="btn w-60 buttonback2" onclick="return confirm(\'Are you sure you want to Reject this Request?\');">Reject</a>
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
    ?>

  </div>
  <div class="container  mt-1 mb-3">
    <div class="row">
    <div class="col-3">
        
    </div>
    <div class="col-6 text-center"><a href="requestlist.php" class="btn buttonback w-50 ">Back</a></div>
    <div class="col-3"></div>
    </div>
    
  </div>
  
  
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
