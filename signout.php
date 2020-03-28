<?php
unset($_SESSION['username']);
unset($_SESSION['fname']);
unset($_SESSION['lname']);
session_destroy();
header("Location: login.php");