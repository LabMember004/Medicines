<?php

if (isset($_GET['id'])) {
    require_once "./includes/connection.php";
    $sql = "DELETE FROM medicine WHERE id = ?";
    $prepare = mysqli_prepare($connection, $sql);
    $id = $_GET['id'];
    mysqli_stmt_bind_param($prepare, "i", $id);
    mysqli_stmt_execute($prepare);
    
    if (mysqli_affected_rows($connection) > 0) {
        header("Location: ./myMedicine.php?state=Deleted successfully");
    }
}

?>