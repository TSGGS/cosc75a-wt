<?php
    require("../../includes/helper.php");
    require("../../includes/db.php");

    changeTitle("../templates/header.php", "Update Discount");
    validateUserPage($_SESSION["tid"], $_SERVER["REQUEST_URI"]);
?>
    <div class="container mt-3">
        <h1>Update Discount</h1>
        <form class="mt-4" action="./update-discount.php" method="get" autocomplete="off">
            <div class="row">
                <div class="col-11">
                    <input class="form-control"type="text" name="update-search" id="update-search" list="promotion-list" placeholder="Search Discount Code" autocfocus>
                    <datalist id="promotion-list">
                        <?php
                            $sql = "SELECT discount_code, discount_amount FROM discounts";
                            $result = prepareSQL($conn, $sql);
                            while($resultRow = mysqli_fetch_array($result)) {
                                echo '
                                    <option value="'.$resultRow["discount_code"].'">'.$resultRow["discount_amount"].'% Discount</option>
                                ';
                            }
                        ?>
                    </datalist>
                </div>
                <div class="col-1">
                    <button type="submit" class="btn btn-light" name="search-discount" value="search">Search</button>
                </div>
            </div>
            <div class="row d-none error" id="update-search-error">
                <span>Discount Not Found</span>
            </div>
        </form>
        <form class="mt-4" action="./update-discount.php" method="POST" enctype="multipart/form-data" autocomplete="off">
            <div class="row my-2">
                <div class="col-3 col-title">
                    <label class="col-form-label" for="update-promotion-code"><h4>Discount Code</h4></label>
                </div>
                <div class="col-9 col-input">
                    <div class="row">
                        <input type="text" class="form-control" name="update-discount-code" id="update-discount-code" autofocus required>
                    </div>
                    <div class="row mb-2 d-none error" id="update-discount-code-error">
                        Invalid Discount Code
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-3 col-title">
                    <label class="col-form-label" for="update-promotion-image"><h4>Discount Amount</h4></label>
                </div>
                <div class="col-9 col-input">
                    <div class="row">
                        <input type="number" class="form-control" name="update-discount-amount" id="update-discount-amount" min="1" max="99">
                    </div>
                    <div class="row mb-2 d-none error" id="update-discount-amount-error">
                        Invalid Amount
                    </div>
                    <div class="row mt-2 mb-4 d-none" id="update-discount-amount-div">
                        <div class="col gx-0">
                            <img class="update-discount-amount" id="update-discount-amount">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-3 col-title">
                    <label class="col-form-label" for="update-promotion-start"><h4>Discount Start</h4></label>
                </div>
                <div class="col-9 col-input">
                    <div class="row">
                        <input type="datetime-local" class="form-control" name="update-discount-start" id="update-discount-start" min="<?= date("Y-m-d\TH:i") ?>" max="<?= date("Y-m-d\TH:i", strtotime("+60 days")) ?>" required>
                    </div>
                    <div class="row mb-2 d-none error" id="update-discount-start-error">
                        Invalid Discount Start
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-3 col-title">
                    <label class="col-form-label" for="update-promotion-end"><h4>Discount End</h4></label>
                </div>
                <div class="col-9 col-input">
                    <div class="row">
                        <input type="datetime-local" class="form-control" name="update-discount-end" id="update-discount-end" min="<?= date("Y-m-d\TH:i") ?>" max="<?= date("Y-m-d\TH:i", strtotime("+60 days")) ?>" required>
                    </div>
                    <div class="row mb-2 d-none error" id="update-discount-end-error">
                        Invalid Discount End
                    </div>
                </div>
            </div>
            <div class="row my-3">
                <div class="col pe-0 inline-right">
                    <button type="submit" class="btn btn-light" name="update-discount"><strong>Update Discount</strong></button>
                </div>
            </div>
        </form>
    </div>
