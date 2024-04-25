<?php
// check if session_start enabled if not enable it
if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }
require_once "./includes/connection.php";



// initializing default page number and number of jobs for each page
$page_number = 1;
// check if page number is set in the URL
if (isset($_GET['page']) && !empty($_GET['page'])) {
    $page_number = (int) $_GET['page'];
}
$page_size = 5;
$offset = ($page_number - 1) * $page_size;
// intializing variables
$sql;
$sql2;
$statement;
$statement2;
$result;
$result2;
$total_jobs;
// check for users jobs
$job_list_query = "SELECT * FROM medicine LIMIT $offset, $page_size";
$job_list_statement = mysqli_prepare($connection, $job_list_query);
mysqli_stmt_execute($job_list_statement);
$jobs_res = mysqli_stmt_get_result($job_list_statement);


// query to get total number of jobs in the database
// query
$sql2 = "SELECT count(*) as row_num FROM medicine";
$statement2 = mysqli_prepare($connection, $sql2);
mysqli_stmt_execute($statement2);

// result
$result2 = mysqli_stmt_get_result($statement2);
$row = mysqli_fetch_assoc($result2);
$total_jobs = $row['row_num'];

// variable to page informations
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
    <title>My Medicines</title>
    <?php include "./components/head.php"; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css" integrity="sha256-3sPp8BkKUE7QyPSl6VfBByBroQbKxKG7tsusY2mhbVY=" crossorigin="anonymous" />
    <link rel="stylesheet" href="./assets/css/style.css">
    <script src="./assets/js/active.js"></script>

