<?php

if(isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM assets WHERE asset_id=$id";
    $result = mysqli_query($conn, $sql);
}

?>