<?php
    if(isset($_GET["search-discount"])) {
        if(!empty($_GET["update-search"])) {
            echo '
                <script>
                    toggleError("update-search-error", "hide");
                </script>
            ';

            $sql = "SELECT * FROM discounts WHERE discount_code=?";
            $result = prepareSQL($conn, $sql, "s", $_GET["update-search"]);
            
            if(mysqli_num_rows($result) != 1) {
                echo '
                    <script>
                        toggleError("update-search-error", "show");
                    </script>
                ';
            } else {
                echo '
                    <script>
                        toggleError("update-search-error", "hide");
                    </script>
                ';

                $resultRow = mysqli_fetch_array($result);
                $discount_code = json_encode($resultRow["discount_code"]);
                $discount_amount = json_encode($resultRow["discount_amount"]);
                $discount_start = json_encode(date("Y-m-d\TH:i", strtotime($resultRow["discount_start_timestamp"])));
                $discount_end = json_encode(date("Y-m-d\TH:i", strtotime($resultRow["discount_end_timestamp"])));

                $_SESSION["discount_start"] = date("Y-m-d H:i:s", strtotime($resultRow["discount_start_timestamp"]));
                $_SESSION["discount_amount"] = $resultRow["discount_amount"];

                echo '
                    <script>
                        displayUpdateDiscount('.$discount_code.', '.$discount_amount.', '.$discount_start.', '.$discount_end.');
                    </script>
                ';
            }

        }
    }
    
    if(isset($_POST["update-discount"])){
        $dCode = $_POST["update-discount-code"];
        $dAmount =  $_POST["update-discount-amount"];
        $dStart = strtotime($_POST["update-discount-start"]);
        $dEnd = strtotime($_POST["update-discount-end"]);

        $emptyCode = isEmpty($dCode, "update-discount-code");
        $emptyAmount = isEmpty($dAmount, "update-discount-amount");

        $errorDate = validateDateRange($dStart, $dEnd);

  
        $error1 = false;
        $error2 = false;

        $sql = "SELECT discount_code FROM discounts WHERE discount_code=?";
        $result = prepareSQL($conn, $sql, "s", $dCode);
        $row = mysqli_num_rows($result);
        if($row !== 1) {
            echo '
                <script>
                    toggleError("update-discount-code-error", "show");
                </script>
            ';
            $error1 = true;
        } else {
            echo '
                <script>
                    toggleError("update-discount-code-error", "hide");
                </script>
            ';
            $error1 = false;
        }

        if($dAmount < 1 || $dAmount > 99) {
            echo '
                <script>
                    toggleError("update-discount-amount-error", "show");
                </script>
            ';
            $error2 = true;
        } else {
            echo '
                <script>
                    toggleError("update-discount-amount-error", "hide");
                </script>
            ';
            $error2 = false;
        }

        if($errorDate === -1) {
            echo '
                <script>
                    toggleError("update-discount-start-error", "show");
                </script>
            ';
        } elseif($errorDate === -2) {
            echo '
                <script>
                    toggleError("update-discount-end-error", "show");
                </script>
            ';
        } elseif($errorDate === -3) {
            echo '
                <script>
                    toggleError("update-discount-start-error", "show");
                    toggleError("update-discount-end-error", "show");
                </script>
            ';
        } else {
            echo '
                <script>
                    toggleError("update-discount-start-error", "hide");
                    toggleError("update-discount-end-error", "hide");
                </script>
            ';

            $sql = "SELECT IF(EXISTS(SELECT discount_id FROM discounts WHERE discount_end_timestamp >= ? AND discount_start_timestamp <= ?), true, false) AS result";
            $result = prepareSQL($conn, $sql, "ss", date("Y-m-d H:i:s",$dStart), date("Y-m-d H:i:s", $dEnd));
            $resultRow = mysqli_fetch_array($result);
            if($resultRow["result"]) {
                echo '
                    <script>
                        toggleError("update-discount-start-error", "show");
                        toggleError("update-discount-end-error", "show");
                    </script>
                ';

                $errorDate = -4;
            } else {
                echo '
                    <script>
                        toggleError("update-discount-start-error", "hide");
                        toggleError("update-discount-end-error", "hide");
                    </script>
                ';

                $errorDate = 1;
            }

            if(!$emptyCode && !$emptyAmount && !$error1 && !$error2 && $errorDate == 1) {
                $sql = "UPDATE discounts SET discount_amount=?, discount_start_timestamp=?, discount_end_timestamp=? WHERE discount_id=?";
                prepareSQL($conn, $sql, "issi", $dAmount, date("Y-m-d H:i:s",$dStart), date("Y-m-d H:i:s", $dEnd), $dCode);

                echo '
                    <script>
                        window.location.replace("dashboard.php");
                    </script>
                ';
            } else {
                echo '
                    <script>
                        document.getElementById("update-discount-code").value = "'.$dCode.'";
                        document.getElementById("update-discount-amount").value = "'.$dAmount.'";
                        document.getElementById("update-discount-start").value = "'.date("Y-m-d H:i", $dStart).'";
                        document.getElementById("update-discount-end").value = "'.date("Y-m-d H:i", $dEnd).'";
                    </script>
                ';
            }
        }

        echo '
            <script>
                document.getElementById("update-discount-code").value = "'.$dCode.'";
                document.getElementById("update-discount-amount").value = "'.$dAmount.'";
                document.getElementById("update-discount-start").value = "'.date("Y-m-d H:i", $dStart).'";
                document.getElementById("update-discount-end").value = "'.date("Y-m-d H:i", $dEnd).'";
            </script>
        ';
    }