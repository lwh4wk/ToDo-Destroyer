<?php
if ($_SERVER['REQUEST_METHOD']) {
    require('dbconnect.php');
    session_start();
    $user = $_SESSION['username'];
    $title = $_POST['title'];
    $statement = $db->prepare("select * from todo where username='$user' and title='$title';");
}