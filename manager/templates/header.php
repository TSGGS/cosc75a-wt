<?php
    error_reporting(0);
    date_default_timezone_set('Asia/Manila');
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="../../css/style.css">
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
        <script type="text/javascript" src="../../js/script.js"></script>
        <title>%TITLE%</title>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark background-black">
            <div class="container-fluid">
                <a href="dashboard.php"><img src="../../images/resources/logo.jpg" class="dashboard-logo"></a>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <?php
                        if($_SESSION["tid"] == 1) {
                            echo '
                            <li class="nav-item dropdown"><a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Product</a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" aria-current="page" href="./new-product.php">New Product</a></li>
                                <li><a class="dropdown-item" aria-current="page" href="./edit-product.php">Update Product</a></li>
                                </ul>
                            </li>

                            <li class="nav-item"><a class="nav-link active" aria-current="page" href="./report.php">Generate Report</a></li>
                            ';
                        } elseif($_SESSION["tid"] == 2) {
                            echo '
                            <li class="nav-item"><a class="nav-link active" aria-current="page" href="./orders.php">View Orders</a></li>
                            <li class="nav-item"><a class="nav-link active" aria-current="page" href="./inventory.php">Update Inventory</a></li>
                            ';
                        } elseif($_SESSION["tid"] == 3) {
                           echo '
                            <li class="nav-item dropdown"><a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Promotion</a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" aria-current="page" href="./new-promotion.php">New Promotion</a></li>
                                    <li><a class="dropdown-item" aria-current="page" href="./edit-promotion.php">Update Promotion</a></li>
                                </ul>
                            </li>

                            <li class="nav-item dropdown"><a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Discount</a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" aria-current="page" href="./new-discount.php">New Discount</a></li>
                                    <li><a class="dropdown-item" aria-current="page" href="#">Update Discount</a></li>

                                </ul>
                            </li>
                            
                            ';
                        } elseif($_SESSION["tid"] == 4) {
                            echo '<ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                    <li class="nav-item dropdown"><a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Manage Employees</a>
                                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                            <li><a class="dropdown-item" href="addemployee.php">Add New Employee</a></li>
                                            <li><a class="dropdown-item" href="removeemployee.php">Remove Employee</a></li>
                                        </ul>
                                    </li>
                                 </ul>
                                <li class="nav-item"><a class="nav-link active" aria-current="page" href="resetpassword.php">Reset Password</a></li>
                            ';
                        } else {
                            header("Location: ../login.php");
                            die();
                        }
                    ?>
                    </div>
                <ul class="navbar-nav mb-2 mb-lg-0 mr-3 d-flex">
                    <li class="nav-item dropdown"><a class="nav-link dropdown-toggle text-white" href="" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"><strong><?php echo $_SESSION["fname"].' '.$_SESSION["lname"]; ?></strong></a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="../user/profile.php">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>