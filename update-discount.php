<?php
    require("../../includes/helper.php");
    require("../../includes/db.php");

    changeTitle("../templates/header.php", "Update Promotion");
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
                            $sql = "SELECT promotion_code FROM promotions WHERE promotion_start_timestamp > current_timestamp() AND promotion_end_timestamp > current_timestamp() OR promotion_start_timestamp < current_timestamp() AND promotion_end_timestamp > current_timestamp()";
                            $result = prepareSQL($conn, $sql);
                            while($resultRow = mysqli_fetch_array($result)) {
                                echo '
                                    <option value="'.$resultRow["promotion_code"].'"></option>
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
                        <input type="text" class="form-control" name="update-discount-amount" id="update-discount-amount">
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
                        <input type="datetime-local" class="form-control" name="update-discount-start" id="update-discount-start" required>
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
                        <input type="datetime-local" class="form-control" name="update-discount-end" id="update-disount-end" required>
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
    if(isset($_GET["search-promotion"])) {
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
                $discount_image = json_encode($resultRow["discount_image"]);
                $discount_start = json_encode(date("Y-m-d\TH:i", strtotime($resultRow["discount_start_timestamp"])));
                $discount_end = json_encode(date("Y-m-d\TH:i", strtotime($resultRow["discount_end_timestamp"])));

                $_SESSION["discount_start"] = date("Y-m-d H:i:s", strtotime($resultRow["discount_start_timestamp"]));
                $_SESSION["discount_amount"] = $resultRow["promotion_amount"];

                echo '
                    <script>
                        displayUpdatePromotion('.$promotion_code.', '.$promotion_image.', '.$promotion_start.', '.$promotion_end.');
                    </script>
                ';
            }

        }
    }
    
    if(isset($_POST["update-discount"])){
        $prCode = strtoupper($_POST["update-discount-code"]);
        $prStart = date("Y-m-d H:i:s", strtotime($_POST["update-discount-start"]));
        $prEnd = date("Y-m-d H:i:s", strtotime($_POST["update-discount-end"]));

        $emptyCode = isEmpty($prCode, "update-discount-code");
        $emptyStart = isEmpty($prStart, "update-discount-start");
        $emptyEnd = isEmpty($prEnd, "update-discount-end");

        $emptyImage = (isset($_FILES["update-promotion-image"]["size"])) && ($_FILES["update-promotion-image"]["size"] > 0);
        if($emptyImage) {
            $img = $_FILES["update-promotion-image"]["name"];
            $imgTmp = $_FILES["update-promotion-image"]["tmp_name"];
            $imgExt = pathinfo($img, PATHINFO_EXTENSION);
        }

        $errorDate = validateDateRange($prStart, $prEnd, "update");
        $errorImage = false;

        $sql = "SELECT * FROM promotions WHERE promotion_code=?";
        $result = prepareSQL($conn, $sql, "s", $prCode);
        $resultRow = mysqli_num_rows($result);
        if($resultRow != 1) {
            echo '
                <script>
                    toggleError("update-promotion-code-error", "show");
                </script>
            ';
            $errorCode = true;
        } else {
            echo '
                <script>
                    toggleError("update-discount-code-error", "hide");
                </script>
            ';
            $errorCode = false;
        }

        if($emptyImage) {
            if(!isImage($imgExt)) {
                echo '
                    <script>
                        toggleError("update-promotion-image-error", "show");
                    </script>
                ';

                $errorImage = true;
            } else {
                echo '
                    <script>
                        toggleError("update-promotion-image-error", "hide");
                    </script>
                ';

                $errorImage = false;
            }
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

            $sql = "SELECT IF(EXISTS(SELECT promotion_id FROM promotions WHERE promotion_code != ? AND promotion_end_timestamp >= ? AND promotion_start_timestamp <= ?), true, false) AS result";
            $result = prepareSQL($conn, $sql, "sss", $prCode, $prStart, $prEnd);
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

                if($prStart != $_SESSION["discount_start"]) {
                    echo '
                        <script>
                            toggleError("update-discount-start-error", "show");
                        </script>
                    ';
                    $errorDate = -4;
                } else {
                    echo '
                        <script>
                            toggleError("update-discount-start-error", "hide");
                        </script>
                    ';
                    $errorDate = 1;
                }
            }
        }

        if(!$emptyCode && !$emptyStart && !$emptyEnd && !$errorCode && !$errorImage && $errorDate == 1) {
            $sql = "SELECT promotion_id FROM promotions WHERE promotion_code=?";
            $result = prepareSQL($conn, $sql, "s", $prCode);
            $resultRow = mysqli_fetch_array($result);

            if(!$emptyImage) {
                $rename = date('YmdHis').'_'.uniqid().'.'.$imgExt;

                move_uploaded_file($imgTmp, "../../images/promotions/$rename");

                $sql = "UPDATE promotions SET promotion_image=? WHERE promotion_id=?";
                prepareSQL($conn, $sql, "si", $rename, $resultRow["promotion_id"]);
            }

            $sql = "UPDATE promotions SET promotion_code=?, promotion_start_timestamp=?, promotion_end_timestamp=? WHERE promotion_id=?";
            prepareSQL($conn, $sql, "sssi", $prCode, $prStart, $prEnd, $resultRow["promotion_id"]);

            echo '
                <script>
                    window.location.replace("dashboard.php");
                </script>
            ';
        } else {
            echo '
                <script>
                    document.getElementById("update-discount-code").value = '.json_encode($prCode).';
                    document.getElementById("update-discount-start").value = '.json_encode($_POST["update-discount-start"]).';
                    document.getElementById("update-promotion-end").value = '.json_encode($_POST["update-discount-end"]).';

                    document.getElementById("update-discount-image-display").src = "../../images/promotions/'.$_SESSION["discount_image"].'";
                    document.getElementById("update-discount-image-display-div").classList.remove("d-none");
                </script>
            ';
        }
    }