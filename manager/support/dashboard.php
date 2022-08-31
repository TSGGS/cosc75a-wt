<?php
    require("../../includes/helper.php");
    require("../../includes/db.php");

    changeTitle("../templates/header.php", "Support - WT");
    validateUserPage($_SESSION["tid"], $_SERVER["REQUEST_URI"]);
?>
     <div class="container mt-3">
        <h1>Employees</h1>
        <table class="table table-sm table-hover mt-4">
            <thead>
                <tr>
                    <th class="inline-center">First Name</th>
                    <th class="inline-center">Last Name</th>
                    <th class="inline-center">Team</th>
                
                </tr>
            </thead>
            <tbody>
                <?php
                    $sql = "SELECT * FROM employees INNER JOIN teams WHERE employee_team_id = team_id and employee_account_end is null";
                    $result = prepareSQL($conn, $sql);
                    while($resultRow = mysqli_fetch_array($result)) {
                        echo '
                            <tr>
                                <td class="inline-center">'.$resultRow["employee_firstname"].'</td>
                                <td class="inline-center">'.$resultRow["employee_lastname"].'</td>
                                <td class="inline-center">'.$resultRow["team_name"].'</td>
                                
                            </tr>
                        ';
                    }
                ?>
            </tbody>
        </table>
    </div>
<?php
    if(isset($_GET["id"])) {
        $sql = "UPDATE employees SET employee_account_end=? WHERE employee_id=?";
        prepareSQL($conn, $sql, "si", date("Y-m-d H:i:s"), $_GET["id"]);

        echo '
            <script>
                window.location.replace("removeemployee.php");
            </script>
        ';
    }
?>
