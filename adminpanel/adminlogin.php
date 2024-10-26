<?php
session_start();
if (isset($_SESSION["use"])) {
    if ($_SESSION["role"] == "Admin") {
        header("Location: admin.php");
    } elseif ($_SESSION["role"] == "Librarian") {
        header("Location: librarian.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body class="body">
    <div class="row mx-5 my-5">
        <div class="col-4"></div>
        <div class="col-4 my-0 mr-5 mx-0">
            <div class="w-100 container-fluid border1 p-5 my-5 shadow-lg boxback mr-5">
                <h3 class="w-100 text-center">Admin Login</h3>
                <?php
                if (isset($_POST["login"])) {
                    $email = $_POST["email"];
                    $password = $_POST["password"];
                    $role = $_POST["role"];
                    $errors = array();
                    require_once "config.php";
                    $result = mysqli_query($conn, "SELECT * FROM admin WHERE e_mail = '$email' and role='$role'");
                    $rowCount = mysqli_num_rows($result);

                    if ($rowCount == 0) {
                        array_push($errors, "Email does not exist!");
                    } else {
                        $admin = mysqli_fetch_assoc($result);
                        if ($admin['password'] !== $password) {
                            array_push($errors, "Incorrect password");
                        }
                    }

                    if (count($errors) > 0) {
                        foreach ($errors as $error) {
                            echo "<div class='alert alert-danger'>$error</div>";
                        }
                    } else {
                        session_start();
                        $_SESSION["use"] = $email;
                        $_SESSION["role"] = $role;
                        if ($role ==="Admin") {
                            header("Location: admin.php");
                        } elseif ($role ==="Librarian") {
                            header("Location: librarian.php");
                        }
                        exit();
                    }
                }
                ?>
                <form action="adminlogin.php" method="post">
                    <label for="E-mail" class="form-label w-75 mx-3">E-mail</label>
                    <input class="w-100 mx-3 mb-2 rounded" type="email" name="email" placeholder="Enter your e-mail">
                    <label for="Password" class="form-label w-75 mx-3">Password</label>
                    <input class="w-100 mx-3 mb-2 rounded" type="password" name="password" placeholder="Enter your password">
                    <div class="form-check mx-3">
                        <input class="form-check-input" type="radio" name="role" value="Admin" id="adminRole" checked>
                        <label class="form-check-label" for="adminRole">Admin</label>
                    </div>
                    <div class="form-check mx-3 mb-3">
                        <input class="form-check-input" type="radio" name="role" value="Librarian" id="librarianRole">
                        <label class="form-check-label" for="librarianRole">Librarian</label>
                    </div>
                    <button type="submit" class="btn w-100 button mx-3 buttonback" name="login">Login</button>
                </form>
            </div>
        </div>
        <div class="col-4"></div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
