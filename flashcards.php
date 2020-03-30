<?php
$invalid_set = false;
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['title-set'])) {
    $username = $_SESSION['username'];
    $title = $_POST['title-set'];
    $statement = $db->prepare("select * from card_set where username='$username' and title='$title';");
    $statement->execute();
    if ($statement->rowCount() > 0) {
        $invalid_set = true;
    } else {
        $invalid_set = false;
        $statement = $db->prepare("insert into card_set values ('$username', '$title');");
        $statement->execute();
    }
    $statement->closeCursor();
}
$invalid_card = false;
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['card_set_title'])) {
    $username = $_SESSION['username'];
    $title = $_POST['card_set_title'];
    $card_front = $_POST['card_front'];
    $card_back = $_POST['card_back'];
    $statement = $db->prepare("select * from card where username='$username' and set_title='$title' and card_front='$card_front';");
    $statement->execute();
    if ($statement->rowCount() > 0) {
        $invalid_card = true;
        $invalid_title = $title;
    } else {
        $invalid_card = false;
        $statement = $db->prepare("Insert into card values ('$username', '$title', '$card_front', '$card_back');");
        $statement->execute();
    }
    $statement->closeCursor();
}
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['set_title_remove'])) {
    $username = $_SESSION['username'];
    $title = $_POST['set_title_remove'];
    $card_front = $_POST['card_front_remove'];
    $statement = $db->prepare("delete from card where username='$username' and set_title='$title' and card_front='$card_front';");
    $statement->execute();
    $statement->closeCursor();
}
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['title_remove'])) {
    $username = $_SESSION['username'];
    $title = $_POST['title_remove'];
    $statement = $db->prepare("delete from card where username='$username' and set_title='$title';");
    $statement->execute();
    $statement = $db->prepare("delete from card_set where username='$username' and title='$title'");
    $statement->execute();
    $statement->closeCursor();
}
?>
<section id="flashcards" hidden>
    <h1>Flashcards</h1> <hr/>
    <?php if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['flashcard-set'])) { ?>

    <?php } else { ?>
        <div class="row">
            <div class="col-lg-3 col-sm-12">
                <h3>Add a card set:</h3>
            </div>
            <div class="col-lg-9 col-sm-12">
                <form class="form-row" action="" method="post">
                    <div class="col-8">
                        <input class="form-control <?php if ($invalid_set) echo 'is-invalid'; ?>" type="text" id="set_title" name="title-set" placeholder="Title" required/>
                        <div class="invalid-feedback">
                            This set already exists.
                        </div>
                    </div>
                    <div class="col-4">
                        <input type="submit" class="btn btn-outline-primary" value="Submit"/>
                    </div>
                </form>
            </div>
        </div>
        <hr/>
        <h3>Current Sets</h3>
        <div id="accordion">
            <?php
            $username = $_SESSION['username'];
            $statement = $db->prepare("select title from card_set where username='$username'");
            $statement->execute();
            if ($statement->rowCount() > 0) {
                $i = 0;
                foreach ($statement->fetchAll() as $row) { ?>

                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h5 class="mb-0 float-left">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapse<?php echo $i; ?>"
                                        aria-expanded="true" aria-controls="collaps<?php echo $i; ?>">
                                    <?php echo $row[0]; ?>
                                </button>
                            </h5>
                            <div class="float-right">
                                <button class="btn btn-primary" onclick="addCard('<?php echo $row[0]; ?>');" type="button" data-toggle="collapse" data-target="#collapse<?php echo $row[0] ?>">Add Card</button>
                                <form class="d-inline" action="" method="post"
                                      onclick="return confirm('Are you sure that you want to remove this set? This will remove the set and all cards associated with it.');">
                                    <input type="text" name="title_remove" value="<?php echo $row[0]; ?>" hidden/>
                                    <input type="submit" class="btn btn-danger" value="Remove Set"/>
                                </form>
                            </div>

                        </div>

                        <div id="collapse<?php echo $i; ?>" class="collapse" aria-labelledby="headingOne"
                             data-parent="#accordion">
                            <div class="card-body">
                                <?php
                                $username = $_SESSION['username'];
                                $statement = $db->prepare("select card_front, card_back from card where username='$username' and set_title='$row[0]';");
                                $statement->execute();
                                if ($statement->rowCount() > 0) {
                                    echo "<div id='accordion2'>";
                                        $j = 0;
                                        foreach ($statement->fetchAll() as $card_row) {?>
                                            <div class="card">
                                                <div class="card-header" id="headingOne">
                                                    <h4 class="float-left mt-auto mb-auto"><?php echo $card_row[0]; ?></h4>
                                                    <div class="float-right">
                                                        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseCard<?php echo $j; ?>"
                                                                aria-expanded="true" aria-controls="collapseCard<?php echo $j; ?>">Show Definition</button>
                                                        <form class="d-inline" action="" method="post"
                                                              onclick="return confirm('Are you sure that you want to remove this card?');">
                                                            <input value="<?php echo $card_row[0] ?>" type="text" name="card_front_remove" hidden/>
                                                            <input value="<?php echo $row[0] ?>" type="text" name="set_title_remove" hidden/>
                                                            <input type="submit" class="btn btn-danger" value="Remove Card"/>
                                                        </form>
                                                    </div>
                                                </div>
                                                <div id="collapseCard<?php echo $j; ?>" class="collapse" aria-labelledby="headingOne" data-parent="#accordion2">
                                                    <div class="card-body">
                                                        <?php echo $card_row[1]; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php $j++; }
                                    echo "</div>";
                                } else {?>
                                    <div class="card">
                                        <div class="card-header" id="headingOne">
                                            <h5 class="mb-0 float-left">
                                                You have no cards in this set, please click the add card button above to add one.
                                            </h5>
                                        </div>
                                    </div>
                                <?php }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="<?php if ($invalid_card && $row[0] == $invalid_title){} else echo 'collapse'; ?>" id="collapse<?php echo $row[0]; ?>">
                        <div class="card card-body">
                            <form class="form-row" action="" method="post">
                                <input type="text" value="<?php echo $row[0]; ?>" name="card_set_title" hidden/>
                                <div class="col-lg-4 col-sm-12">
                                    <input type="text" class="form-control <?php if ($invalid_card && $row[0] == $invalid_title) echo 'is-invalid'; ?>" placeholder="Term" value="<?php if ($invalid_card && $row[0] == $invalid_title) echo $card_front ?>" name="card_front" required/>
                                    <div class="invalid-feedback">
                                        This card already exists in this set.
                                    </div>
                                </div>
                                <div class="col-lg-7 col-sm-12">
                                    <input type="text" class="form-control <?php if ($invalid_card && $row[0] == $invalid_title) echo 'is-valid'; ?>" placeholder="Definition" value="<?php if ($invalid_card && $row[0] == $invalid_title) echo $card_back ?>" name="card_back" required/>
                                </div>
                                <div class="col-lg-1 col-sm-12">
                                    <input type="submit" class="btn btn-outline-primary" <?php if ($invalid_card && $row[0] == $invalid_title) {?> onload="this.click();" <?php }?> value="Submit"/>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php
                    $i++;
                }
            } else { ?>
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <h5 class="mb-0 float-left">
                                You have no card sets, please create one above.
                        </h5>
                    </div>
                </div>
            <?php }
            $statement->closeCursor();
            ?>
        </div>
        <script>
            function addCard() {

            }
        </script>
    <?php } ?>
</section>
