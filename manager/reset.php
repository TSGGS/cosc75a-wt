<?php
    require("../includes/helper.php");
    require("../includes/db.php");

    changeTitle("../templates/header.php", "Walang Tatak - Reset Password");
?>

        <div class="box-small box-round background-black center">
            <div class="inline-center">
                <img src="../images/resources/logo.jpg" class="logo">
                <h1 class="text-white">RESET PASSWORD</h1>
            </div>
            <form action="./reset.php" method="POST" enctype="multipart/form-data" autocomplete="off">
                <div class="row m-2 mt-4 mb-4">
                    <div class="col-5">
                        <label for="login-id" class="col-form-label text-white"><h4>Employee ID</h4></label>
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" name="reset-id" id="reset-id" autofocus required>
                    </div>
                </div>
                <div class="row m-2 mb-4">
                    <div class="col-5">
                        <label for="reset-pass1" class="col-form-label text-white"><h4>Password</h4></label>
                    </div>
                    <div class="col">
                        <input type="password" class="form-control" name="reset-pass1" id="reset-pass1" required>
                    </div>
                </div>
                <div class="row m-2 mb-4">
                    <div class="col-5">
                        <label for="reset-pass2" class="col-form-label text-white"><h5>Confirm Password</h5></label>
                    </div>
                    <div class="col">
                        <input type="password" class="form-control" name="reset-pass2" id="reset-pass2" required>
                    </div>
                </div>

                <div class="row m-2">
                    <div class="col inline-center">
                        <button type="submit" class="btn btn-light" name="reset-submit"><strong>Reset Password</strong></button>
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>

<?php
    if(isset($_POST["reset-submit"])) {
        if($_POST['reset-pass1'] != $_POST['reset-pass2']) {
            echo "
                <script>
                    alert('Passwords do not match. Please try again.');
                </script>";
        } else {
            $password = password_hash($_POST['reset-pass2'], PASSWORD_DEFAULT);

            $sql = "SELECT c.credential_employee_id AS eid FROM credentials AS c, employees AS e WHERE c.credential_employee_id=e.employee_id AND c.credential_employee_id=?";
            $result = prepareSQL($conn, $sql, "i", $_POST['reset-id']);

            if(mysqli_num_rows($result) < 1) {
                echo "
                    <script>
                        alert('Employee ID not found. Please try again.');
                    </script>";
            } else {
                if(!validatePassword($_POST['reset-pass2'])) {
                    echo "
                        <script>
                            alert('Password did not meet requirements. Please make sure your password contains at least one uppercase, lowercase, number and special characters.');
                        </script>";
                } else {
                    while($row_result = mysqli_fetch_array($result)) {
                        $eid = $row_result['eid'];
    
                        $sql = "INSERT INTO password_reset VALUES (NULL, ?, ?, NULL, NULL, 0)";
                        prepareSQL($conn, $sql, "is", $eid, $password);
    
                        echo "
                        <script>
                            alert('Password reset request was sent. Support team will contact you shortly.');
                        </script>";
    
                        break;
                    }
                }

            }
        }
    }