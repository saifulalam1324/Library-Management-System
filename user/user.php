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

  $sql = "INSERT INTO borrow_record (user_id,book_id) VALUES ('$user_id','$book_id')"; 
  if (mysqli_query($conn, $sql)) {
    header("Location: user.php");
    exit();
  } else {
      echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
  }
}
?>
<?php
require_once "config.php";
$sql4 = "SELECT b.BOOK_ID,b.BOOK_NAME,b.GENER,b.AUTHOR,count(b.BOOK_ID) as total FROM borrow_record as br,books as b WHERE  b.BOOK_ID = br.BOOK_ID and br.ACCEPT=1 and br.RECEIVED=1 
        GROUP by b.BOOK_ID,b.BOOK_NAME,b.GENER,b.AUTHOR ORDER by total desc limit 3";
        $result4 = mysqli_query($conn, $sql4);
        
?>
<?php
require_once "config.php";
$sql1 = "SELECT b.BOOK_ID,b.BOOK_NAME,b.GENER,b.AUTHOR,count(b.GENER) as total FROM borrow_record as br,books as b WHERE  b.BOOK_ID = br.BOOK_ID and br.ACCEPT=1 and br.RECEIVED=1 
        GROUP by b.BOOK_ID,b.BOOK_NAME,b.GENER,b.AUTHOR ORDER by total desc limit 3";
        $result1 = mysqli_query($conn, $sql1);
        
?>
<?php
require_once "config.php";
$sql2 = "SELECT u.USER_ID,U.NAME,count(u.USER_ID) as total FROM borrow_record as br,users as u WHERE  u.USER_ID = br.USER_ID and br.ACCEPT=1 and br.RECEIVED=1 
        GROUP by u.USER_ID,u.NAME ORDER by total desc limit 3";
        $result2 = mysqli_query($conn, $sql2);
        
