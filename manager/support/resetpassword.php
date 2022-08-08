<?php
    require("../../includes/helper.php");
    require("../../includes/db.php");

    changeTitle("../templates/header.php", "Support - WT");
    validateUserPage($_SESSION["tid"], $_SERVER["REQUEST_URI"]);
?>
    <div class="container mt-3">
    <h1>Change Password</h1>
        <table class="table table-sm table-hover mt-4">
            <thead>
                <tr>
                    <th class="inline-center">First Name</th>
                    <th class="inline-center">Last Name</th>
                    <th class="inline-center">Team</th>
                    <th class="inline-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $sql = "SELECT * FROM employees INNER JOIN teams WHERE employee_team_id = team_id AND employee_account_end IS NULL";
                    $result = prepareSQL($conn, $sql);
                    while($resultRow = mysqli_fetch_array($result)) {
                        echo '
                            <tr>
                                <td class="inline-center">'.$resultRow["employee_firstname"].'</td>
                                <td class="inline-center">'.$resultRow["employee_lastname"].'</td>
                                <td class="inline-center">'.$resultRow["team_name"].'</td>
                                <td class="inline-center"><a href="resetform.php?id='.$resultRow["employee_id"].'">Change</a></td>
                            </tr>
                        ';
                    }
                ?>
            </tbody>
        </table>
        <h1>For Approval</h1>
        <table class="table table-sm table-hover mt-4">
            <thead>
                <tr>
                    <th class="inline-center">First Name</th>
                    <th class="inline-center">Last Name</th>
                    <th class="inline-center">Team</th>
                    <th class="inline-center">Timestamp</th>
                    <th class="inline-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $sql = "SELECT * FROM password_reset INNER JOIN employees AS e INNER JOIN teams AS t WHERE password_reset_employee_id=e.employee_id AND e.employee_team_id=t.team_id AND password_reset_handler IS NULL AND e.employee_account_end IS NULL;";
                    $result = prepareSQL($conn, $sql);
                    while($resultRowApprove = mysqli_fetch_array($result)) {
                        echo '
                            <tr>
                                <td class="inline-center">'.$resultRowApprove["employee_firstname"].'</td>
                                <td class="inline-center">'.$resultRowApprove["employee_lastname"].'</td>
                                <td class="inline-center">'.$resultRowApprove["team_name"].'</td>
                                <td class="inline-center">'.$resultRowApprove["password_reset_timestamp"].'</td>
                                <td class="inline-center"><a href="resetpassword.php?id='.$resultRowApprove["employee_id"].'&type=approval&status=approved">APPROVE</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="Resetpassword.php?id='.$resultRowApprove["employee_id"].'&type=approval&status=rejected">REJECT</a></td>
                            </tr>
                        ';
                    }
                ?>
            </tbody>
        </table>
    </div>
<?php
    if(isset($_GET["id"])) {
        if($_GET["type"] == "change") {
            $sql = "UPDATE employees SET employee_account_end=? WHERE employee_id=?";
            prepareSQL($conn, $sql, "si", date("Y-m-d H:i:s"), $_GET["id"]);

            echo '
                <script>
                    window.location.href("resetpassword.php");
                </script>
            ';
        } elseif($_GET["type"] == "approval") {
            $sql = "SELECT * FROM password_reset INNER JOIN employees AS e INNER JOIN teams AS t WHERE password_reset_employee_id=e.employee_id AND e.employee_team_id=t.team_id AND e.employee_id=? AND password_reset_handler IS NULL AND e.employee_account_end IS NULL;";
            $result = prepareSQL($conn, $sql, "i", $_GET["id"]);
            $resultRowApprove = mysqli_fetch_array($result);

            if($_GET["status"] == "approved") {
                $sql = "UPDATE password_reset SET password_reset_handler=?, password_reset_approved = 1 WHERE password_reset_id=?";
                prepareSQL($conn, $sql, "ii", $_SESSION['eid'], $resultRowApprove["password_reset_id"]);
    
                $sql = "UPDATE credentials SET credential_password=? WHERE credential_employee_id=?";
                prepareSQL($conn, $sql, "si", $resultRowApprove["password_reset_password"], $resultRowApprove["password_reset_employee_id"]);
            } else {
                $sql = "UPDATE password_reset SET password_reset_handler=? WHERE password_reset_id=?;";
                $test = prepareSQL($conn, $sql, "ii", $_SESSION["eid"], $resultRowApprove["password_reset_id"]);
                var_dump(mysqli_errno($conn));;
            }

            // echo '
            //     <script>
            //         window.location.reload;
            //     </script>
            // ';
        }
    }
    
?>

