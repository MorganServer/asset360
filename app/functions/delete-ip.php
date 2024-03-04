<?php

if(isset($_GET['deleteip'])) {
    $id = $_GET['deleteip'];

    $sql = "DELETE FROM ip_address WHERE ip_id=$id";
    $result = mysqli_query($conn, $sql);
}

?>