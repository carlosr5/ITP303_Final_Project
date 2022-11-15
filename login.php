<?php
require "./config/config.php";

if (!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) {

    if (isset($_POST["username"]) && isset($_POST["password"])) {

        if (empty($_POST["username"]) || empty($_POST["password"])) {
            $error = "Please enter your username and your password";
        } else {
            $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            if ($mysqli->connect_errno) {
                echo $mysqli->connect_error;
                exit();
            }

            $passwordInput = hash("sha256", $_POST["password"]);

            $sql = "SELECT * FROM users WHERE username = '" . $_POST['username'] . "' AND password = '" . $passwordInput . "';";

            $results = $mysqli->query($sql);

            if (!$results) {
                echo $mysqli->error;
                exit();
            }

            // If there is only one record
            if ($results->num_rows == 1) {
                // Log the user in

                $_SESSION["logged_in"] = true;
                $_SESSION["username"] = $_POST["username"];

                header("Location: ./index.php");
            } else {
                $error = "Invalid username or password.";
            }
        }
    }
} else {
    header("Location: ./index.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Queen's Pawn</title>

    <!-- Importing chessboard.js CSS -->
    <link rel="stylesheet" href="https://unpkg.com/@chrisoakman/chessboardjs@1.0.0/dist/chessboard-1.0.0.min.css"
        integrity="sha384-q94+BZtLrkL1/ohfjR8c6L+A6qzNH9R2hBLwyoAfu3i/WCvQjzL2RQJ3uNHDISdU" crossorigin="anonymous">

    <!-- Importing Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- Importing custom styles -->
    <link rel="stylesheet" href="./css/styles.css">

</head>

<body class="d-flex flex-column min-vh-100">
    <!-- Header Nav Bar -->
    <?php include "./components/nav.php"; ?>

    <!-- Login area -->
    <div class="container">
        <div class="row">
            <div class="col col-lg-3 col-2"></div>
            <div class="col col-lg-6 col-8 text-center">
                <?php
                if (isset($error) && !empty($error)) : ?>
                <div class="text-center text-danger">
                    <?php echo $error; ?>
                </div>
                <?php endif; ?>

                <div class="input-text">
                    Please login with your username and password:
                </div>
                <!-- Just a form that allows people to login/sign up -->
                <form id="login-form" method="POST" action="./login.php">
                    <label for="username-id"></label>
                    <input id="username-id" class="form-control" name="username" type="text" placeholder="Username">

                    <label for="password-id"></label>
                    <input id="password-id" class="form-control" name="password" type="password" placeholder="Password">
                    <button type="submit" class="btn text-center viridian mt-3">Log In</button>
                </form>
                <a href="signup.php">
                    <button class="btn text-center viridian mt-3">Sign-up</button>
                </a>
            </div>
            <div class="col col-lg-3 col-2"></div>
        </div>
    </div>

    <!-- Footer -->
    <?php include "./components/footer.php"; ?>

</body>

<!-- Importing jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<!-- Importing chessboard.js -->
<!-- Not using original JS because I have to change the path for the images in order for them to appear (changed on line 577). Otherwise the rest of the logic is the same -->
<!-- <script src="https://unpkg.com/@chrisoakman/chessboardjs@1.0.0/dist/chessboard-1.0.0.min.js"
    integrity="sha384-8Vi8VHwn3vjQ9eUHUxex3JSN/NFqUg3QbPyX8kWyb93+8AC/pPWTzj+nHtbC5bxD"
    crossorigin="anonymous"></script> -->

<script src="chessboardjs-1.0.0/js/chessboard-1.0.0.js"></script>

<!-- Importing Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
</script>

<script src="./js/login.js"></script>

</html>