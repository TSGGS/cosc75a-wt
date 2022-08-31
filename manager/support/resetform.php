<?php
    require("../../includes/helper.php");
    require("../../includes/db.php");

    changeTitle("../templates/header.php", "Support - WT");
    validateUserPage($_SESSION["tid"], $_SERVER["REQUEST_URI"]);
?>
<div class="container mt-3">
    <h1>Reset Password</h1>
    <form class="mt-4" action="./resetform.php" method="POST" enctype="multipart/form-data" autocomplete="off">
            <div class="row my-2">
                <div class="col-3 col-title">
                    <label class="col-form-label" for="password"><h4>Password</h4></label>
                </div>
                <div class="col-9 col-input">
                    <div class="row">
                        <input type="password" class="form-control" name="password" id="password" autofocus required>
                    </div>
                    <div class="row mb-2 d-none error" id="password-error">
                        Invalid Password
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-3 col-title">
                    <label class="col-form-label" for="confirm-password"><h4>Confirm Password</h4></label>
                </div>
                <div class="col-9 col-input">
                    <div class="row">
                        <input type="password" class="form-control" name="confirm-password" id="confirm-password" autofocus required>
                    </div>
                    <div class="row mb-2 d-none error" id="confirm-password-error">
                        Passwords do not match
                    </div>
                </div>
            </div>
            
            <div class="row my-3">
                <div class="col pe-0 inline-right">
                    <button type="submit" class="btn btn-light" name="reset-password" value=<?= $_GET["id"] != NULL ? $_GET["id"] : "" ?>><strong>Reset Password</strong></button>
                </div>
            </div>
        </form>

<?php
    if(isset($_POST["reset-password"])) {
        $pass1 = $_POST["password"];
        $pass2 = $_POST["confirm-password"];

        $error1 = validatePassword($pass1);
        $error2 = false;
        $error3 = false;

        if(!$error1) {
            echo '
                <script>
                    toggleError("password-error", "show");
                </script>
            ';

            $error1 = true;
        } else {
            echo '
                <script>
                    toggleError("password-error", "hide");
                </script>
            ';

            $error1 = false;
        }
        
        if($pass1 != $pass2) {
            echo '
                <script>
                    toggleError("confirm-password-error", "show");
                </script>
            ';

            $error2 = true;
        } else {
            echo '
                <script>
                    toggleError("confirm-password-error", "hide");
                </script>
            ';

            $error2 = false;
        }

        $sql = "SELECT IF(EXISTS(SELECT employee_id FROM employees WHERE employee_id=?), true, false) AS result";
        $exist = prepareSQL($conn, $sql, "s", $_POST["reset-password"]);
        $result = mysqli_fetch_array($exist);

        if(!$result["result"]) {
            echo '
                <script>
                    alert("Employee ID does not exist");
                    window.location.replace("./resetpassword.php");
                </script>
            ';

            $error3 = true;
        } else {
            $error3 = false;
        }

        if(!$error1 && !$error2 && !$error3) {
            $sql = "INSERT INTO password_reset VALUES (NULL, ?, ?, NULL, ?, 1)";
            prepareSQL($conn, $sql, "isi", $_POST["reset-password"], password_hash($pass2, PASSWORD_DEFAULT), $_SESSION["eid"]);

            $sql = "UPDATE credentials SET credential_password=? WHERE credential_employee_id=?";
                prepareSQL($conn, $sql, "si", password_hash($pass2, PASSWORD_DEFAULT), $_POST["reset-password"]);

            echo '
                <script>
                    window.location.replace("./resetpassword.php");
                </script>
            ';
        } else {
            echo '
                <script>
                    document.getElementById("reset-password").value = '.$_POST["reset-password"].'
                </script>
            ';
        }
    }