<?php
    require("../../includes/helper.php");
    require("../../includes/db.php");

    changeTitle("../templates/header.php", "Support - WT");
    validateUserPage($_SESSION["tid"], $_SERVER["REQUEST_URI"]);

    if(isset($_POST["new-employee"])) {
        $fname = $_POST["new-employee-fname"];
        $lname = $_POST["new-employee-lname"];
        $mobile = $_POST["new-employee-mobile"];
        $email = $_POST["new-employee-mobile"];
        $password = $_POST["new-employee-password"];
        $team = $_POST["new-employee-team"];
        
        $sql = "INSERT INTO employees (employee_firstname, employee_lastname, employee_team_id)
        VALUES ('".$fname."', '".$lname."', '".$team."')";

        if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";

        $sql1 = "SELECT MAX(employee_id) as max_id FROM employees";
        $result = prepareSQL($conn, $sql1);
        while($resultRow = mysqli_fetch_array($result)) {
            $maxid = $resultRow["max_id"];
            
            $sql = "INSERT INTO credentials (credential_employee_id, credential_password)
            VALUES ('".$maxid."', '". password_hash($password,PASSWORD_DEFAULT)."')";
    
            if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
            echo '
            <script>
                window.location.replace("dashboard.php");
            </script>
        ';
            } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        }

        

        if(!preg_match("/^\+63?\d{10}$/", $fname)) {
            echo '
                <script>
                    toggleError(new-employee-name-error, show);
                </script>
            ';
            $error1 = true;
        }else {
            echo '
                <script>
                    toggleError(new-employee-name-error, hide);
                </script>
            ';
            $error1 = false;
        }

    }

  


?>