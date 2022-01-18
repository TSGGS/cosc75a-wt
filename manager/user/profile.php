<?php
    require("../../includes/db.php");
    session_start();

    $sql = "SELECT employees.*, credentials.credential_last_login, teams.team_name FROM employees INNER JOIN credentials ON employee_id=credentials.credential_employee_id INNER JOIN teams ON teams.team_id=employee_team_id WHERE employee_id=?";
    $employee = prepareSQL($conn, $sql, "i", $_SESSION["eid"]);
    $row = mysqli_fetch_array($employee);
?>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="../../css/style.css">
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
        <script type="text/javascript" src="../../js/script.js"></script>
        <title>Marketing - WT</title>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark background-black">
            <div class="container-fluid">
                <a href="../admin/dashboard.php"><img src="../../images/resources/logo.jpg" class="dashboard-logo"></a>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                </div>
                <ul class="navbar-nav mb-2 mb-lg-0 mr-3 d-flex">
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="../admin/dashboard.php"><strong>Dashboard</strong></a></li>
                </ul>
            </div>
        </nav>    
        <div class="container mt-3">
            <h1>PROFILE</h1>
            <div class="row mt-4">
                <div class="col-3 border-hidden"><h3 class="align-bottom">Employee ID</h3></div>
                <div class="col border"><h3><?php echo $row["employee_id"] ?></h3></div>
            </div>
            <div class="row mt-4">
                <div class="col-3 border-hidden"><h3 class="align-bottom">Name</h3></div>
                <div class="col border"><h3><?php echo $row["employee_lastname"].", ".$row["employee_firstname"] ?></h3></div>
            </div>
            <div class="row mt-4">
                <div class="col-3 border-hidden"><h3 class="align-bottom">Mobile Number</h3></div>
                <div class="col border"><h3><?php echo $row["employee_mobile_number"] ?></h3></div>
            </div>
            <div class="row mt-4">
                <div class="col-3 border-hidden"><h3 class="align-bottom">Email Address</h3></div>
                <div class="col border"><h3><?php echo $row["employee_email_address"] ?></h3></div>
            </div>
            <div class="row mt-4">
                <div class="col-3 border-hidden"><h3 class="align-bottom">Team</h3></div>
                <div class="col border"><h3><?php echo $row["team_name"] ?></h3></div>
            </div>
            <div class="row mt-4">
                <div class="col-3 border-hidden"><h3 class="align-bottom">Last Login</h3></div>
                <div class="col border"><h3><?php echo $row["credential_last_login"] ?></h3></div>
            </div>
    </body>
</html>