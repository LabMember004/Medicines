<?php
require_once "./includes/connection.php";
session_start();
$_SESSION['email']="";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Title -->
  <title>Job App: Sign up</title>
  <?php include "./components/head.php"; ?>
  <link rel="stylesheet" href="./assets/css/bootstrap.css">
  <link rel="stylesheet" href="./assets/css/style.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="./assets/css/footer.css">
</head>
<body>
  <?php
  $regName = '/^[a-zA-Z].{5,19}$/';
  $regEmail = '/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/';
  $regPass = '/^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{7,15}$/';
  $regAge = '/^(1[89]|[2-9]\d)$/';
  $username = $password = $confirm_password = $age = $location = $comp_logo = $company_name = $email = "";
  $uname_err = $pass_err = $conPass_err = $age_err = $location_err = $logo_err = $cname_err = $email_err = "";
  $uname_err1 = $pass_err1 = $conPass_err1 = $age_err1 = $location_err1 = $logo_err1 = $cname_err1 = $email_err1 = "";
  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["reg"])) {
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['conPass']) && isset($_POST['age']) && !empty($_POST['age'])) {
      $username = $_POST['username'];
      $password = $_POST['password'];
      $confirm_password = $_POST['conPass'];
      $age = $_POST['age'];
      $email = $_POST['email'];
      if (empty($username)) {
        $uname_err = "Please enter a username";
      } elseif (!preg_match($regName, $username)) {
        $uname_err = "Enter a username with a minimum length of 6";
      } else {
        $sql = "SELECT id FROM users WHERE username=?";
        if ($stmt = mysqli_prepare($connection, $sql)) {
          mysqli_stmt_bind_param($stmt, "s", $p_username);
          $p_username = $username;
          if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) == 1) {
              $uname_err = "Username already exists";
            } else {
              $username = trim($username);
            }
          }
          mysqli_stmt_close($stmt);
        }
      }
      if (empty($password)) {
        $pass_err = "Please enter a password";
      } elseif (!preg_match($regPass, $password)) {
        $pass_err = "Enter a passowrd with a minimum length of 8 and it must contain an upper case letter,a number and a special character";
      } else {
        $password = trim($password);
      }
      if (empty($confirm_password)) {
        $conPass_err = "Please confirm your password";
      } else {
        $confirm_password = trim($confirm_password);
        if ($password != $confirm_password) {
          $conPass_err = "Password didn't match";
        }
      }
      if (empty($email)) {
        $email_err = "Please enter your email";
      } elseif (!preg_match($regEmail, $email)) {
        $email_err = "Please enter a valid email";
      } 
      else {
        $sql = "SELECT id FROM users WHERE email=?";
        if ($stmt = mysqli_prepare($connection, $sql)) {
          mysqli_stmt_bind_param($stmt, "s", $p_email);
          $p_email = $email;
          if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) == 1) {
              $email_err = "Email already exists";
            } else {
              $email = trim($email);
            }
          }
          mysqli_stmt_close($stmt);
        }
      }
      if (empty($age)) {
        $age_err = "Enter your age";
      } elseif (!preg_match($regAge, $age)) {
        $age_err = "Enter an age that is greater than 17";
      }
      if (empty($uname_err) && empty($email_err) && empty($pass_err) && empty($conPass_err) && empty($age_err)) {
        $sql = "INSERT INTO users (username,password,email,age) VALUES (?,?,?,?)";
        if ($stmt = mysqli_prepare($connection, $sql)) {
          mysqli_stmt_bind_param($stmt, "sssi", $p_username, $p_password, $p_email, $_age);
          $p_username = $username;
          $p_password = hash('sha512', $password);
          $p_email = $email;
          $_age = $age;
          $_SESSION['email']=$email;
          if (mysqli_stmt_execute($stmt)) {
            header("location: signin.php");
          } else {
            echo "something went wrong";
          }
          mysqli_stmt_close($stmt);
        }
      }
    }
    //company database connection
    elseif (isset($_POST['username']) && isset($_POST['company_name']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['conPass']) && isset($_POST['logo']) && isset($_POST['location'])) {
      $username = $_POST['username'];
      $company_name = $_POST['company_name'];
      $email = $_POST['email'];
      $password = $_POST['password'];
      $confirm_password = $_POST['conPass'];
      $location = $_POST['location'];
      $comp_logo = $_POST['logo'];
      if (empty($username)) {
        $uname_err1 = "Please enter a username";
      } elseif (!preg_match($regName, $username)) {
        $uname_err1 = "Enter a username with a minimum length of 6";
      } else {
        $sql = "SELECT id FROM company WHERE username=?";
        if ($stmt = mysqli_prepare($connection, $sql)) {
          mysqli_stmt_bind_param($stmt, "s", $p_username);
          $p_username = $username;
          if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) == 1) {
              $uname_err1 = "Username already exists";
            } else {
              $username = trim($username);
            }
          }
          mysqli_stmt_close($stmt);
        }
      }
      if (empty($password)) {
        $pass_err1 = "Please enter a password";
      } elseif (!preg_match($regPass, $password)) {
        $pass_err1 = "Enter a passowrd with a minimum length of 8 and it must contain an upper case letter,a number and a special character";
      } else {
        $password = trim($password);
      }
      if (empty($confirm_password)) {
        $conPass_err1 = "Please confirm your password";
      } else {
        $confirm_password = trim($confirm_password);
        if ($password != $confirm_password) {
          $conPass_err1 = "Password didn't match";
        }
      }
      if (empty($email)) {
        $email_err1 = "Please enter your email";
      } elseif (!preg_match($regEmail, $email)) {
        $email_err1 = "Please enter a valid email";
      } else { 
        $sql = "SELECT id FROM company WHERE email=?";
        if ($stmt = mysqli_prepare($connection, $sql)) {
          mysqli_stmt_bind_param($stmt, "s", $p_email);
          $p_email = $email;
          if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) == 1) {
              $email_err = "Email already exists";
            } else {
              $email = trim($email);
            }
          }
          mysqli_stmt_close($stmt);
        }
      }
      if (empty($location)) {
        $location_err1 = "Enter company location";
      }
      if (empty($company_name)) {
        $cname_err1 = "Pleade enter company name";
      }
      if (empty($comp_logo)) {
        $logo_err1 = "Please choose company logo";
      }
      if (empty($uname_err1) && empty($cname_err1) && empty($email_err1) && empty($pass_err1) && empty($conPass_err1) && empty($location_err1) && empty($logo_err1)) {
        $sql = "INSERT INTO company (username,password,email,company_name,location) VALUES (?,?,?,?,?)";
        if ($stmt = mysqli_prepare($connection, $sql)) {
          $_SESSION['username']=$username;
          mysqli_stmt_bind_param($stmt, "sssss", $p_username, $p_password, $p_email, $p_company_name, $_location);
          $p_username = $username;
          $p_password = hash('sha512', $password);
          $p_email = $email;
          $p_company_name = $company_name;
          $_location = $location;
          if (mysqli_stmt_execute($stmt)) {
            header("location:signin.php");
          } else {
            echo "something went wrong";
          }
          mysqli_stmt_close($stmt);
        }
      }
    }
    mysqli_close($connection);
  }
  ?>
  <?php include "./components/header.php"?>
  <div class="container h-100 " style="margin-top: 10vh; margin-bottom: 20vh;">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-lg-12 col-xl-11">
        <div class="card text-black" style="border-radius: 25px;">
          <div class="card-body p-md-5">
            <div class="row justify-content-center">
              <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">
                <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Sign up</p>
                <form class="mx-1 mx-md-4" method="post" action="" id="Signup">
                  <button type="button" class="btn btn-primary btn-m " onclick="userForm()" id="primaryBtn">Looking for a job</button>
                  <button type="button" class="btn btn-secondary btn-m " onclick="compForm()" id="seconfstyBtn">Want to hire experts</button>
                  <div class="d-flex flex-row align-items-center mb-4">
                    <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                    <div class="form-outline flex-fill mb-0">
                      <label class="form-label" for="uname">Username</label>
                      <input type="text" id="uname" class="form-control" name="username" value="<?php echo $username;?>"/>
                      <div id="error"><?php echo $uname_err;
                                      echo $uname_err1; ?></div>
                    </div>
                  </div>
                  <div id="compName">
                    <div>
                      <div class="d-flex flex-row align-items-center mb-4">
                        <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                        <div class="form-outline flex-fill mb-0">
                          <label class="form-label" for="cname">Company name</label>
                          <input type="text" id="cname" class="form-control" name="company_name" value="<?php echo $company_name;?>"/>
                          <div id="error"><?php echo $cname_err;
                                          echo $cname_err1; ?></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="d-flex flex-row align-items-center mb-4">
                    <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                    <div class="form-outline flex-fill mb-0">
                      <label class="form-label" for="email">Email</label>
                      <input type="email" id="email" class="form-control" name="email" value="<?php echo $email;?>"/>
                      <div id="error"><?php echo $email_err;
                                      echo $email_err1;  ?></div>
                    </div>
                  </div>
                  <div class="d-flex flex-row align-items-center mb-4">
                    <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                    <div class="form-outline flex-fill mb-0">
                      <label class="form-label" for="password">Password</label>
                      <input type="password" id="password" class="form-control" name="password" />
                      <div id="error"><?php echo $pass_err;
                                      echo $pass_err1; ?></div>
                    </div>
                  </div>
                  <div class="d-flex flex-row align-items-center mb-4">
                    <i class="fas fa-key fa-lg me-3 fa-fw"></i>
                    <div class="form-outline flex-fill mb-0">
                      <label class="form-label" for="confirmPassword">Confirm password</label>
                      <input type="password" id="confirmPassword" class="form-control" name="conPass" />
                      <div id="error"><?php echo $conPass_err;
                                      echo $conPass_err1; ?></div>
                    </div>
                  </div>
                  <div id="gen">
                    <div class="d-flex flex-row align-items-center mb-4">
                      <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                      <div class="form-outline flex-fill mb-0">
                        <label class="form-label" for="gender">Gender</label>
                        <select class="form-select " id="gender">
                          <option value="female">Female</option>
                          <option value="male">Male</option>
                          <option value="others">Others</option>
                        </select>
                      </div>
                    </div>
                    <div class="d-flex flex-row align-items-center mb-4">
                      <i class="fas fa-key fa-lg me-3 fa-fw"></i>
                      <div class="form-outline flex-fill mb-0">
                        <label class="form-label" for="age">Age</label>
                        <input type="text" id="age" class="form-control" name="age" value="<?php echo $age;?>" />
                        <div id="error"><?php echo $age_err; $age_err1;   ?></div>

                      </div>
                    </div>  
                  </div>
                  <div id="companyLocation">
                    <div class="d-flex flex-row align-items-center mb-4">
                      <i class="fas fa-key fa-lg me-3 fa-fw"></i>
                      <div class="form-outline flex-fill mb-0">
                        <label class="form-label" for="location">Location</label>
                        <input type="text" id="location" class="form-control" name="location" value="<?php echo $location;?>"/>
                        <div id="error"><?php echo $location_err;
                                        echo $location_err1; ?></div>
                      </div>
                    </div>
                    <div class="d-flex flex-row align-items-center mb-4">
                      <i class="fas fa-key fa-lg me-3 fa-fw"></i>
                      <div class="form-outline flex-fill mb-0">
                        <label class="form-label" for="companyLogo">Company label</label>
                        <input type="file" class="form-control" id="companyLogo" name="logo" />
                        <div id="error"><?php echo $logo_err;
                                        echo $logo_err1; ?></div>

                      </div>
                    </div>
                  </div>
                  <div class="form-check d-flex justify-content-center mb-5">
                    <input class="form-check-input me-2" type="checkbox" value="" id="form2Example3c" />
                    <label class="form-check-label" for="form2Example3">
                      I agree all statements in <a href="#!">Terms of service</a>
                    </label>
                  </div>

                  <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                    <button type="submit" class="btn btn-primary btn-lg" name="reg">Register</button>
                  </div>

                </form>
              </div>
              <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2">

                <img src="./assets/images/undraw_welcome_cats_thqn.svg" class="img-fluid" alt="Sample image">

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
    function compForm() {
      document.getElementById("companyLocation").style.display = "block";
      document.getElementById("compName").style.display = "block";
      document.getElementById("gen").style.display = "none";
      document.getElementById("primaryBtn").style.backgroundColor = "#3F3D56";
      document.getElementById("seconfstyBtn").style.backgroundColor = "#4e5fff";
    }

    function userForm() {
      document.getElementById("gen").style.display = "block";
      document.getElementById("compName").style.display = "none";
      document.getElementById("companyLocation").style.display = "none";
      document.getElementById("seconfstyBtn").style.backgroundColor = "#3F3D56";
      document.getElementById("primaryBtn").style.backgroundColor = "#4e5fff";
    }
  </script>
</body>

</html>