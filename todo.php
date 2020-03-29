<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove-todo'])) {
    $title = $_POST['title'];
    $statement = $db->prepare("Delete From todo where username='" . $_SESSION['username'] . "' and title='$title'");
    $statement->execute();
    $statement->closeCursor();
}?>
<section id="todo" hidden>
    <h1>To Do Items</h1><hr/>
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
    }?>
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
                        <label for="toDoXPInput">XP</label>
                        <input type="number" class="form-control <?php if ($invalid_todo) echo "is-valid"; ?>"
                               id="toDoXPInput" placeholder="XP" <?php if ($invalid_todo) echo "value='$xp'"; ?>
                               name="xp" required/>
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
    <table class="table table-bordered table-hover">
        <thead class="thead-light">
        <th scope="col">Title</th>
        <th scope="col">Description</th>
        <th scope="col">XP</th>
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
                    <td><?php echo $row[3] ?></td>
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
