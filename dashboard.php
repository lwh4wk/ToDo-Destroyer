<section id="dashboard" hidden>
    <h1>Dashboard</h1>
    <h3>
        <?php
        if (isset($_SESSION['fname']) && isset($_SESSION['lname'])) {
            echo "Hello, " . $_SESSION['fname'] . " " . $_SESSION['lname'];
        }
        ?></h3>
    <?php include('xpbar.php'); ?>
    <hr/>
    <h2>To Do List</h2>
    <table class="table table-bordered table-hover">
        <thead class="thead-light">
        <th scope="col">Title</th>
        <th scope="col">Description</th>
        <th scope="col">XP</th>
        </thead>
        <tbody>
        <?php
        $statement = $db->prepare("select * from todo where username='" . $_SESSION['username'] . "';");
        $statement->execute();
        foreach ($statement->fetchAll() as $row) {
            echo "<tr>" .
                    "<td>$row[1]</td>" .
                    "<td>$row[2]</td>" .
                    "<td>$row[3]</td>" .
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
        <th scope="col">XP</th>
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
                          <td>$row[4]</td>
                          <td><script>document.write((new Date('$row[3]')).toDateString())</script></td>
                      </tr>";
            }
            ?>
        </tbod>
    </table>
    <div align="center">
        <?php
        $statement = $db->prepare("select \"level\" from \"user\" where username='" . $_SESSION['username'] . "';");
        $statement->execute();
        $row = $statement->fetch();
        $statement->closeCursor();
        $level = $row[0];
        if ($level < 4){
            echo "<img src='images/level_" . (string)$level . ".png" . "' alt='error'>";
        }
        else{
            echo "<img src='images/level_3.png" . "' alt='error'>";
        }
        ?>
    </div>

</section>
