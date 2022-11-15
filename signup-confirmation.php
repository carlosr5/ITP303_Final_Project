<?php
require "./config/config.php";

if (
    !isset($_POST['name']) ||
    empty($_POST['name']) ||
    !isset($_POST['email']) ||
    empty($_POST['email']) ||
    !isset($_POST['username']) ||
    empty($_POST['username']) ||
    !isset($_POST['password']) ||
    empty($_POST['password'])
) {
    $error = "Please fill out all required fields.";
} else {
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($mysqli->connect_errno) {
        echo $mysqli->connect_error;
        exit();
    }

    // Is the current user registered?
    $registered_stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $registered_stmt->bind_param("ss", $_POST["username"], $_POST["email"]);

    $registered_exe = $registered_stmt->execute();
    if (!$registered_exe) {
        echo $mysqli->error;
    }

    // Checking if there are any users that share the same username or email
    $registered_stmt->store_result();

    $numrows = $registered_stmt->num_rows;
    $registered_stmt->close();

    if ($numrows > 0) {
        $error = "Username or email already taken. Please try again.";
    } else {
        // Storing the user into the table.
        $password = hash("sha256", $_POST["password"]);

        $statement = $mysqli->prepare("INSERT INTO users(name, username, password, email) VALUES(?,?,?,?);");

        $statement->bind_param("ssss", $_POST["name"], $_POST["username"], $password, $_POST["email"]);

        $executed = $statement->execute();

        if (!$executed) {
            echo $mysqli->error;
        }
        $statement->close();
    }
    $mysqli->close();
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

    <!-- Confirmation Area -->
    <div class="container">
        <div class="row">
            <div class="col col-lg-3 col-2"></div>

            <?php if (isset($error) && !empty($error)) : ?>
            <div class="col col-lg-6 col-8 text-center text-danger">
                <?php echo $error; ?>
            </div>

            <?php else : ?>
            <div class="col col-lg-6 col-8 text-center text-success">
                <?php echo $_POST['username']; ?> was successfully registered.
            </div>
            <?php endif; ?>

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

<script src="./js/signup-confirmation.js"></script>

</html>