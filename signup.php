<?php
$user_exists = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require("dbconnect.php");
    session_start();
    $_SESSION['username'] = $_POST['username'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $username_list = "SELECT * FROM \"user\" WHERE username= '$username'";
    $result = $db->prepare($username_list);
    $result->execute();
    if ($result->rowCount() == 1) {
        $user_exists = true;
        $result->closeCursor();
    }
    else{
        $user_exists = false;
        $result->closeCursor();
        $password = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO \"user\" (username, \"password\", first_name, last_name, email) values ('$username', '$password', '$fname','$lname','$email')";
        $statement = $db->prepare($sql);
        $statement->execute();
        #echo $statement->fetch();
        $statement->closeCursor();
        $_SESSION['username'] = 'username';
        $_SESSION['fname'] = 'fname';
        $_SESSION['lname'] = 'lname';
        header("Location: index.php");
    }
}
?>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

</head>
<header>
    <?php include("navbar.php"); ?>
</header>
<body>
    <div class="jumbotron text-center">
        <h1>Sign up!</h1>
    </div>
<div class="container col-lg-4 col-md-7 col-sm-12" >
    <form method="POST" action="" onsubmit="return checkForm()">
        <div class="form-row">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <label for="fname">First name:</label><br>
                <input class="form-control" type="text" id="fname" name="fname" <?php if ($user_exists) echo "value='$fname'"; ?> required>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <label for="lname">Last name:</label><br>
                <input class="form-control" type="text" id="lname" name="lname" <?php if ($user_exists) echo "value='$lname'"; ?> required><br>
            </div>
        </div>
        <label for="email">Email:</label><br>
        <input class="form-control" type="text" id="email" name="email" <?php if ($user_exists) echo "value='$email'"; ?> required><br>
        <label for="username">Username:</label><br>
        <input class="form-control" type="text" id="username" name="username" <?php if ($user_exists) echo "value='$username'"; ?> required><br>
        <?php if ($user_exists) { ?>
        <p class="alert alert-danger">There is an existing account with this username, please <a href="login.php">sign in</a>.</p>
        <?php } ?>
        <label for="password">Password:</label><br>
        <input class="form-control" type="password" id="password" name="password" required><br>
        <p id="password_error" class="alert alert-danger" hidden>Your password must be at least 8 characters long.</p>
        <label for="confirm_password">Confirm Password:</label><br>
        <input class="form-control" type="password" id="confirm_password" name="confirm_password" required><br>
        <p id="confirm_password_error" class="alert alert-danger" hidden>Your passwords must match.</p>
        <div class="text-center">
            <button type="submit" class="btn btn-outline-secondary">Submit</button>
            <p class="pt-2">Already have an account? <a href="login.php">Sign In</a></p>
        </div>
    </form>
</div>

<script>
    function checkForm() {
        if (document.getElementById('password').value.length < 8) {
            document.getElementById('password_error').hidden = false;
            return false;
        } else {
            document.getElementById('password_error').hidden = true;
            if (document.getElementById("password").value != document.getElementById("confirm_password").value) {
                document.getElementById("confirm_password_error").hidden = false;
                return false;
            } else {
                document.getElementById("confirm_password_error").hidden = true;
            }
        }
        return true;
    }
</script>
</body>