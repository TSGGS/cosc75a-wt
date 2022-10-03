<?php

    require("includes/db.php");
    require("includes/helper.php");
    require("templates/customer-header.php");

    if($count == 0) {
        header("Location: product.php");
        die();
    }

    echo '
        <title>Customer Information | Walang Tatak</title>
        <script src="https://kit.fontawesome.com/dbed6b6114.js" crossorigin="anonymous"></script>
		    <script src="js/store.js"></script>
    ';
?>
    <div class="content mt-2">
        <h1 class="mt-3 page-title">Customer Information</h1>
        <form class="mt-4" action="./customer.php" method="POST" enctype="multipart/form-data" autocomplete="off">
            <div class="row">
                <div class="col-2">
                <label class="col-form-label" for="customer-name"><h5>Customer Name</h5></label>
                </div>
                <div class="col">
                    <div class="row">
                        <input type="text" class="form-control" name="customer-name" id="customer-name" required>
                    </div>
                    <div class="row mb-2 d-none error" id="customer-name-error">
                        Invalid Name
                    </div>
                </div>
                <div class="col-1">
                </div>
            </div>
            <div class="row">
                <div class="col-2">
                <label class="col-form-label" for="customer-address"><h5>Delivery Address</h5></label>
                </div>
                <div class="col">
                    <div class="row">
                        <input type="text" class="form-control" name="customer-address" id="customer-address" required>
                    </div>
                    <div class="row mb-2 d-none error" id="customer-address-error">
                        Invalid Address
                    </div>
                </div>
                <div class="col-1">
                </div>
            </div>
            <div class="row">
                <div class="col-2">
                <label class="col-form-label" for="customer-number"><h5>Contact Number</h5></label>
                </div>
                <div class="col">
                    <div class="row">
                        <input type="text" class="form-control" name="customer-number" id="customer-number" value="+63" required>
                    </div>
                    <div class="row mb-2 d-none error" id="customer-number-error">
                        Invalid Contact Number
                    </div>
                </div>
                <div class="col-1">
                </div>
            </div>
            <h1 class="mt-3 page-title">Delivery Information</h1>
            <div class="row">
                <div class="col-2">
                <label class="col-form-label" for="customer-date"><h5>Delivery Date</h5></label>
                </div>
                <div class="col">
                    <div class="row">
                        <input type="date" class="form-control" name="customer-date" id="customer-date" min="<?php echo(date("Y-m-d")) ?>" max="<?php echo(date("Y-m-d", strtotime("+30 days"))) ?>" onblur="checkASAP();" required>
                    </div>
                    <div class="row mb-2 d-none error" id="customer-date-error">
                        Invalid Date
                    </div>
                </div>
                <div class="col-1">
                </div>
            </div>
            <div class="row">
                <div class="col-2">
                <label class="col-form-label" for="customer-time"><h5>Delivery Time</h5></label>
                </div>
                <div class="col">
                    <div class="row">
                        <input type="time" class="form-control" name="customer-time" id="customer-time" onblur="checkASAP();" required>
                    </div>
                    <div class="row">
                        <input type="checkbox" class="form-check-input" name="customer-time-asap" id="customer-time-asap" onclick="checkedCheckbox();">&nbsp;ASAP
                    </div>
                    <div class="row mb-2 d-none error" id="customer-time-error">
                        Invalid Time
                    </div>
                </div>
                <div class="col-1">
                </div>
            </div>
            <div class="row">
                <div class="col mt-5 mb-5 inline-right">
                    <button type="submit" class="btn btn-dark" name="confirm-order" id="confirm-order">Confirm Order</button>
                </div>
                <div class="col-1">
                </div>
            </div>
        </form>
    </div>

