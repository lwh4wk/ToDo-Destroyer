<?php
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['remove-assignment'])) {
    $title = $_POST['title'];
    $due_date = $_POST['dueDate'];
    $statement = $db->prepare("Delete from \"assignment\" where username='" . $_SESSION['username'] . "' and title='$title' and due_date='$due_date';");
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
?>
<section id="assignments" hidden>
    <h1>Assignments</h1>
    <hr/>
    <div class="row">
        <div class="col-lg-3 col-sm-12">
            <h3>Add Assignment:</h3>
        </div>
        <div class="col-lg-9 col-sm-12">
            <form action="" method="POST">
                <input value="true" name="add-assignment" hidden/>
                <div class="form-row">
                    <div class="col-lg-4 col-sm-12">
                        <label for="assignmentTitleInput">Title</label>
                        <input type="text" class="form-control <?php if ($invalid) echo "is-invalid"; ?>" id="assignmentTitleInput" placeholder="Title"
                               name="title" <?php if ($invalid) echo "value='$title'"; ?> required/>
                        <div class="invalid-feedback">An assignment with this title already exists</div>
                    </div>
                    <div class="col-lg-8 col-sm-12">
                        <label for="assignmentDescriptionInput">Description</label>
                        <input type="text" class="form-control <?php if ($invalid) echo "is-valid"; ?>" id="assignmentDescriptionInput"
                               placeholder="Description" <?php if ($invalid) echo "value='$description'"; ?> name="description" required/>
                    </div>
                </div>
                <div class="form-row pt-2 pb-2">
                    <div class="col-lg-6 col-sm-12">
                        <label for="assignmentXPInput">Experience Points</label>
                        <input class="form-control <?php if ($invalid) echo "is-valid"; ?>" <?php if ($invalid) echo "value='$xp'"; ?> type="number" placeholder="XP" name="xp" id="assignmentXPInput" required/>
                    </div>
                    <div class="col-lg-6 col-sm-12">
                        <label for="assignmentDueDateInput">Experience Points</label>
                        <input class="form-control <?php if ($invalid) echo "is-valid"; ?>" <?php if ($invalid) echo "value='$dueDate'"; ?> type="date" placeholder="XP" name="dueDate" id="assignmentXPInput" placeholder="mm/dd/yyyy" required/>
                    </div>
                </div>
                <button type="submit" class="btn btn-outline-primary" >Submit</button>
            </form>
        </div>
    </div>

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