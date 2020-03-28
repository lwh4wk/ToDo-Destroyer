<?php
// hostname
$hostname = 'ec2-18-206-84-251.compute-1.amazonaws.com';

// database name
$dbname = 'ddd5qje3ftenhn';

// database credentials
$username = 'ejiazxlbmqshag';
$password = '09f1125e80242fb76906615bfce55685e21fae4c2f0e6a054a35907c32366c71';

// DSN (Data Source Name) specifies the host computer for the MySQL database
// and the name of the database. If the MySQL database is running on the same server
// as PHP, use the localhost keyword to specify the host computer

$dsn = "mysql:host=$hostname;dbname=$dbname";

// To connect to a MySQL database named web4640, need three arguments:
// - specify a DSN, username, and password

// Create an instance of PDO (PHP Data Objects) which connects to a MySQL database
// (PDO defines an interface for accessing databases)
// Syntax:
//    new PDO(dsn, username, password);


/** connect to the database **/
try
{
//  $db = new PDO("mysql:host=$hostname;dbname=$dbname, $username, $password);
    $db = new PDO($dsn, $username, $password);

    // dispaly a message to let us know that we are connected to the database
    // echo "<p>You are connected to the database</p>";
}
catch (PDOException $e)     // handle a PDO exception (errors thrown by the PDO library)
{
    // Call a method from any object, use the object's name followed by -> and then method's name
    // All exception objects provide a getMessage() method that returns the error message
    $error_message = $e->getMessage();
    echo "<p>An error occurred while connecting to the database: $error_message </p>";
}
catch (Exception $e)       // handle any type of exception
{
    $error_message = $e->getMessage();
    echo "<p>Error message: $error_message </p>";
}
