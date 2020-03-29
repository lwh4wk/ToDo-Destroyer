<?php
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['remove-assignment'])) {
    $title = $_POST['title'];
    $due_date = $_POST['dueDate'];
    $statement = $db->prepare("Delete from \"assignment\" where username='" . $_SESSION['username'] . "' and title='$title' and due_date='$due_date';");
    $statement->execute();
    $statement->closeCursor();
}
?>
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