<?php
    require("../../includes/helper.php");
    require("../../includes/db.php");

    changeTitle("../templates/header.php", "Support - WT");
    validateUserPage($_SESSION["tid"], $_SERVER["REQUEST_URI"]);

    if(!isset($Password)) {
        $Password = "";
    }

?>
    <div class="container mt-3">
        <h1>Reset Password</h1>
        <form action="resetform.php" method="POST" enctype="multipart/form-data" autocomplete="off">
            <div class="row">
                <div class="col-4 col-title-large">
                    <label class="col-form-label" for="new-employee-fname"><h4>Password: </h4></label>
                </div>
                <div class="col">
                    <div class="row">
                        <input type="text" class="form-control" name="new-employee-fname" id="new-employee-fname" value="<?php echo $fname; ?>" autofocus required>
                    </div>
                    
                    <input type="hidden" id="id" name="id" value="<?php echo $_GET["id"]; ?>">


                <div class="row mt-5">
                    <div class="col pe-0 inline-right">
                        <button type="submit" class="btn btn-light" name="new-employee"><strong>Reset</strong></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
<?php
    if(isset($_POST["new-employee-fname"])) {

        $password = $_POST["new-employee-fname"];

        $sql = "UPDATE credentials SET credential_password='".password_hash($_POST['new-employee-fname'], PASSWORD_DEFAULT)."' WHERE credential_employee_id=".$_POST["id"];

        if ($conn->query($sql) === TRUE) {
          echo "Record updated successfully";

          echo '
          <script>
              window.location.replace("resetpassword.php");
          </script>
          ';
        } else {
          echo "Error updating record: " . $conn->error;
        }

    }
    






