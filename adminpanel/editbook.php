<?php
session_start();
require_once "config.php";
if (!isset($_SESSION["use"]) || $_SESSION["role"] != "Admin") {
    header("Location: adminlogin.php");
    exit();
}
$id = $_GET['update'];
$sql = "SELECT * FROM books WHERE BOOK_ID = $id";
$result = mysqli_query($conn, $sql);
$book = mysqli_fetch_assoc($result);

if (!$book) {
    echo "<div class='alert alert-danger'>Error: Book not found.</div>";
    exit();
}
if (isset($_POST["submit"])) {
    $bookname = mysqli_real_escape_string($conn, $_POST["bookname"]);
    $gener = mysqli_real_escape_string($conn, $_POST["gener"]);
    $quantity = intval($_POST["quantity"]);
    $author = mysqli_real_escape_string($conn, $_POST["author"]);


    $sql = "UPDATE books SET BOOK_NAME='$bookname', GENER='$gener', QUANTITY=QUANTITY+$quantity, AUTHOR='$author' WHERE BOOK_ID=$id";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = "Book updated successfully!";
        header("Location: books.php");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error: Could not update the book.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="body">
<div class="row my-5 mt-5">
  <div class="col-4"></div>
  <div class="container-fluid col-4 border1 p-5 shadow-lg boxback">
    <div class="w-100">
      <h3 class="w-100 text-center">Edit Book</h3>
      <form action="editbook.php?update=<?php echo $id; ?>" method="post">
        <label class="form-label w-75 mx-3">Book Name</label>
        <input class="w-100 mx-3 mb-2 rounded" type="text" name="bookname" value="<?php echo htmlspecialchars($book['BOOK_NAME']); ?>" placeholder="Book Name" required>
        
        <label class="form-label w-75 mx-3">Genre</label>
        <input class="w-100 mx-3 mb-2 rounded" type="text" name="gener" value="<?php echo htmlspecialchars($book['GENER']); ?>" placeholder="Genre" required>
        
        <label class="form-label w-75 mx-3">Quantity</label>
        <input class="w-100 mx-3 mb-2 rounded" type="number" name="quantity" placeholder="Quantity" required>
        
        <label class="form-label w-75 mx-3">Author</label>
        <input class="w-100 mx-3 mb-2 rounded" type="text" name="author" value="<?php echo htmlspecialchars($book['AUTHOR']); ?>" placeholder="Author" required>
        
        <button type="submit" name="submit" class="btn w-100 button mx-3 buttonback">Update Book</button>
      </form>
    </div>
  </div>
  <div class="col-4"></div>

  
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
