<?php
    require("../../includes/helper.php");
    require("../../includes/db.php");

    changeTitle("../templates/header.php", "Support - WT");
    validateUserPage($_SESSION["tid"], $_SERVER["REQUEST_URI"]);

    if(!isset($fname)) {
        $fname = "";
    }

    if(!isset($lname)) {
        $lname = "";
    }

    if(!isset($mobile)) {
        $mobile = "+63";
    }

    if(!isset($email)) {
        $email = "";
    }

?>
    <div class="container mt-3">
        <h1>Add Employee</h1>
        <form action="addemployee.php" method="POST" enctype="multipart/form-data" autocomplete="off">
            <div class="row">
                <div class="col-4 col-title-large">
                    <label class="col-form-label" for="new-employee-fname"><h4>Employee First Name</h4></label>
                </div>
                <div class="col">
                    <div class="row">
                        <input type="text" class="form-control" name="new-employee-fname" id="new-employee-fname" value="<?php echo $fname; ?>" autofocus required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-4 col-title-large">
                    <label class="col-form-label" for="new-employee-lname"><h4>Employee Last Name</h4></label>
                </div>
                <div class="col">
                    <div class="row">
                        <input type="text" class="form-control" name="new-employee-lname" id="new-employee-lname" id="new-employee-fname" value="<?php echo $lname; ?>" required>
                    </div>
                    <div class="row mb-2 d-none error" id="new-employee-name-error">
                        Invalid Name
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-4 col-title-large">
                    <label class="col-form-label" for="new-employee-mobile"><h4>Employee Mobile No</h4></label>
                </div>
                <div class="col">
                    <div class="row">
                        <input type="text" class="form-control" name="new-employee-mobile" id="new-employee-mobile"  id="new-employee-fname" value="<?php echo $mobile; ?>" required>
                    </div>
                    <div class="row mb-2 d-none error" id="new-employee-mobile-error">
                        Invalid Mobile Number
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-4 col-title-large">
                    <label class="col-form-label" for="new-employee-email"><h4>Employee Email</h4></label>
                </div>
                <div class="col">
                    <div class="row">
                        <input type="text" class="form-control" name="new-employee-email" id="new-employee-email"  id="new-employee-fname" value="<?php echo $email; ?>" required>
                    </div>
                    <div class="row mb-2 d-none error" id="new-employee-email-error">
                        Invalid Email Address
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-4 col-title-large">
                    <label class="col-form-label" for="new-employee-team"><h4>Employee Team</h4></label>
                </div>
                <div class="col">
                    <div class="row">
                        <select class="form-select" name="new-employee-team" id="new-employee-team" required>
                                <option selected disabled>--SELECT TEAM--</option>
                                <?php
                                    $sql = "SELECT * FROM teams";
                                    $result = prepareSQL($conn, $sql);
                                    while($rowResult = mysqli_fetch_array($result)) {
                                        echo '<option value="'.$rowResult["team_id"].'">'.$rowResult["team_name"].'</option>';
                                    }
                                ?>
                        </select>
                    </div>
                    <div class="row mb-2 d-none error" id="new-employee-team-error">
                        Invalid Team
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-4 col-title-large">
                    <label class="col-form-label" for="new-employee-password"><h4>Temporary Password</h4></label>
                </div>
                <div class="col">
                    <div class="row">
                            <input type="password" class="form-control" name="new-employee-password" id="new-employee-password" required>
                        </div>
                    <div class="row mb-2 d-none error" id="new-employee-password-error">
                        Invalid Password
                    </div>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col pe-0 inline-right">
                        <button type="submit" class="btn btn-light" name="new-employee"><strong>Add Employee</strong></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
