<section id="assignments" hidden>
    <h1>Assignments</h1>
    <hr/>
    <div class="row">
        <div class="col-lg-3 col-sm-12">
            <h3>Add Assignment:</h3>
        </div>
        <div class="col-lg-9 col-sm-12">
            <form action="" method="POST" enctype="multipart/form-data">
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
                        <select class="form-control <?php if ($invalid) echo "is-valid"; ?>" <?php if ($invalid) echo "value='$xp'"; ?> type="number" placeholder="XP" name="xp" id="assignmentXPInput" required>
                            <option value="">---</option>
                            <option value="100">100</option>
                            <option value="500">500</option>
                            <option value="1000">1000</option>
                            <option value="2500">2500</option>
                            <option value="5000">5000</option>
                        </select>
                    </div>
                    <div class="col-lg-6 col-sm-12">
                        <label for="assignmentDueDateInput">Due Date</label>
                        <input class="form-control <?php if ($invalid) echo "is-valid"; ?>" <?php if ($invalid) echo "value='$dueDate'"; ?> type="date" placeholder="XP" name="dueDate" id="assignmentXPInput" placeholder="mm/dd/yyyy" required/>
                    </div>
                </div>
                <button type="submit" class="btn btn-outline-primary" >Submit</button>
            </form>
        </div>
    </div>

    <hr/>
    <h3>Current Assignments</h3>
    <table class="table table-bordered table-hover">
        <thead class="thead-light">
        <th scope="col">Title</th>
        <th scope="col">Description</th>
        <th scope="col">XP</th>
        <th scope="col">Due Date</th>
        <th scope="col"></th>
        <th scope="col"></th>
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
                    <td style="width: 0.1%; white-space: nowrap;">
                        <form action="" method="POST"
                              onsubmit="return confirm('Are you sure you would like to complete this assignment?');">
                            <input value="true" name="complete-assignment" hidden/>
                            <input value="<?php echo $row[1] ?>" name="title" hidden/>
                            <input value="<?php echo $row[2] ?>" name="description" hidden/>
                            <input value=<?php echo $row[4] ?> name="xp" type="number" hidden/>
                            <input type="submit" class="btn btn-success" value="Complete"/>
                        </form>
                    </td>
                    <td style="width: 0.1%; white-space: nowrap;">
                        <form action="" method="post"
                              onsubmit="return confirm('Are you sure you would like to remove this assignment? This will delete all associated files and you will receive no xp.');">
                            <input value="true" name="remove-assignment" hidden/>
                            <input value="<?php echo $row[1] ?>" name="title" hidden/>
                            <input type="submit" class="btn btn-danger" value="Remove"/>
                        </form>
                    </td>
                </tr>
                <?php
            }
        } else {?>
            <tr>
                <td colspan="6">You have no assignments.</td>
            </tr><?php
        }
        ?>
        </tbody>
    </table>
    <hr/>
    <h3>Completed Assignments</h3>
    <table class="table-bordered table-hover table">
        <thead class="thead-light">
            <th scope="col">Title</th>
            <th scope="col">Description</th>
            <th scope="col">XP</th>
            <th scope="col">Date Completed</th>
        </thead>
        <tbody>
            <?php
            $statement = $db->prepare("select * from completed_assignment where username='" . $_SESSION['username'] . "';");
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
                    <td colspan="6">You have no completed assignments.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</section>