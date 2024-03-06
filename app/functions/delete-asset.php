<?php

if(isset($_GET['deleteid'])) {
    $id = $_GET['deleteid'];

    $sql = "DELETE FROM assets WHERE asset_id=$id";
    $result = mysqli_query($conn, $sql);
}

?>