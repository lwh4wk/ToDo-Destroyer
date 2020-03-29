<!DOCTYPE html>
<html lang="en">
<?php
session_start();
require('dbconnect.php');
if (!isset($_SESSION['username'])) {
    echo "test";
    header("Location: login.php");
}


?>

<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['secret_value'])) {
        require("dbconnect.php");
        $username = $_SESSION['username'];
        $username_list = "SELECT experience_points, \"level\" FROM \"user\" WHERE username= '$username'";
        $result = $db->prepare($username_list);
        $result->execute();
        $row = $result->fetch();
        $XP_count = $row[0];
        $level = $row[1];
        $xp = ($XP_count) + ($_POST['secret_value']);
        //echo $xp;
        if ($xp >= 5000){
            $new_xp = $xp - 5000;
            $new_level = $level + 1;
            $up_xp = "UPDATE \"user\" SET experience_points=$new_xp, \"level\"=$new_level WHERE username='" . $_SESSION['username'] . "';";
            $result = $db->prepare($up_xp);
            $result->execute();
            $result->closeCursor();
        }
        else{
            $sql = "UPDATE \"user\" SET experience_points=$xp WHERE username='" . $_SESSION['username'] . "';";
            $statement = $db->prepare($sql);
            $statement->execute();
            $statement->closeCursor();
        }

}
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['remove-assignment'])) {
    $title = $_POST['title'];

    $statement = $db->prepare("Delete from \"assignment\" where username='" . $_SESSION['username'] . "' and title='$title';");
    $statement->execute();
    $statement->closeCursor();
}
$invalid = false;
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['add-assignment'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $xp = $_POST['xp'];
    $dueDate = $_POST['dueDate'];
    $user = $_SESSION['username'];
    $statement = $db->prepare("select * from \"assignment\" where username='$user' and title='$title';");
    $statement->execute();
    if ($statement->rowCount() > 0) $invalid = true;
    else {
        $invalid = false;
        $statement = $db->prepare("insert into \"assignment\" values ('$user', '$title', '$description', '$dueDate', '$xp');");
        $statement->execute();
    }
    $statement->closeCursor();
}
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['complete-assignment'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $xp = $_POST['xp'];
    $username = $_SESSION['username'];
    $statement = $db->prepare("select experience_points, \"level\" from \"user\" where username='$username';");
    $statement->execute();
    $row = $statement->fetch();
    $new_xp = $row[0];
    $level = $row[1];
    if (($new_xp + $xp) >= 5000) {
        $new_xp += $xp;
        $new_xp -= 5000;
        $level ++;
    } else {
        $new_xp += $xp;
    }
    $statement = $db->prepare("update \"user\" set experience_points=$new_xp, \"level\"=$level where username='$username';");
    $statement->execute();
    $statement = $db->prepare("Insert Into completed_assignment values ('$username', '$title', $xp, CURRENT_TIMESTAMP, '$description');");
    $statement->execute();
    $statement = $db->prepare("Delete from \"assignment\" where username='$username' and title='$title';");
    $statement->execute();
}

