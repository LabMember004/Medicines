<?php
session_start();

if (isset($_POST['name']) && isset($_POST['price']) && isset($_POST['description'])) {
    if (!isset($connection)) {
        require_once "./includes/connection.php";
    }


    // inserting into jobs table
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];;


    $sql = "INSERT INTO medicine (name, price, description) VALUES (?,?,?)";
    $prepare = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($prepare, "sss", $name, $price, $description);
    mysqli_stmt_execute($prepare);
    $job_id = mysqli_insert_id($connection);

    echo "Added Successfully";


    // header("Location: ../company/addjobs.php?state=Posted successfully");
}
