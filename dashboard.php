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
    <div align="center">
        <img src= images/Logan.png alt=Sprite1 width="400" height="500">
    </div>

</section>
