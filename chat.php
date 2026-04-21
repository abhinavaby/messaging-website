<?php
include 'db.php';
$query = "SELECT * FROM messages ORDER BY id DESC";
$run = $con->query($query);
while($row = $run->fetch_array()) :
?>
    <div class="msg_div">
        <span class="user_name"><?php echo $row['username']; ?>: </span>
        <span><?php echo $row['msg']; ?></span>
    </div>
<?php endwhile; ?>