<?php
    if(isset($_POST["confirm-order"])) {
        $name = $_POST["customer-name"];
        $addr = $_POST["customer-address"];
        $cpno = $_POST["customer-number"];
        $date = $_POST["customer-date"];
        $otime = $_POST["customer-time"];

        if(!validateName($name)){
            echo '
                <script>
                    toggleError("customer-name-error", "show");
                </script>
            ';
            $error1 = true;
        } else {
            echo '
                <script>
                    toggleError("customer-name-error", "hide");
                </script>
            ';
            $error1 = false;
        }

        if(!validateAddress($addr)){
            echo '
                <script>
                    toggleError("customer-address-error", "show");
                </script>
            ';
            $error2 = true;
        } else {
            echo '
                <script>
                    toggleError("customer-address-error", "hide");
                </script>
            ';
            $error2 = false;
        }

        if(!validateMobile($cpno)) {
            echo '
                <script>
                    toggleError("customer-number-error", "show");
                </script>
            ';
            $error3 = true;
        } else {
            echo '
                <script>
                    toggleError("customer-number-error", "hide");
                </script>
            ';
            $error3 = false;
        }
        
        if (isset($_POST["customer-time-asap"])) {
            $date = date("Y-m-d");
            $time = date("H:i");

            $custDateTime = date("Y-m-d H:i", strtotime("+10 minutes"));

            $error4 = false;
            $error5 = false;
        } else {
            if(!validateCustDate($date, $otime)) {
                echo '
                    <script>
                        toggleError("customer-date-error", "show");
                    </script>
                ';
                $error4 = true;
            } else {
                echo '
                    <script>
                        toggleError("customer-date-error", "hide");
                    </script>
                ';
                $error4 = false;
            }

            if(isEmpty($otime, "customer-time")) {
                echo '
                    <script>
                        toggleError("customer-time-error", "show");
                    </script>
                ';
                $error5 = true;
            } else {
                echo '
                    <script>
                        toggleError("customer-time-error", "hide");
                    </script>
                ';

                $custDateTime = date("Y-m-d H:i", strtotime("$date $otime"));
                $error5 = false;
            }
        }

        if(!$error1 && !$error2 && !$error3 && !$error4 && !$error5) {
            $sql = "INSERT INTO orders VALUES (NULL, ?, ?, ?, ?, ?, NULL, 1, NULL)";
            prepareSQL($conn, $sql, "sisss", $name, $_SESSION["GRANDTOTAL"], $cpno, $addr, $custDateTime);

            $sql = "SELECT LAST_INSERT_ID() AS orderCustID";
            $result = prepareSQL($conn,$sql);
            $orderCustID = mysqli_fetch_array($result);
            $orderCustID = $orderCustID["orderCustID"];

            foreach($_SESSION["cartList"] as $item) {
                $sql = "SELECT product_id FROM products WHERE product_code = ?";
                $result = prepareSQL($conn, $sql, "s", $item);
                $itemID = mysqli_fetch_array($result);
                $itemID = $itemID["product_id"];

                $sql = "INSERT INTO order_items VALUES (NULL, ?, ?, ?)";
                prepareSQL($conn, $sql, "isi", $orderCustID, $itemID,$_SESSION["cartInfo"][$item]["count"]);
            }

            unset($_SESSION["cartList"], $_SESSION["itemCount"], $_SESSION["cartCount"], $_SESSION["cartInfo"], $_SESSION["GRANDTOTAL"], $_SESSION["discountCode"]);
            echo '
                <script>
                    // let itemCount = document.getElementById("cart-count");
                    // itemCount.value = 0;
                    window.location.href = "thank-you.php";
                </script>
            ';
        } else {
            echo '
                <script>
                    document.getElementById("customer-name").value = '.json_encode($name).';
                    document.getElementById("customer-address").value = '.json_encode($addr).';
                    document.getElementById("customer-number").value = '.json_encode($cpno).';
                    document.getElementById("customer-date").value = '.json_encode($date).';
                    document.getElementById("customer-time").value = '.json_encode($otime).';
                </script>
            ';
        }
    }