<?php
    if(isset($_POST["new-employee"])) {
        $fname = $_POST["new-employee-fname"];
        $lname = $_POST["new-employee-lname"];
        $mobile = $_POST["new-employee-mobile"];
        $email = $_POST["new-employee-email"];
        $team = $_POST["new-employee-team"];
        $password = $_POST["new-employee-password"];

        if(!(validateName($fname) && validateName($lname))) {
            echo '
                <script>
                    toggleError("new-employee-name-error", "show");
                </script>
            ';
            $error1 = true;
        } else {
            echo '
                <script>
                    toggleError("new-employee-name-error", "hide");
                </script>
            ';
            $error1 = false;
        }

        
        if(!validateMobile($mobile)) {
            echo '
                <script>
                    toggleError("new-employee-mobile-error", "show");
                </script>
            ';
            $error2 = true;
        } else {
            echo '
                <script>
                    toggleError("new-employee-mobile-error", "hide");
                </script>
            ';
            $error2 = false;
        }

        $sql = "SELECT employee_email_address FROM employees WHERE employee_email_address=?";
        $result = prepareSQL($conn, $sql, "s", $email);
        if(!validateEmail($email) || mysqli_num_rows($result) > 0) {
            echo '
                <script>
                    toggleError("new-employee-email-error", "show");
                </script>
            ';
            $error3 = true;
        } else {
            echo '
                <script>
                    toggleError("new-employee-email-error", "hide");
                </script>
            ';
            $error3 = false;
        }

        if(empty($_POST["new-employee-team"])) {
            echo '
                <script>
                    toggleError("new-employee-team-error", "show");
                </script>
            ';
            $error4 = true;
        } else {
            echo '
                <script>
                    toggleError("new-employee-team-error", "hide");
                </script>
            ';
            $error4 = false;
        }
        
        if(!validatePassword($password)) {
            echo '
                <script>
                    toggleError("new-employee-password-error", "show");
                </script>
            ';
            $error5 = true;
        } else {
            echo '
                <script>
                    toggleError("new-employee-password-error", "hide");
                </script>
            ';
            $error5 = false;
        }

        if(!($error1 || $error2 || $error3 || $error4 || $error5)) {
            $sql = "INSERT INTO employees VALUES (NULL, ?, ?, ?, ?, ?, NULL, NULL)";
            prepareSQL($conn, $sql, "ssssi", $fname, $lname, $mobile, $email, $team);

            $sql = "SELECT employee_id FROM employees WHERE employee_email_address=?";
            $eidRes = prepareSQL($conn, $sql, "s", $email);
            $employeeID = mysqli_fetch_array($eidRes);

            $sql = "INSERT INTO credentials VALUES (NULL, ?, ?, NULL)";
            prepareSQL($conn, $sql, "is", $employeeID["employee_id"], password_hash($password, PASSWORD_DEFAULT));

            echo '
                <script>
                    window.location.replace("dashboard.php");
                </script>
            ';

        } else {
            echo '
                <script>
                    document.getElementById("new-employee-fname").value = '.json_encode($fname).';
                    document.getElementById("new-employee-lname").value = '.json_encode($lname).';
                    document.getElementById("new-employee-mobile").value = '.json_encode($mobile).';
                    document.getElementById("new-employee-email").value = '.json_encode($email).';
                </script>
            ';
        }



        // if(!preg_match("/^\+?\d{12}$/", $mobile)) {
        //     echo '
        //         <script>
        //             toggleError(new-employee-mobile-error, show);
        //         </script>
        //     ';
        //     $error1 = true;
        // }else {
        //     echo '
        //         <script>
        //             toggleError(new-employee-mobile-error, hide);
        //         </script>
        //     ';
        //     $error1 = false;
        // }

        // $error2 = validatePassword($password);
        
        // if ($error1 == false && $error2 == true && filter_var($email, FILTER_VALIDATE_EMAIL) != false) 
        // {
        //     $sql = "INSERT INTO employees (employee_firstname, employee_lastname, employee_team_id)
        //     VALUES ('".$fname."', '".$lname."', '".$team."')";

        //     if ($conn->query($sql) === TRUE) {

        //         $sql1 = "SELECT MAX(employee_id) as max_id FROM employees";
        //         $result = prepareSQL($conn, $sql1);
        //         while($resultRow = mysqli_fetch_array($result)) {
        //             $maxid = $resultRow["max_id"];
                
        //             $sql = "INSERT INTO credentials (credential_employee_id, credential_password)
        //             VALUES ('".$maxid."', '". password_hash($password,PASSWORD_DEFAULT)."')";
        


        //             if ($conn->query($sql) === TRUE) {
        //                 echo '
        //                     <script>
        //                         window.location.replace("dashboard.php");
        //                     </script>
        //                 ';
        //             } else {
        //                 echo "Error: " . $sql . "<br>" . $conn->error;
        //             }
        //         }

        //     } else {
        //         echo "Error: " . $sql . "<br>" . $conn->error;
        //     }

        // } else {
        //     echo "<script> alert('ERROR: USER WAS NOT ADDED'); </script>"; 
        // }
    }


