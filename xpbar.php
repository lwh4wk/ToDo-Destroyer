<?php
    $statement = $db->prepare("select experience_points, \"level\" from \"user\" where username='" . $_SESSION['username'] . "';");
    $statement->execute();
    $row = $statement->fetch();
    $statement->closeCursor();
    $xp = $row[0];
    $level = $row[1];
?>
<h5>Current xp: <?php echo $xp; ?>, <?php echo 1000 - $xp ?> xp until you reach the next level.</h5>
<div class="progress">
    <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $xp/10; ?>%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><?php echo $xp/10; ?>%</div>
</div>