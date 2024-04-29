<?php

if(isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM event_log WHERE event_id=$id";
    $result = mysqli_query($conn, $sql);
}

?>