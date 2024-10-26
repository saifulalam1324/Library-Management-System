<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: userlogin.php");
    exit();
}

require_once "config.php";
$email = $_SESSION["user_email"];
$result = mysqli_query($conn, "SELECT * FROM users WHERE E_MAIL = '$email'");

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    $user_id = $user['USER_ID'];
} else {
    echo "User information not found.";
    exit();
}
?>
<?php
require_once "config.php";
$sql = "SELECT COUNT(SERIAL_NO) as total FROM borrow_record as bw ,books as b WHERE USER_ID = '$user_id' and bw.BOOK_ID = b.BOOK_ID and bw.ACCEPT = 1 AND RECEIVED = 1";
$result = mysqli_query($conn, $sql);
$totalCount = 0; 
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $totalCount = $row['total'];
}
?>
<?php
require_once "config.php";
$sql = "SELECT COUNT(SERIAL_NO) as total FROM borrow_record as bw ,books as b WHERE USER_ID = '$user_id' and bw.BOOK_ID = b.BOOK_ID and OVERDUE = 1";
$result = mysqli_query($conn, $sql);
$totalCount1 = 0; 
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $totalCount1 = $row['total'];
}
?>
<?php
require_once "config.php";
$sql = "SELECT COUNT(SERIAL_NO) as total FROM borrow_record as bw ,books as b WHERE USER_ID = '$user_id' and bw.BOOK_ID = b.BOOK_ID and bw.ACCEPT = 1 AND bw.RECEIVED = 0 and DATEDIFF(CURDATE(),ACCEPT_DATE) <= 1";
$result = mysqli_query($conn, $sql);
$totalCount2 = 0; 
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $totalCount2 = $row['total'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>userinfo</title>
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
                        <a class="nav-link navbar-brand" href="userinfo.php"><img class="adminimg" src="pictures/user logo.png" alt="#" srcset=""></a>
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
        <table class="table table1 table-bordered table-striped shadow-lg">
            <td><div class="row"><div class="col-4"><h4>Welcome, <?php echo htmlspecialchars($user['NAME']); ?>! </h4><p>Email: <?php echo htmlspecialchars($user['E_MAIL']); ?></p></div><div class="col-6"></div><div class="col-2 text-end"><h4></h4></div></div></td>
        </table>
    </div>
    <div class="container">
        <table class="table table1 table-bordered table-striped shadow-lg">
            <td><div class="row"><div class="col-8"><h4>Books have been successfully borrowed <a onclick="toggleDiv('div1')" class="btn buttonback4">See more..</a></h4></div><div class="col-2"></div><div class="col-2 text-end"><h4>Total: <?php echo $totalCount?></h4></div></div></td>
        </table>
    </div>

    <div class="container mb-3" id="div1">
        <?php
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "SELECT * FROM borrow_record as bw ,books as b WHERE USER_ID = '$user_id' and bw.BOOK_ID = b.BOOK_ID and bw.ACCEPT = 1 AND RECEIVED = 1";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                echo '<table class="table table1 table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr class="text-center">
                               <th>Book Name</th>
                                <th>Gener</th>
                                <th>Author</th>
                            </tr>
                        </thead>
                        <tbody>';
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>
                            <td>' . $row["BOOK_NAME"]. '</td>
                            <td>' . $row["GENER"]. '</td>
                            <td>' . $row["AUTHOR"]. '</td>
                            ';
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
        <table class="table table1 table-bordered table-striped shadow-lg">
            <td><div class="row"><div class="col-8"><h4>Overdues <a onclick="toggleDiv('div2')" class="btn">See more..</a></h4></div><div class="col-2"></div><div class="col-2 text-end"><h4>Total: <?php echo $totalCount1?></h4></div></div></td>
        </table>
    </div>
    <div class="container mb-3" id="div2">
        <?php
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "SELECT * FROM borrow_record as bw ,books as b WHERE bw.USER_ID = '$user_id' and bw.OVERDUE = 1 and bw.BOOK_ID=b.BOOK_ID";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                echo '<table class="table table1 table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr class="text-center">
                               <th>Book Name</th>
                                <th>Gener</th>
                                <th>Author</th>
                            </tr>
                        </thead>
                        <tbody>';
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>
                            <td>' . $row["BOOK_NAME"]. '</td>
                            <td>' . $row["GENER"]. '</td>
                            <td>' . $row["AUTHOR"]. '</td>
                            ';
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
        <table class="table table1 table-bordered table-striped shadow-lg">
            <td><div class="row"><div class="col-8"><h4>Accepted Requests <a onclick="toggleDiv('div3')" class="btn">See more..</a></h4></div><div class="col-2"></div><div class="col-2 text-end"><h4>Total: <?php echo $totalCount2 ?></h4></div></div></td>
        </table>
    </div>
    <div class="container mb-3" id="div3">
        <?php
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "SELECT * FROM borrow_record as bw ,books as b,users as u WHERE bw.USER_ID = '$user_id' and u.USER_ID='$user_id' and bw.ACCEPT = 1 and bw.RECEIVED = 0 and bw.BOOK_ID=b.BOOK_ID and DATEDIFF(CURDATE(),ACCEPT_DATE) <= 1";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                echo '<table class="table table1 table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr class="text-center">
                            </tr>
                        </thead>
                        <tbody>';
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>
                            <td>' . $row["BOOK_NAME"]. '</td>
                            <td>' . $row["GENER"]. '</td>
                            <td>' . $row["AUTHOR"]. '</td>
                            <td class="text-center">
                                      <a href="pdf.php?seemore= '.$row["SERIAL_NO"] .'" class="btn w-60 buttonback">See more..</a>
                            </td>
                            ';
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

    <script>
        window.onload = function() {
        document.getElementById("div1").style.display = "none";
        document.getElementById("div2").style.display = "none";
        document.getElementById("div3").style.display = "none"
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



</body>
</html>