</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-10 mx-auto mb-4">
                <div class="section-title text-center ">
                    <h3 class="top-c-sep">Medicinces</h3>
                </div>
            </div>
        </div>
        <!-- END Nav -->
        <!-- Body -->
        <div class="row">
            <!-- Search -->
            <div class="col-lg-10 mx-auto">
                <div class="career-search mb-60">
                    <!-- END SEARCH -->
                    <!-- JOBS -->
                    <div class="filter-result">
                        <p class="mb-30 ff-montserrat">Total medicines : <?php echo $total_jobs; ?></p>
                        <?php
                        while ($row = mysqli_fetch_assoc($jobs_res)) {
                            $job_id = $row['id'];
                            $title = $row['name'];
                            $description = $row['description'];
                            trim($title);
                            $word_num = str_word_count($title);
                            $head = mb_substr($title, 0, 1) . substr($title, -1);
                            if ($word_num > 1) {
                                $head = explode(' ', $title);
                                $head = mb_substr($head[0], 0, 1) . mb_substr($head[1], 0, 1);
                            }
                            $salary = $row['price'];
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
                                                        <i class="zmdi zmdi-pin mr-2"></i> $description
                                                    </li>
                                                    <li class="mr-md-4">
                                                        <i class="zmdi zmdi-money mr-2"></i> $salary
                                                    </li>
                                                </ul>
                                            </div>
                                            </div class="d-md-flex">
                                                    <div class="job-right my-4 flex-shrink-0">
                                                        <a href="editMedicine.php?id=$job_id" class="btn d-block w-100 d-sm-inline-block btn-light">Edit Medicine</a>
                                                    </div>
                                                    <div class="job-right my-4 flex-shrink-0">
                                                        <a href="deleteMedicine.php?id=$job_id" class="btn d-block w-100 d-sm-inline-block btn-red">Delete Medicine</a>
                                                    </div>
                                            </div>
                                            EOT;
                            echo $DATA;
                        }
                        ?>
                    </div>
                    <!-- END JOBS -->
                </div>
                <!-- END Body -->
                <!-- Pagination -->
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">
                        <li class="page-item"><a class="page-link" href="<?php echo "myMedicine.php?page=1";
                                                                            echo isset($_GET['search']) && !empty($_GET['search']) ? "&search=" . $_GET['search'] : ""; ?>">First Page</a></li>
                        <li class="page-item <?php echo ($page_number === 1) ? "disabled" : ""; ?>"><a class="page-link" href="<?php echo "myMedicine.php?page=$previous_page";
                                                                                                                                echo isset($_GET['search']) && !empty($_GET['search']) ? "&search=" . $_GET['search'] : ""; ?>">Previous</a></li>
                        <?php
                        // if we have more than 5 pages
                        if ($total_pages >= 5) {
                            // if we are in the first 5 pages
                            if ($page_number <= 3) {
                                for ($i = 2; $i <= 5; $i++) {
                                    $res =  isset($_GET['search']) && !empty($_GET['search']) ? "&search=" . $_GET['search'] : "";
                                    // if search query is not empty append search=search_query to the url
                                    if (!empty($res)) {
                                        echo "<li class='page-item'><a class='page-link' href='myMedicine.php?page=$i&search=$res'>$i</a></li>";
                                    } else {
                                        echo "<li class='page-item'><a class='page-link' href='myMedicine.php?page=$i'>$i</a></li>";
                                    }
                                }
                            }
                            // if we are in the last 5 pages
                            else if ($page_number >= $total_pages - 2) {
                                for ($i = $total_pages - 4; $i <= $total_pages; $i++) {
                                    $res =  isset($_GET['search']) && !empty($_GET['search']) ? "&search=" . $_GET['search'] : "";
                                    // if search query is not empty append search=search_query to the url
                                    if (!empty($res)) {
                                        echo "<li class='page-item'><a class='page-link' href='myMedicine.php?page=$i&search=$res'>$i</a></li>";
                                    } else {
                                        echo "<li class='page-item'><a class='page-link' href='myMedicine.php?page=$i'>$i</a></li>";
                                    }
                                }
                            }
                            // if we are in the middle
                            else {
                                for ($i = $page_number - 2; $i <= $page_number + 2; $i++) {
                                    $res =  isset($_GET['search']) && !empty($_GET['search']) ? "&search=" . $_GET['search'] : "";
                                    // if search query is not empty append search=search_query to the url
                                    if (!empty($res)) {
                                        echo "<li class='page-item'><a class='page-link' href='myMedicine.php?page=$i&search=$res'>$i</a></li>";
                                    } else {
                                        echo "<li class='page-item'><a class='page-link' href='myMedicine.php?page=$i'>$i</a></li>";
                                    }
                                }
                            }
                        } else {
                            for ($i = 1; $i <= $total_pages; $i++) {
                                $res =  isset($_GET['search']) && !empty($_GET['search']) ? "&search=" . $_GET['search'] : "";
                                // if search query is not empty append search=search_query to the url
                                if (!empty($res)) {
                                    echo "<li class='page-item'><a class='page-link' href='myMedicine.php?page=$i&search=$res'>$i</a></li>";
                                } else {
                                    echo "<li class='page-item'><a class='page-link' href='myMedicine.php?page=$i'>$i</a></li>";
                                }
                            }
                        }
                        ?>
                        <li class="page-item <?php echo ($page_number == $total_pages) ? "disabled" : ""; ?>"><a class="page-link" href="<?php echo "myMedicine.php?page=$next_page";
                                                                                                                                            echo isset($_GET['search']) && !empty($_GET['search']) ?
                                                                                                                                                "&search=" . $_GET['search'] : ""; ?>">Next</a></li>
                        <li class="page-item"><a class="page-link" href="<?php echo "myMedicine.php?page=$total_pages";
                                                                            echo isset($_GET['search']) && !empty($_GET['search']) ?
                                                                                "&search=" . $_GET['search'] : ""; ?>">Last Page</a></li>
                    </ul>
                </nav>
                <!-- END Pagination -->
            </div>
        </div>
    </div>
    <script>
        // Add active to the current page in Pagination
        window.onload = function() {
            let params = (new URL(document.location)).searchParams;
            let name = params.get("page");
            if (name) {
                const findButton = document.querySelector('[href="myMedicine.php?page=' + name + '"]');
                findButton.parentElement.classList.add('active');
            } else {
                document.querySelectorAll('[href="index.php?page=1"]')[0].parentElement.classList.add('active');
            }
        }
    </script>
</body>

</html>