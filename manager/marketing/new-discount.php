<?php
    require("../../includes/helper.php");
    require("../../includes/db.php");
    changeTitle("../templates/header.php", "New Discount");
    validateUserPage($_SESSION["tid"], $_SERVER["REQUEST_URI"]);

    if(isset($_POST["insert-discount"])) { 
    }
?>

<div class="container mt-3">
        <h1>NEW DISCOUNT</h1>
        <form class="mt-4" action="./new-discount.php" method="POST" enctype="multipart/form-data" autocomplete="off">
            <div class="row my-2">
                <div class="col-3 col-title">
                    <label class="col-form-label" for="new-discount-code"><h4>Discount Code</h4></label>
                </div>
                <div class="col-9 col-input">
                    <div class="row">
                        <input type="text" class="form-control" name="new-discount-code" id="new-discount-code" autofocus required>
                    </div>
                    <div class="row mb-2 d-none error" id="new-discount-code-error">
                        Invalid Discount Code
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-3 col-title">
                    <label class="col-form-label" for="new-discount-amount"><h4>Discount Amount</h4></label>
                </div>
                <div class="col-9 col-input">
                    <div class="row">
                        <input type="number" class="form-control" name="new-discount-amount" id="new-discount-amount" min="0" value="0" required>
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-3 col-title">
                    <label class="col-form-label" for="new-discount-start"><h4>Discount Start</h4></label>
                </div>
                <div class="col-9 col-input">
                    <div class="row">
                        <input type="datetime-local" class="form-control" name="new-discount-start" id="new-discount-start" required>
                    </div>
                    <div class="row mb-2 d-none error" id="new-discount-start-error">
                        Invalid Discount Start
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-3 col-title">
                    <label class="col-form-label" for="new-discount-end"><h4>Discount End</h4></label>
                </div>
                <div class="col-9 col-input">
                    <div class="row">
                        <input type="datetime-local" class="form-control" name="new-discount-end" id="new-discount-end" required>
                    </div>
                    <div class="row mb-2 d-none error" id="new-discount-end-error">
                        Invalid Discount End
                    </div>
                </div>
            </div>
            <div class="row my-3">
                <div class="col pe-0 inline-right">
                    <button type="submit" class="btn btn-light" name="new-discount"><strong>Insert Discount</strong></button>
                </div>
            </div>
        </form>
    </div>
<?php
    if(isset($_POST["new-discount"])) {
        $dCode = $_POST["new-discount-code"];
        $dAmount =  $_POST["new-discount-amount"];
        $dStart = date("Y-m-d H:i:s", strtotime($_POST["new-discount-start"]));
        $dEnd = date("Y-m-d H:i:s", strtotime($_POST["new-discount-end"]));

        $emptyCode = isEmpty($dCode, "new-discount-code");
        $emptyAmount = isEmpty($dAmount, "new-discount-amount");

        $errorDate = validateDateRange($dStart, $dEnd);

  
        $error1 = false;
        $error2 = false;

        $sql = "SELECT discount_code FROM discounts WHERE discount_code=?";
        $result = prepareSQL($conn, $sql, "s", $dCode);
        $row = mysqli_num_rows($result);
        if($row > 0) {
            echo '
                <script>
                    toggleError("new-discount-code-error", "show");
                </script>
            ';
            $error1 = true;
        } else {
            echo '
                <script>
                    toggleError("new-discount-code-error", "hide");
                </script>
            ';
            $error1 = false;
        }

        if($dAmount < 1 || $dAmount > 99) {
            echo '
                <script>
                    toggleError("new-discount-amount-error", "show");
                </script>
            ';
            $error2 = true;
        } else {
            echo '
                <script>
                    toggleError("new-discount-amount-error", "hide");
                </script>
            ';
            $error2 = false;
        }

        if($errorDate === -1) {
            echo '
                <script>
                    toggleError("new-discount-start-error", "show");
                    alert("HERE");
                </script>
            ';
        } elseif($errorDate === -2) {
            echo '
                <script>
                    toggleError("new-discount-end-error", "show");
                </script>
            ';
        } elseif($errorDate === -3) {
            echo '
                <script>
                    toggleError("new-discount-start-error", "show");
                    toggleError("new-discount-end-error", "show");
                </script>
            ';
        } else {
            echo '
                <script>
                    toggleError("new-discount-start-error", "hide");
                    toggleError("new-discount-end-error", "hide");
                </script>
            ';

            $sql = "SELECT IF(EXISTS(SELECT discount_id FROM discounts WHERE discount_end_timestamp >= ? AND discount_start_timestamp <= ?), true, false) AS result";
            $result = prepareSQL($conn, $sql, "ss", $dStart, $dEnd);
            $resultRow = mysqli_fetch_array($result);
            if($resultRow["result"]) {
                echo '
                    <script>
                        toggleError("new-discount-start-error", "show");
                        toggleError("new-discount-end-error", "show");
                    </script>
                ';

                $errorDate = -4;
            } else {
                echo '
                    <script>
                        toggleError("new-discount-start-error", "hide");
                        toggleError("new-discount-end-error", "hide");
                    </script>
                ';

                $errorDate = 1;
            }

            if(!$emptyCode && !$emptyAmount && !$error1 && !$error2 && $errorDate == 1) {
                $sql = "INSERT INTO discounts VALUES (NULL, ?, ?, ?, ?)";
                prepareSQL($conn, $sql, "siss", $dCode, $dAmount, $dStart, $dEnd);

                echo '
                    <script>
                        window.location.replace("dashboard.php");
                    </script>
                ';
            } else {
                echo '
                    <script>
                        document.getElementById("new-discount-code").value = "'.$dCode.'";
                        document.getElementById("new-discount-amount").value = "'.$dAmount.'";
                        document.getElementById("new-discount-start").value = "'.$dStart.'";
                        document.getElementById("new-discount-end").value = "'.$dEnd.'";
                    </script>
                ';
            }
        }

        echo '
            <script>
                document.getElementById("new-discount-code").value = "'.$dCode.'";
                document.getElementById("new-discount-amount").value = "'.$dAmount.'";
                document.getElementById("new-discount-start").value = "'.$dStart.'";
                document.getElementById("new-discount-end").value = "'.$dEnd.'";
            </script>
        ';
    }