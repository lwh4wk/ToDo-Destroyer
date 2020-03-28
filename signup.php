<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ((empty($_POST['username'])) or (empty($_POST['fname'])) or (empty($_POST['lname'])) or (empty($_POST['email'])) or (empty($_POST['password']))) {
        echo 'Please fill out all the fields!';
    }
    else if (($_POST['password']) != ($_POST['confirm_password'])){
        echo 'Make sure your passwords match...';
    }
    else{
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
            echo 'Username already taken. Choose something better?';
            $result->closeCursor();
        }
        else{
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
        <label for="fname">First name:</label><br>
        <input class="form-control" type="text" id="fname" name="fname"><br>
        <label for="lname">Last name:</label><br>
        <input class="form-control" type="text" id="lname" name="lname"><br>
        <label for="email">Email:</label><br>
        <input class="form-control" type="text" id="email" name="email"><br>
        <label for="username">Username:</label><br>
        <input class="form-control" type="text" id="username" name="username"><br>
        <label for="password">Password:</label><br>
        <input class="form-control" type="password" id="password" name="password"><br>
        <label for="password">Confirm Password:</label><br>
        <input class="form-control" type="password" id="password" name="confirm_password"><br>
        <input type="submit" value="Submit">
    </form>
    </span>
</div>