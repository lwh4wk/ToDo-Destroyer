<?php
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
<section id="todo" hidden>
    <h1>To Do Items</h1><hr/>
    <div class="row">
        <div class="col-lg-3 col-sm-12">
            <h3>Add To Do Item:</h3>
        </div>
        <div class="col-lg-9 col-sm-12">
            <form class="" action="" method="POST">
                <input value="true" name="addToDo" hidden/>
                <div class="form-row pb-2">
                    <div class="col-lg-8 col-sm-12">
                        <label for="toDoTitleInput">Title</label>
                        <input type="text" class="form-control <?php if ($invalid_todo) echo "is-invalid"; ?>"
                               id="toDoTitleInput" placeholder="Title" name="title"
                               <?php if ($invalid_todo) echo "value='$title'"; ?> required/>
                        <div class="invalid-feedback">A to do item with this title already exists</div>
                    </div>
                    <div class="col-lg-4 col-sm-12">
                        <label for="assignmentXPInput">Experience Points</label>
                        <select class="form-control <?php if ($invalid_todo) echo "is-valid"; ?>" <?php if ($invalid_todo) echo "value='$xp'"; ?> type="number" placeholder="XP" name="xp" id="assignmentXPInput" required>
                            <option value="">---</option>
                            <option value="100">100</option>
                            <option value="500">500</option>
                            <option value="1000">1000</option>
                            <option value="2500">2500</option>
                            <option value="5000">5000</option>
                        </select>
                    </div>
                </div>
                <div class="form-row pb-2">
                    <div class="col-12">
                        <label for="toDoDescriptionInput">Description</label>
                        <input type="text" class="form-control <?php if ($invalid_todo) echo "is-valid"; ?>"
                               id="toDoDescriptionInput" <?php if ($invalid_todo) echo "value='$description'"; ?>
                               placeholder="Description" name="description" required/>
                    </div>
                </div>
                <button type="submit" class="btn btn-outline-primary" value="Submit">Submit</button>
            </form>
        </div>
    </div>
    <hr/>
    <h3>Current To Do Items</h3>
    <table class="table table-bordered table-hover">
        <thead class="thead-light">
        <th scope="col">Title</th>
        <th scope="col">Description</th>
        <th scope="col">XP</th>
        <th scope="col"></th>
        <th scope="col"></th>
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
                    <td ><?php echo $row[3] ?></td>
                    <td style="width: 0.1%; white-space: nowrap;">
                        <form action="" method="POST"
                              onsubmit="return confirm('Are you sure you would like to complete this assignment?');">
                            <input value="true" name="complete-todo" hidden/>
                            <input value="<?php echo $row[1] ?>" name="title" hidden/>
                            <input value="<?php echo $row[2] ?>" name="description" hidden/>
                            <input value=<?php echo $row[3] ?> name="xp" type="number" hidden/>
                            <input type="submit" class="btn btn-success" value="Complete"/>
                        </form>
                    </td>
                    <td style="width: 0.1%; white-space: nowrap">
                        <form method="post" action=""
                              onsubmit="return confirm('Are you sure you want to delete this item?')">
                            <input type="text" value="true" name="remove-todo" hidden/>
                            <input type="text" value="<?php echo $row[1] ?>" name="title" hidden/>
                            <input type="submit" class="btn btn-danger" value="Remove"/>
                        </form>
                    </td>
                </tr>
                <?php
            }
        } else {?>
            <tr>
                <td colspan="5">You have no to do items.</td>
            </tr><?php
        }
        $statement->closeCursor();
        ?>
        </tbody>
    </table>
    <hr/>
    <h3>Completed To Do Items</h3>
    <table class="table-bordered table-hover table">
        <thead class="thead-light">
        <th scope="col">Title</th>
        <th scope="col">Description</th>
        <th scope="col">XP</th>
        <th scope="col">Date Completed</th>
        </thead>
        <tbody>
        <?php
        $statement = $db->prepare("select * from completed_todo where username='" . $_SESSION['username'] . "';");
        $statement->execute();
        if ($statement->rowCount() > 0) {
            foreach ($statement->fetchAll() as $row) {?>
                <tr>
                    <td><?php echo $row[1]; ?></td>
                    <td><?php echo $row[4]; ?></td>
                    <td><?php echo $row[2]; ?></td>
                    <td>
                        <script>
                            date_before = "<?php echo $row[3]; ?>".split(' ');
                            date = date_before[0].split('-');
                            date_time = date_before[1].split('.')[0].split(':');
                            if (parseInt(date_time[0]) == 0){
                                document.write(new Date(parseInt(date[0]), parseInt(date[1]) - 1, parseInt(date[2])).toDateString() + ", 12" + ":" + date_time[1] + " AM");
                            } else if (parseInt(date_time[0]) > 12) {
                                date_time[0] = " " + (date_time[0] - 12);
                                document.write(new Date(parseInt(date[0]), parseInt(date[1]) - 1, parseInt(date[2])).toDateString() + ", " + date_time[0] + ":" + date_time[1] + " PM");
                            } else {
                                document.write(new Date(parseInt(date[0]), parseInt(date[1]) - 1, parseInt(date[2])).toDateString() + ", " + date_time[0] + ":" + date_time[1] + ' AM');
                            }
                        </script>
                    </td>
                </tr>
            <?php }
        } else { ?>
            <tr>
                <td colspan="4">You have no completed to do items.</td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</section>