$invalid_todo = false;
if ($_SERVER['REQUEST_METHOD'] && isset($_POST['addToDo'])) {
    $user = $_SESSION['username'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $xp = $_POST['xp'];
    $statement = $db->prepare("select * from todo where username='$user' and title='$title';");
    $statement->execute();
    if ($statement->rowCount() > 0) {
        $invalid_todo = true;
    } else {
        $invalid_todo = false;
        $statement->closeCursor();
        $statement = $db->prepare("insert into todo values ('$user', '$title', '$description', '$xp');");
        $statement->execute();
        $statement->closeCursor();
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove-todo'])) {
    $title = $_POST['title'];
    $statement = $db->prepare("Delete From todo where username='" . $_SESSION['username'] . "' and title='$title'");
    $statement->execute();
    $statement->closeCursor();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['complete-todo'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $xp = $_POST['xp'];
    $username = $_SESSION['username'];
    $statement = $db->prepare("select experience_points, \"level\" from \"user\" where username='$username';");
    $statement->execute();
    $row = $statement->fetch();
    $new_xp = $row[0];
    $level = $row[1];
    if (($new_xp + $xp) >= 5000) {
        $new_xp += $xp;
        $new_xp -= 5000;
        $level ++;
    } else {
        $new_xp += $xp;
    }
    $statement = $db->prepare("update \"user\" set experience_points=$new_xp, \"level\"=$level where username='$username';");
    $statement->execute();
    $statement = $db->prepare("Insert Into completed_todo values ('$username', '$title', $xp, CURRENT_TIMESTAMP, '$description');");
    $statement->execute();
    $statement = $db->prepare("Delete from todo where username='$username' and title='$title';");
    $statement->execute();
}
?>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
<nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
    <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="javascript: nav_click('dashboard');">To Do Destroyer</a>
    <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
            <a class="nav-link" href="signout.php">Sign out</a>
        </li>
    </ul>
</nav>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar" style="height: 100vh;">
            <div class="sidebar-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="javascript: nav_click('dashboard');">
                            <span data-feather="home"></span>
                            Dashboard <span class="sr-only">(current)</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript: nav_click('todo');">To Do Items</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript: nav_click('assignments');">Assignments</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript: nav_click('workspace');">Workspace</a>
                    </li>
                </ul>
            </div>
        </nav>
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
            <?php
                include("dashboard.php");
                include("todo.php");
                include("assignments.php");
            ?>
            <section id="workspace" hidden>
                <div align="center">
                    <h1>Pomodoro</h1>
                    <div>
                        <h2 id="timer">25m 0s</h2>
                        <button class='btn-secondary-outline' onclick="myTimer()">Start counter</button>
                        <button class='btn-secondary-outline' onclick="clearInterval(x);">Stop counter</button>
                        <script>
                            var x;
                            function myTimer() {
                                document.getElementById("timer").innerHTML = "25m 0s";
                                x = setInterval(startPomodoro, 1000);
                                var distance = 4;

                                function startPomodoro() {
                                    var minutes = Math.floor(distance / 60);
                                    var seconds = Math.floor(distance % 60);
                                    document.getElementById("timer").innerHTML = minutes + "m " + seconds + "s ";
                                    distance = distance -1;
                                    if (distance < 0) {
                                        document.getElementById("timer").innerHTML = "+2500 XP. Great Job!";
                                        document.getElementById("wealth").innerHTML = String(Number(document.getElementById("wealth").innerHTML) + 2500);
                                        document.getElementById("secret_value").value = Number(document.getElementById("wealth").innerHTML);
                                        clearInterval(x);
                                    }
                                }
                            }
                        </script>
                        <h2 id="break_timer">5m 0s</h2>
                        <button class='btn-secondary-outline' onclick="myBreak()">Start counter</button>
                        <button class='btn-secondary-outline' onclick="clearInterval(y);">Stop counter</button>
                        <script>
                            var y;
                            function myBreak() {
                                document.getElementById("break_timer").innerHTML = "5m 0s";
                                y = setInterval(startPomodoro, 1000);
                                var distance = 300;

                                function startPomodoro() {
                                    var minutes = Math.floor(distance / 60);
                                    var seconds = Math.floor(distance % 60);
                                    document.getElementById("break_timer").innerHTML = minutes + "m " + seconds + "s ";
                                    distance = distance -1;
                                    if (distance < 0) {
                                        document.getElementById("break_timer").innerHTML = "Break's over! Let's be productive!";
                                        clearInterval(y);
                                    }
                                }
                            }
                        </script>
                    </div>
                    <br>
                    <div>
                        <form method="POST" action="">
                            <img src= images/chest.png alt=Sprite1 width="400" height="300">
                            <h1 id = wealth>0</h1>
                            <input value="" id ="secret_value" name="secret_value" hidden>
                            <button class='btn-secondary-outline'>Collect XP!</button>
                        </form>
                    </div>
                </div>
            </section>
        </main>
</div>


<script>
    if (window.sessionStorage.getItem("currentPage")) {
        nav_click(window.sessionStorage.getItem("currentPage"));
    } else {
        nav_click('dashboard');
    }
    function nav_click(nav) {
        if (document.getElementById(nav).hidden != false) {
            let sections = document.getElementsByTagName("section");
            for (let i = 0; i < sections.length; i++) {
                sections[i].hidden = true;
            }
            document.getElementById(nav).hidden = false;
        }
        window.sessionStorage.setItem("currentPage", nav);
    }
</script>

<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

<script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>

</body>
</html>


