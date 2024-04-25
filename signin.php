<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
// // check if user is already logged in
// if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
//   header("Location: index.php");
// }
// import db connection
require_once "includes/connection.php";

// if user is not logged in, check if user is trying to login
if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['as'])) {
  $user_type = "user";
  $db = "users";
  $state = "Invalid Credentials";
  $email = $_POST['email'];
  $password = $_POST['password'];
  $password = hash('sha512', $password);
  $remember = false;
  // check if user wants to save credentials in browser for a day
  if (isset($_POST['remember'])) {
    $remember = true;
  }
  // check if its a company then user_type = company
  if ($_POST['as'] == "company") {
    $user_type = "company";
    $db = "company";
  }
  header("Location: signin.php?state=$user_type");
  // searching for user in db with email and password and preparing mysql query
  $sql = "SELECT * FROM $db WHERE email= ? AND password=?";
  $statement = mysqli_prepare($connection, $sql);
  mysqli_stmt_bind_param($statement, "ss",  $email, $password);
  mysqli_stmt_execute($statement);
  $result = mysqli_stmt_get_result($statement);
  // if user is found
  if (mysqli_num_rows($result) === 1) {
    $user = mysqli_fetch_assoc($result);
    $_SESSION['id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['logged_in'] = true;
    $_SESSION['user_type'] = $user_type;
    // if user is a normal user include age
    if ($user_type == "user") {
      $_SESSION['age'] = $user['age'];
    }
    // if user is a company include company_name and location
    if ($user_type == "company") {
      $_SESSION['company_name'] = $user['company_name'];
      $_SESSION['location'] = $user['location'];
    }
    // if user wants to save credentials in browser for a day update session_id in db
    if ($remember) {
      $sql = "UPDATE $db SET session_id= ? WHERE email= ?";
      $statement = mysqli_prepare($connection, $sql);
      $session = session_id();
      $email = $_SESSION['email'];
      mysqli_stmt_bind_param($statement, "ss",  $session, $email);
      mysqli_stmt_execute($statement);
      setcookie('id', session_id(), time() + (3600 * 24), "/");
      setcookie('user_type', $user_type, time() + (3600 * 24), "/");
    }
    header("Location: signin.php?state=Logged in successfully");
    die();
  }
  header("Location: signin.php?state=Invalid Credentials");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Title -->
  <title>Job App: Sign In</title>
  <!-- Metadata js and css imports -->
  <?php include "./components/head.php"; ?>
  <link rel="stylesheet" href="./assets/css/bootstrap.css">
  <link rel="stylesheet" href="./assets/css/style.css">
  <script src="./assets/js/form-validation.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="./assets/css/footer.css">
</head>

<body>
  <?php
  // if user is logged in, redirect to show toast then redirect to index.php
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
  <!-- Navbar -->
  <?php include "./components/header.php"; ?>
  <div class="wrapper d-flex justify-content-center align-items-center">
    <div class="content">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <img src="assets/images/login1.svg" alt="Image" class="img-fluid">
          </div>
          <div class="col-md-6 contents mt-lg-5">
            <div class="row justify-content-center mt-lg-5">
              <div class="col-md-8">
                <div class="mb-4">
                  <h3>Sign In</h3>
                  <p class="mb-4">Lorem ipsum dolor sit amet elit. Sapiente sit aut eos consectetur adipisicing.</p>
                </div>
                <form action="" method="post" class="">
                  <div class="form-group first">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" id="email" name="email">

                  </div>
                  <div class="form-group last mb-4">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password">
                  </div>
                  <div class="">

                    <div class="">
                      <label class="">Login as: </label>
                      <div class="ms-5 my-2">
                        <div>
                          <input type="radio" name="as" id="user" value="user" class="form-check-input">
                          <label for="user" class="">User</label>

                        </div>
                        <div>
                          <input type="radio" name="as" id="company" value="company" class="form-check-input">
                          <label for="company">Company</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="d-flex mb-3 justify-content-between">
                    <label class="control mb-0"><span>Remember me</span>
                      <input type="checkbox" class="form-check-input" name="remember" />
                    </label>
                    <span class="ml-auto"><a href="#" class="links link-primary">Forgot Password</a></span>
                  </div>
                  <div class="d-flex justify-content-center mb-3">
                    <input type="submit" value="Log In" class="btn btn-block btn-primary">
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- END Main Content -->
    <!-- Footer -->
    <?php include './components/footer.php'; ?>
  <script>
    window.onload = () => {
      // show toast if login is successful or not
      var toast_container = document.getElementById('liveToast')
      if (toast_container) {
        var toast = new bootstrap.Toast(toast_container)
        toast.show()
      }
      // if logged in successfully send user to index.php
      let params = (new URL(document.location)).searchParams;
      let state = params.get("state");
      if (state == "Logged in successfully") {
        setTimeout(() => {
          window.location.href = "index.php";
        }, 2000);
      }
    }
  </script>
</body>

</html>