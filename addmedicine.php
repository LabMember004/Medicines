<?php
// check if session_start enabled if not enable it
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a Medicine</title>
    <link rel="stylesheet" href="./assets/css/bootstrap.css">
    <link rel="stylesheet" href="./assets/css/style.css">
    <script src="./assets/js/active.js"></script>
</head>

<body>
    <div class="d-flex align-items-center justify-content-center h-50">
        <div class="row align-self-center">
            <div class="card profile">
                <form action="add-medicine.php" method="post" id="form">
                    <h1 class="text-center">Add a Medicine</h1>
                    <div class="mt-4">
                        <div class="mb-3 ">
                            <label for="name" class="mb-1">Name</label>
                            <input type="text" required name="name" id="name" class="form-control" value="">
                            <div class="invalid-feedback">
                                Please enter a valid name.
                            </div>
                        </div>
                        <div class="mb-3 ">
                            <label for="name" class="mb-1">Price in IQD</label>
                            <input type="text" required name="price" id="price" class="form-control" value="">
                            <div class="invalid-feedback">
                                Please specify price.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="validationTextarea" class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="textarea" placeholder="Job Description"></textarea>
                            <div class="invalid-feedback">
                                Please describe your Medicine
                            </div>
                        </div>
                        <div class="mb-3 d-flex justify-content-center">
                            <input type="submit" name="submit" class="btn btn-primary" value="Add" onclick="textarea()">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>

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