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
    if ($_SERVER['REQUEST_METHOD'] && isset($_POST['addToDo'])) {
        $user = $_SESSION['username'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $statement = $db->prepare("select * from todo where username='$user' and title='$title';");
        $statement->execute();
        if ($statement->rowCount() > 0) {?>
            <form class="" action="" method="POST">
                <input value="true" name="addToDo" hidden/>
                <div class="row" style="margin: auto">
                    <div class="col-lg-2 col-sm-12">
                        <h3>Add Item:</h3>
                    </div>
                    <div class="col-lg-4 col-sm-12">
                        <label for="toDoTitleInput">Title:</label>

                        <input type="text" class="form-control is-invalid" id="toDoTitleInput" placeholder="Title"
                               name="title" value="<?php echo $title ?>" required/>
                        <div class="invalid-feedback">A to do item with this title already exists</div>
                    </div>
                    <div class="col-lg-4 col-sm-12">
                        <label for="toDoDescriptionInput">Title:</label>

                        <input type="text" class="form-control is-valid" id="toDoDescriptionInput"
                               placeholder="Description" name="description" value="<?php echo $description ?>" required/>
                    </div>
                    <div class="col-lg-2 col-sm-12">
                        <input type="submit" class="btn btn-outline-primary" value="Submit"/>
                    </div>
                </div>
            </form>
            <?php
        } else {
            ?>
            <form class="needs-validating" action="" method="POST">
                <input value="true" name="addToDo" hidden/>
                <div class="row" style="margin: auto">
                    <div class="col-lg-2 col-sm-12">
                        <h3>Add Item:</h3>
                    </div>
                    <div class="col-lg-4 col-sm-12">
                        <label for="toDoTitleInput">Title:</label>

                        <input type="text" class="form-control" id="toDoTitleInput" placeholder="Title"
                               name="title" required/>
                        <div class="invalid-feedback">A to do item with this title already exists</div>
                    </div>
                    <div class="col-lg-4 col-sm-12">
                        <label for="toDoDescriptionInput">Title:</label>

                        <input type="text" class="form-control" id="toDoDescriptionInput"
                               placeholder="Description" name="description" required/>
                    </div>
                    <div class="col-lg-2 col-sm-12">
                        <input type="submit" class="btn btn-outline-primary" value="Submit"/>
                    </div>
                </div>
            </form>
            <?php
            $statement->closeCursor();
            $statement = $db->prepare("insert into todo values ('$user', '$title', '$description');");
            $statement->execute();
            $statement->closeCursor();
        }
    } else {
        ?>
        <form class="needs-validation" action="" method="POST">
            <input value="true" name="addToDo" hidden/>
            <div class="row" style="margin: auto">
                <div class="col-lg-2 col-sm-12">
                    <h3>Add Item:</h3>
                </div>
                <div class="col-lg-4 col-sm-12">
                    <label for="toDoTitleInput">Title:</label>
                    <input type="text" class="form-control" id="toDoTitleInput" placeholder="Title"
                           name="title" required/>
                    <div class="invalid-feedback">A to do item with this title already exists</div>
                </div>
                <div class="col-lg-4 col-sm-12">
                    <label for="toDoDescriptionInput">Title:</label>
                    <input type="text" class="form-control" id="toDoDescriptionInput"
                           placeholder="Description" name="description" required/>
                </div>
                <div class="col-lg-2 col-sm-12">
                    <input type="submit" class="btn btn-outline-primary" value="Submit"/>
                </div>
            </div>
        </form>
        <?php
    }
    ?>
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