?>
<?php
require_once "config.php";
$sql = "SELECT COUNT(USER_ID) AS total FROM users";
$result = mysqli_query($conn, $sql);
$totalCount = 0; 
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $totalCount = $row['total'];
}
?>
<?php
require_once "config.php";
$sql = "SELECT COUNT(BOOK_ID) AS total FROM books";
$result = mysqli_query($conn, $sql);
$totalCount1 = 0; 
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $totalCount1 = $row['total'];
}
?>
<?php
require_once "config.php";
$sql = "SELECT  COUNT(distinct GENER) AS total FROM books";
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
    <title>User</title>
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
          <div class="container mt-5 pt-5"></div>
    <div class="row container-fluid mt-5 pt-3">
      <div class="col-3">

      </div>
      <div class="col-4 ml-auto mr-auto ">
      <div class="main m-auto shadow-lg">
        <form action="user.php" method="GET" class="d-flex flex-row">
          <div>
            <input class="input rounded " type="search"  name="search" required placeholder="search for book's">
          </div>
          <div class="ml-auto">
          <button class="searchbutton" type="submit" name="submit"><i class="fa fa-search icon-search"></i></button> 
          </div>        
        
        </form>
       
      </div>
      </div>
      
      <div class="col-3">

      </div>
    </div>
    <div class="container mt-5">
    <table class="table table-striped table1 table-bordered shadow-lg p-3 mb-2">
            <?php
            if (isset($_GET['search'])) {
                $search = $_GET['search'];
                $sql = "SELECT * FROM books WHERE BOOK_NAME like '%$search%' or GENER like '%$search%' or AUTHOR like '%$search%' ORDER BY BOOK_NAME";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    echo '
                    <thead class="thead-dark">
                        <tr class="text-center">
                            <th>Book Name</th>
                            <th>Gener</th>
                            <th>Author</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>';

                    while ($row1 = mysqli_fetch_assoc($result)) {
                      $status = (intval($row1["QUANTITY"]) > 0) ? 'Available' : 'Unavailable';
                       $buttonDisabled = (intval($row1["QUANTITY"]) > 0) ? '' : 'disabled';
                        echo '
                        <tbody>
                            <tr>
                                <td>' . $row1["BOOK_NAME"] . '</td>
                                <td>' . $row1["GENER"] . '</td>
                                <td>' . $row1["AUTHOR"] . '</td>
                                 <td>' . $status . '</td>
                                <td class="text-center">
                                    <form action="user.php" method="post">
                                        <input type="hidden" name="book_id" value="' . $row1['BOOK_ID'] . '">
                                        <button type="submit" class="btn w-60 buttonback" name="borrow" '. $buttonDisabled .' onclick="return confirm(\'Request sent to Admin\');">Borrow</button>
                                    </form>
                                </td>
                            </tr>
                        </tbody>';
                    }
                } else {
                    echo "<div class='alert alert-danger'>Not Found</div>";
                }
            }
            ?>
        </table>
    </div>
    <div class="container mt-3">
            <div class="row">
                <div class="col-4">
                    <div class="card shadow-lg card2">
                    <div class="card-body">
                      <p class="card-title">If my book is open, <br> your mouth should be closed.</p>  
                        </div>
                  </div>
                </div>
                <div class="col-4">
                    <div class="card shadow-lg card2">
                    <div class="card-body">
                      <p class="card-title">Books are a uniquely portable magic. — Stephen King</p>
                      
                    </div>
                  </div>
                </div>
                <div class="col-4">
                  <div class="card shadow-lg card2">
                    <div class="card-body">
                      <p class="card-title">Reading gives us someplace to go when we have to stay where we are. — Mason Cooley</p>           
                    </div>
                  </div>
                </div>

            </div>
          </div>

    </div>
    <div class="container mt-3">
            <div class="row">
                <div class="col-4">
                    <div class="card shadow-lg card1">
                    <div class="card-body">
                      <h5 class="card-title">Most Popular Books</h5>
                      <?php
                          if ($result4 && mysqli_num_rows($result4) > 0) {
                                while ($row = mysqli_fetch_assoc($result4)) {
                                 echo '
                                    <p>' . htmlspecialchars($row["BOOK_NAME"]) . '</p>
                                          ';
                               }
                             } 
                        ?>
                        <a href="booklist.php" class="card-link">See more</a>
                        </div>
                  </div>
                </div>
                <div class="col-4">
                    <div class="card shadow-lg card1">
                    <div class="card-body">
                      <h5 class="card-title">Top 3 Readers</h5>
                      <?php
                          if ($result2 && mysqli_num_rows($result2) > 0) {
                                while ($row = mysqli_fetch_assoc($result2)) {
                                 echo '
                                    <p>' . htmlspecialchars($row["NAME"]) . '</p>
                                          ';
                               }
                             } 
                        ?>
                      
                    </div>
                  </div>
                </div>
                <div class="col-4">
                  <div class="card shadow-lg card1">
                    <div class="card-body">
                      <h5 class="card-title">Most Populer Categories</h5>
                      <?php
                          if ($result1 && mysqli_num_rows($result1) > 0) {
                                while ($row = mysqli_fetch_assoc($result1)) {
                                 echo '
                                    <p>' . htmlspecialchars($row["GENER"]) . '</p>
                                          ';
                               }
                             } 
                        ?>
                       <a href="booklist.php" class="card-link">See more</a>
                     
                    </div>
                  </div>
                </div>

            </div>
          </div>

    </div>
    <div class="container mt-3">
            <div class="row">
                <div class="col-4">
                    <div class="card shadow-lg card2">
                    <div class="card-body">
                      <h5 class="card-title">Over <?php echo $totalCount-1?>+ Users</h5>  
                        </div>
                  </div>
                </div>
                <div class="col-4">
                    <div class="card shadow-lg card2">
                    <div class="card-body">
                      <h5 class="card-title">Over <?php echo $totalCount1-1?>+ Books</h5>
                      
                    </div>
                  </div>
                </div>
                <div class="col-4">
                  <div class="card shadow-lg card2">
                    <div class="card-body">
                      <h5 class="card-title">Over <?php echo $totalCount2-1?>+ Category</h5>           
                    </div>
                  </div>
                </div>

            </div>
          </div>

    </div>
    <div class="container mt-3 pt-3">

</div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" 
    integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
    crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" 
    crossorigin="anonymous"></script>
</body>
</html>
