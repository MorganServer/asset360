<?php

if(isset($_GET['deleteid'])) {
    $id = $_GET['deleteid'];

    $sql = "DELETE FROM event_log WHERE event_id=$id";
    $result = mysqli_query($conn, $sql);
}

?>