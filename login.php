<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ((empty($_POST['username'])) or (empty($_POST['password']))) {
        echo 'Fields not filled';
    }
    else{
        require("dbconnect.php");
        session_start();
        $_SESSION['username'] = $_POST['username'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $username_list = "SELECT * FROM \"user\" WHERE username= '$username' AND password='$password'";
        $result = $db->prepare($username_list);
        $result->execute();
        if ($result->rowCount() == 1) {
            $result->closeCursor();
            $_SESSION['username'] = 'username';
            $first_name = "SELECT 'first_name' FROM \"user\" WHERE username= '$username' AND password='$password'";
            $last_name = "SELECT 'last_name' FROM \"user\" WHERE username= '$username' AND password='$password'";
            $f_result = $db->prepare($first_name);
            $f_result->execute();
            $l_result = $db->prepare($last_name);
            $l_result->execute();
            $_SESSION['fname'] = $f_result;
            $_SESSION['lname'] = $l_result;
            $l_result->closeCursor();
            $f_result->closeCursor();
            header("Location: index.php");
        }
        else{
            echo "Wrong something or other.";
        }
        $result->closeCursor();
    }

}
?>


    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
        .container {
            position: absolute;
            top: 50%;
            left: 50%;
            -moz-transform: translateX(-50%) translateY(-50%);
            -webkit-transform: translateX(-50%) translateY(-50%);
            transform: translateX(-50%) translateY(-50%);
        }
    </style>
    <div class="container">
    <span>
        <h1>Sign up!</h1>
    <form method="POST" action="">
        <label for="username">Username:</label><br>
        <input class="form-control" type="text" id="username" name="username"><br>
        <label for="password">Password:</label><br>
        <input class="form-control" type="password" id="password" name="password"><br>
        <input type="submit" value="Submit">
    </form>
    </span>
    </div>
