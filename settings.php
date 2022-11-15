<?php
session_start();
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
    <?php if (!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) : ?>
    <div class="container">
        <div class="row">
            <div class="col text-center">
                You can only access this page if you're logged in.
            </div>
        </div>
    </div>
    <?php else : ?>
    <div class="container">
        <div class="row">
            <div class="col col-lg-3 col-2"></div>
            <div class="col col-lg-6 col-8 text-center">
                <div class="row">

                    <div class="col col-md-3"></div>
                    <div class="col col-12 col-md-6 input-text mt-4">
                        Edit your username here. Got really busy but will add updating passwords soon I promise haha.
                    </div>
                    <div class="col col-md-3"></div>
                </div>
                <!-- Just a form that allows people to login/sign up -->
                <form id="login-form" method="POST" action="./edit-confirmation.php">
                    <label for="curr-username-input"></label>
                    <input id="curr-username-input" class="form-control" name="curr_username"
                        placeholder="Current username">

                    <label for="new-username-input"></label>
                    <input id="new-username-input" class="form-control" name="new_username" placeholder="New username">

                    <label for="curr-password-input"></label>
                    <input id="curr-password-input" class="form-control" type="password" name="curr_password"
                        placeholder="Current password">

                    <button type="button submit" class="btn mt-4 viridian">Submit</button>
                </form>
                <a href="logout.php">
                    <button type="button" class="btn mt-4 mx-auto btn-danger">Log Out</button>
                </a>
                <a href="deleted.php">
                    <button type="button" class="btn mt-4 mx-auto btn-danger">Delete My Account</button>
                </a>
            </div>
            <div class="col col-lg-3 col-2"></div>
        </div>
    </div>
    <?php endif; ?>
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

<script src="./js/settings.js"></script>

</html>