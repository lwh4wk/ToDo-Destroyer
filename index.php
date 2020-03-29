<!DOCTYPE html>
<html lang="en">
<?php
session_start();
require('dbconnect.php');
if (!isset($_SESSION['username'])) {
    echo "test";
    header("Location: login.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['remove-todo'])) {
        $title = $_POST['title'];
        $statement = $db->prepare("Delete From todo where username='" . $_SESSION['username'] . "' and title='$title'");
        $statement->execute();
        $statement->closeCursor();
    }
    if (isset($_POST['remove-assignment'])) {
        $title = $_POST['title'];
        $due_date = $_POST['dueDate'];
        $statement = $db->prepare("Delete from \"assignment\" where username='" . $_SESSION['username'] . "' and title='$title' and due_date='$due_date';");
        $statement->execute();
        $statement->closeCursor();
    }
}
?>
<head>
    <meta charset="UTF-8">
    <title>Title</title>
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
                </ul>
            </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
            <section id="dashboard" hidden>
                <h1>Dashboard</h1>
                <h3>
                <?php
                if (isset($_SESSION['fname']) && isset($_SESSION['lname'])) {
                    echo "Hello, " . $_SESSION['fname'] . " " . $_SESSION['lname'];
                }
                ?></h3>
                <hr/>
                <h2>To Do List</h2>
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <th scope="col">Title</th>
                        <th scope="col">Description</th>
                    </thead>
                    <tbody>
                    <?php
                    $statement = $db->prepare("select * from todo where username='" . $_SESSION['username'] . "';");
                    $statement->execute();
                    foreach ($statement->fetchAll() as $row) {
                        echo "<tr>" .
                                "<td>$row[1]</td>" .
                                "<td>$row[2]</td>" .
                            "</tr>";
                    }
                    $statement->closeCursor();
                    ?>
                    </tbody>
                </table>
                <hr/>
                <h2>Assignments</h2>
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <th scope="col">Title</th>
                        <th scope="col">Description</th>
                        <th scope="col">Due Date</th>
                    </thead>
                    <tbod>
                        <?php
                        $statement = $db->prepare("select * from \"assignment\" where username='" . $_SESSION['username'] . "';");
                        $statement->execute();
                        foreach ($statement->fetchAll() as $row) {
                            echo "<tr>
                                      <td>$row[1]</td>
                                      <td>$row[2]</td>
                                      <td><script>document.write((new Date('$row[3]')).toDateString())</script></td>
                                  </tr>";
                        }
                        ?>
                    </tbod>
                </table>
            </section>
            <section id="todo" hidden>
                <h1>To Do Items</h1><hr/>
                <?php
                if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['addToDo'])) {

                }
                ?>
                <script>
                    function checkToDo() {
                        let xhr = new XMLHttpRequest();
                        xhr.onreadystatechange = () => {
                            if (xhr.status === 200 && this.readyState === XMLHttpRequest.DONE) {
                                if (xhr.responseText == 'true' ) {
                                    return true;
                                } else {
                                    return false;
                                }
                            }
                        }
                        xhr.open("POST", 'checkExistingToDo.php' false);
                        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                        xhr.send("title=" + document.getElementById("toDoTitleInput").value);
                    }
                </script>
                <form class="needs-validation" action="" method="POST" onsubmit="return checkToDo();" id="addToDoForm">
                    <input value="true" name="addToDo" hidden/>
                    <div class="row" style="margin: auto">
                        <div class="col-lg-2 col-sm-12">
                            <h3>Add Item:</h3>
                        </div>
                        <div class="col-lg-4 col-sm-12">
                            <input type="text" class="form-control" id="toDoTitleInput" placeholder="Title" required/>
                            <div class="invalid-feedback">A to do item with this title alread exists</div>
                        </div>
                        <div class="col-lg-4 col-sm-12">
                            <input type="text" class="form-control" id="toDoDescriptionInput" placeholder="Description" required/>
                        </div>
                        <div class="col-lg-2 col-sm-12">
                            <input type="submit" class="btn btn-outline-primary" value="Submit"/>
                        </div>
                    </div>
                </form>
                <hr/>
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Remove</th>
                    </thead>
                    <tbody>
                    <?php
                    $statement = $db->prepare("select * from todo where username='" . $_SESSION['username'] . "';");
                    $statement->execute();
                    if ($statement->rowCount() > 0) {
                        foreach ($statement->fetchAll() as $row) {
                            ?>
                            <tr>
                                <td><?php echo $row[1] ?></td>
                                <td><?php echo $row[2] ?></td>
                                <td>
                                    <form method="post" action=""
                                          onsubmit="return confirm('Are you sure you want to delete this item?')">
                                        <input type="text" value="true" name="remove-todo" hidden/>
                                        <input type="text" value="<?php echo $row[1] ?>" name="title" hidden/>
                                        <input type="submit" class="btn btn-danger"/>
                                    </form>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {?>
                        <tr>
                            <td colspan="3">You have no to do items.</td>
                        </tr><?php
                    }
                    $statement->closeCursor();
                    ?>
                    </tbody>
                </table>
            </section>
            <section id="assignments" hidden>
                <h1>Assignments</h1>
                <hr/>
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">XP</th>
                    <th scope="col">Due Date</th>
                    <th scope="col">Remove</th>
                    </thead>
                    <tbody>
                        <?php
                        $statement = $db->prepare("select * from \"assignment\" where username='" . $_SESSION['username'] . "';");
                        $statement->execute();
                        if ($statement->rowCount() > 0) {
                            foreach ($statement->fetchAll() as $row) { ?>
                                <tr>
                                    <td><?php echo $row[1] ?></td>
                                    <td><?php echo $row[2] ?></td>
                                    <td><?php echo $row[4] ?></td>
                                    <td>
                                        <script>
                                            date = "<?php echo $row[3]; ?>".split('-');
                                            document.write(new Date(parseInt(date[0]), parseInt(date[1]) - 1, parseInt(date[2])).toDateString());
                                        </script>
                                    </td>
                                    <td>
                                        <form action="" method="post"
                                              onsubmit="return confirm('Are you sure you would like to remove this assignment?');">
                                            <input value="true" name="remove-assignment" hidden/>
                                            <input value="<?php echo $row[1] ?>" name="title" hidden/>
                                            <input value="<?php echo $row[3] ?>" name="dueDate" hidden/>
                                            <input type="submit" class="btn btn-danger" value="Remove"/>
                                        </form>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {?>
                            <tr>
                                <td colspan="5">You have no assignments.</td>
                            </tr><?php
                        }
                        ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>
</div>


<script>
    if (window.localStorage.getItem("currentPage")) {
        nav_click(window.localStorage.getItem("currentPage"));
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
        window.localStorage.setItem("currentPage", nav);
    }
</script>

<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

<script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>

</body>
</html>


