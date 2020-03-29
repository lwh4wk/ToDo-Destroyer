<?php
session_start();
if (isset($_SESSION['username'])) {
    header("Location: index.php");
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ((empty($_POST['username'])) or (empty($_POST['password']))) {
        echo 'Fields not filled';
    }
    else{
        require("dbconnect.php");
        session_start();
        $username = $_POST['username'];
        $password = $_POST['password'];
        $username_list = "SELECT * FROM \"user\" WHERE username= '$username'";
        $result = $db->prepare($username_list);
        $result->execute();
        $row = $result->fetch();
        if (password_verify($password, $row[1])) {
            $_SESSION['username'] = $username;
            $_SESSION['fname'] = $row[2];
            $_SESSION['lname'] = $row[3];
            $result->closeCursor();
            header("Location: index.php");
        }
        else{
            echo "Wrong something or other.";
        }
        $result->closeCursor();
    }

}
?>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

</head>

<header>
    <?php include('navbar.php'); ?>
</header>
<div class="jumbotron text-center">
    <h1>Login to Todo Destroyer</h1>
</div>
<div class="container col-lg-3 col-md-5 col-sm-12">
    <form method="POST" action="">
        <label for="username">Username:</label><br>
        <input class="form-control" type="text" id="username" name="username" placeholder="Username" required><br>
        <label for="password">Password:</label><br>
        <input class="form-control" type="password" id="password" name="password" placeholder="Password" required><br>
        <div class="ml-auto mr-auto justify-content-center text-center">
            <button class="btn btn-outline-secondary ml-auto mr-auto" type="submit">Submit</button>
            <p class="pt-2">Need an account? <a href="signup.php">sign up</a></p>
        </div>
    </form>

</div>
