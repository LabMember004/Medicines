<?php

// imports
include "./includes/connection.php";


// initializing default page number and number of jobs for each page
$page_number = 1;
$page_size = 5;
// check if page number is set in the URL
if (isset($_GET['page']) && !empty($_GET['page'])) {
    $page_number = (int) $_GET['page'];
}
$offset = ($page_number - 1) * $page_size;
// intializing variables
$sql;
$sql2;
$statement;
$statement2;
$result;
$result2;
$total_jobs;

// check if search is set in the URL
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search =  "%" . $_GET['search'] . "%";
    // query to get data from database

    $sql = "SELECT * FROM medicine WHERE (name LIKE LOWER(?) OR description LIKE LOWER(?) ) LIMIT ?, ?";
    $statement = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($statement, "ssii", $search, $search, $offset, $page_size);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    $total_jobs = mysqli_num_rows($result);

    // query to get total number of jobs in the database
    $sql2 = "SELECT count(*) as row_num FROM medicine WHERE (name LIKE LOWER(?) OR description LIKE LOWER(?))";
    $statement2 = mysqli_prepare($connection, $sql2);
    mysqli_stmt_bind_param($statement2, "ss", $search, $search);
    mysqli_stmt_execute($statement2);
    $result2 = mysqli_stmt_get_result($statement2);
    $row = mysqli_fetch_assoc($result2);
    $total_jobs = $row['row_num'];
} else {

    // query to get data from database
    $sql = "SELECT * FROM medicine LIMIT ?, ?";
    $statement = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($statement, "ii", $offset, $page_size);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    $total_jobs = mysqli_num_rows($result);

    // query to get total number of jobs in the database
    $sql2 = "SELECT count(*) as row_num FROM medicine";
    $statement2 = mysqli_prepare($connection, $sql2);
    mysqli_stmt_execute($statement2);
    $result2 = mysqli_stmt_get_result($statement2);
    $row = mysqli_fetch_assoc($result2);
    $total_jobs = $row['row_num'];
}

//  page informations
$previous_page = $page_number - 1;
$next_page = $page_number + 1;
$total_pages = ceil($total_jobs / $page_size);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job App: HOME</title>
    <?php include "./components/head.php"; ?>
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css" integrity="sha256-3sPp8BkKUE7QyPSl6VfBByBroQbKxKG7tsusY2mhbVY=" crossorigin="anonymous" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="./assets/css/footer.css">

</head>

<body >
    <!-- Nav -->
    <?php include "./components/header.php"; ?>
    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-10 mx-auto mb-4">
                <div class="section-title text-center ">
                    <h3 class="top-c-sep">Search for medicine</h3>
                    <p>You can search for any medicine you want.</p>
                </div>
            </div>
        </div>
        <!-- END Nav -->
        <!-- Body -->
        <div class="row">
            <!-- Search -->
            <div class="col-lg-10 mx-auto">
                <div class="career-search mb-60">
                    <form action="" class="career-form mb-60">
                        <div class="row">
                            <div class="col-md-6 col-lg-9 my-3 ">
                                <div class="input-group">
                                    <input name="search" type="text" class="form-control" placeholder="Enter Your Keywords" id="keywords" style="height: 48px;">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3 my-3">
                                <button type="submit" class="btn btn-lg btn-block btn-light btn-custom" id="contact-submit">
                                    Search
                                </button>
                            </div>
                        </div>
                    </form>
                    <!-- END SEARCH -->
                    <!-- JOBS -->
                    <div class="filter-result">
                        
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            $job_id = $row['id'];
                            $title = $row['name'];
                            trim($title);
                            $word_num = str_word_count($title);
                            $head = mb_substr($title, 0, 1) . substr($title, -1);
                            if ($word_num > 1) {
                                $head = explode(' ', $title);
                                $head = mb_substr($head[0], 0, 1) . mb_substr($head[1], 0, 1);
                            }
                            $salary = $row['price'];
                            $location = $row['description'];
                            $DATA = <<<EOT
                                            <div class="job-box d-md-flex align-items-center justify-content-between mb-30">
                                            <div class="job-left my-4 d-md-flex align-items-center flex-wrap">
                                                <div class="img-holder mr-md-4 mb-md-0 mb-4 mx-auto mx-md-0 d-md-none d-lg-flex">
                                                    $head
                                                </div>
                                            <div class="job-content">
                                                <h5 class="text-center text-md-left">$title</h5>
                                                <ul class="d-md-flex flex-wrap text-capitalize ff-open-sans">
                                                    <li class="mr-md-4">
                                                        <i class="zmdi zmdi-pin mr-2"></i> $location
                                                    </li>
                                                    <li class="mr-md-4">
                                                        <i class="zmdi zmdi-money mr-2"></i> $salary
                                                    </li>
                                                </ul>
                                            </div>
                                            </div>
                                                
                                            </div>
                                            EOT;
                            echo $DATA;
                        }
                        ?>
                    </div>
                    <!-- END JOBS -->
                </div>
    <!-- END Main Content -->
    <!-- Footer -->
    
    <script>
        // Add active to the current page in Pagination
        window.onload = function() {
            let params = (new URL(document.location)).searchParams;
            let name = params.get("page");
            if (name) {
                const findButton = document.querySelector('[href="index.php?page=' + name + '"]');
                findButton.parentElement.classList.add('active');
            }
            else{
                document.querySelectorAll('[href="index.php?page=1"]')[0].parentElement.classList.add('active');
            }
        }
    </script>
</body>

</html>