<?php
// check if session_start enabled if not enable it
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// if job id is empty
if (isset($_GET['id']) && empty($_GET['id'])) {
    die("Invalid Job ID");
}

// get details of job
else if (isset($_GET['id']) && !empty($_GET['id'])) {
    include "./includes/connection.php";
    $id = $_GET['id'];
    // Check if the job is posted by the company
    $sql = "SELECT * FROM medicine WHERE id = ?";
    $statement = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($statement, "i", $id);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    $row = mysqli_fetch_assoc($result);
    // check if job id is valid
    if (mysqli_num_rows($result) < 1) {
        die("Invalid Job ID");
    }
    // Get job details to put into their fields
    $sql = "SELECT * FROM medicine WHERE id = ?";
    $statement = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($statement, "i", $id);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    $row = mysqli_fetch_assoc($result);
    $title = $row['name'];
    $description = $row['description'];
    $salary = $row['price'];
}
if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['price'])) {
    if (!isset($connection)) {
        require_once "./includes/connection.php";
    }

    // Use the POST data directly to update the database
    $title = $_POST['name'];
    $description = $_POST['description'];
    $salary = $_POST['price'];

    $sql = "UPDATE medicine SET name=?, description=?, price=? WHERE id=?";
    $prepare = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($prepare, "ssdi", $title, $description, $salary, $id);
    mysqli_stmt_execute($prepare);
    header("Location: ./myMedicine.php?id=$id&state=Updated successfully&name=$title&description=$description&price=$salary");

}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company: Edit a job</title>
    <?php include "./components/head.php"; ?>
    <link rel="stylesheet" href="./assets/css/bootstrap.css">
    <link rel="stylesheet" href="./assets/css/style.css">
    <script src="./assets/js/active.js"></script>
    <script src="./assets/js/form-validation.js"></script>
    <!--Markdown editor-->
</head>

<body>
    <?php
    // Toast to show when a job has been added successfully
    if (isset($_GET['state'])) {
        $state = $_GET['state'];
        echo "<div class='position-fixed top-0 end-0 p-3' style='z-index: 11111'>
                <div id='liveToast' class='toast' role='alert' aria-live='assertive' aria-atomic='true'>
                  <div class='toast-header'>
                  <img src='./assets/images/door.svg' class='rounded me-2' alt='door'>
                  <strong class='me-auto'>Login</strong>
                  <small>1 seconds ago</small>
                  <button type='button' class='btn-close' data-bs-dismiss='toast' aria-label='Close'></button>
                </div>
                  <div class='toast-body'>
                    $state
                  </div>
                </div>
              </div>";
    }
    ?>
    <div class="d-flex align-items-center justify-content-center h-100">
        <div class="row align-self-center">
            <div class="card profile">
                <form action="" method="post" id="form">
                    <h1 class="text-center">Edit a job</h1>
                    <div class="mt-4">
                        <div class="mb-3">
                            <label for="name" class="mb-1">Name</label>
                            <input type="text" required name="name" id="name" class="form-control" value="<?php echo $title; ?>">
                            <div class="invalid-feedback">
                                Please enter a valid name.
                            </div>
                        </div>
                        <div class="mb-3 ">
                            <label for="name" class="mb-1">Price</label>
                            <input type="text" required name="price" id="price" class="form-control" value="<?php echo $salary; ?>">
                            <div class="invalid-feedback">
                                Please specify Price.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="validationTextarea" class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="textarea" placeholder="Description" value="<?php echo $description; ?>">
                            <?php echo $description; ?>
                            </textarea>
                        </div>
                        <div class="mb-3 d-flex justify-content-center">
                            <input type="submit" name="submit" class="btn btn-primary" value="Update">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        // if logged in successfully send user to index.php
        let params = (new URL(document.location)).searchParams;
        let state = params.get("state");
        if (state == "Posted successfully") {
            setTimeout(() => {
                window.location.href = "./myjobs.php";
            }, 1000);
        }
        // show toast if added a job successfully
        window.onload = () => {
            var toast_container = document.getElementById('liveToast')
            if (toast_container) {
                var toast = new bootstrap.Toast(toast_container)
                toast.show()
            }
        }
    </script>
</body>

</html>