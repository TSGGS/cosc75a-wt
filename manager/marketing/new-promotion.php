<?php
    require("../../includes/helper.php");
    require("../../includes/db.php");

    changeTitle("../templates/header.php", "Update Product");
    validateUserPage($_SESSION["tid"], $_SERVER["REQUEST_URI"]);
?>
    <div class="container mt-3">
        <h1>New Promotion</h1>
        <form class="mt-4" action="./new-promotion.php" method="POST" enctype="multipart/form-data" autocomplete="off">
            <div class="row my-2">
                <div class="col-3 col-title">
                    <label class="col-form-label" for="new-promotion-code"><h4>Promotion Code</h4></label>
                </div>
                <div class="col-9 col-input">
                    <div class="row">
                        <input type="text" class="form-control" name="new-promotion-code" id="new-promotion-code" autofocus required>
                    </div>
                    <div class="row mb-2 d-none error" id="new-promotion-code-error">
                        Invalid Promotion Code
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-3 col-title">
                    <label class="col-form-label" for="new-promotion-image"><h4>Promotion Image</h4></label>
                </div>
                <div class="col-9 col-input">
                    <div class="row">
                        <input type="file" class="form-control" name="new-promotion-image" id="new-promotion-image" required>
                    </div>
                    <div class="row mb-2 d-none error" id="new-promotion-image-error">
                        Invalid File
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-3 col-title">
                    <label class="col-form-label" for="new-promotion-start"><h4>Promotion Start</h4></label>
                </div>
                <div class="col-9 col-input">
                    <div class="row">
                        <input type="datetime-local" class="form-control" name="new-promotion-start" id="new-promotion-start" min="<?= date("Y-m-d\TH:i") ?>" max="<?= date("Y-m-d\TH:i", strtotime("+60 days")) ?>" required>
                    </div>
                    <div class="row mb-2 d-none error" id="new-promotion-start-error">
                        Invalid Promotion Start
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-3 col-title">
                    <label class="col-form-label" for="new-promotion-end"><h4>Promotion End</h4></label>
                </div>
                <div class="col-9 col-input">
                    <div class="row">
                        <input type="datetime-local" class="form-control" name="new-promotion-end" id="new-promotion-end" min="<?= date("Y-m-d\TH:i") ?>" max="<?= date("Y-m-d\TH:i", strtotime("+60 days")) ?>" required>
                    </div>
                    <div class="row mb-2 d-none error" id="new-promotion-end-error">
                        Invalid Promotion End
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col pe-0 inline-right">
                    <button type="submit" class="btn btn-light" name="new-promotion"><strong>Create Promotion</strong></button>
                </div>
            </div>
        </form>
    </div>
<?php
    require("../templates/footer.php");

    if(isset($_POST["new-promotion"])) {
        $prCode = strtoupper($_POST["new-promotion-code"]);
        $prStart = strtotime($_POST["new-promotion-start"]);
        $prEnd = strtotime($_POST["new-promotion-end"]);

        $img = $_FILES["new-promotion-image"]["name"];
        $imgTmp = $_FILES["new-promotion-image"]["tmp_name"];
        $imgExt = pathinfo($img, PATHINFO_EXTENSION);

        $emptyCode = isEmpty($prCode, "new-promotion-code");
        $emptyStart = isEmpty($prStart, "new-promotion-start");
        $emptyEnd = isEmpty($prEnd, "new-promotion-end");

        $errorImage = !isImage($imgExt);
        $errorDate = validateDateRange($prStart, $prEnd);

        $sql = "SELECT IF(EXISTS(SELECT promotion_id FROM promotions WHERE promotion_code=?), true, false) AS result";
        $result = prepareSQL($conn, $sql, "s", $prCode);
        $resultRow = mysqli_fetch_array($result);
        if($resultRow["result"] != 0) {
            echo '
                <script>
                    toggleError("new-promotion-code-error", "show");
                </script>
            ';

            $errorCode = true;
        } else {
            echo '
                <script>
                    toggleError("new-promotion-code-error", "hide");
                </script>
            ';

            $errorCode = false;
        }

        if($errorImage) {
            echo '
                <script>
                    toggleError("new-promotion-image-error", "show");
                </script>
            ';
        } else {
            echo '
                <script>
                    toggleError("new-promotion-image-error", "hide");
                </script>
            ';
        }

        if($errorDate === -1) {
            echo '
                <script>
                    toggleError("new-promotion-start-error", "show");
                </script>
            ';
        } elseif($errorDate === -2) {
            echo '
                <script>
                    toggleError("new-promotion-end-error", "show");
                </script>
            ';
        } elseif($errorDate === -3) {
            echo '
                <script>
                    toggleError("new-promotion-start-error", "show");
                    toggleError("new-promotion-end-error", "show");
                </script>
            ';
        } else {
            echo '
                <script>
                    toggleError("new-promotion-start-error", "hide");
                    toggleError("new-promotion-end-error", "hide");
                </script>
            ';

            $sql = "SELECT IF(EXISTS(SELECT promotion_id FROM promotions WHERE promotion_end_timestamp >= ? AND promotion_start_timestamp <= ?), true, false) AS result";
            $result = prepareSQL($conn, $sql, "ss", $prStart, $prEnd);
            $resultRow = mysqli_fetch_array($result);
            if($resultRow["result"]) {
                echo '
                    <script>
                        toggleError("new-promotion-start-error", "show");
                        toggleError("new-promotion-end-error", "show");
                    </script>
                ';

                $errorDate = -4;
            } else {
                echo '
                    <script>
                        toggleError("new-promotion-start-error", "hide");
                        toggleError("new-promotion-end-error", "hide");
                    </script>
                ';

                $errorDate = 1;
            }
        }

        if(!$emptyCode && !$emptyStart && !$emptyEnd && !$errorCode && !$errorImage && $errorDate == 1) {
            $rename = date('YmdHis').'_'.uniqid().'.'.$imgExt;
            move_uploaded_file($imgTmp, "../../images/promotions/$rename");

            $sql = "INSERT INTO promotions VALUES (NULL, ?, ?, ?, ?)";
            prepareSQL($conn, $sql, "ssss", $prCode, $rename, date("Y-m-d H:i:s",$prStart), date("Y-m-d H:i:s",$prEnd));

            echo '
                <script>
                    window.location.replace("dashboard.php");
                </script>
            ';
        } else {
            echo '
                <script>
                    document.getElementById("new-promotion-code").value = '.json_encode($prCode).';
                    document.getElementById("new-promotion-start").value = '.json_encode(date('Y-m-d H:i', $prStart)).';
                    document.getElementById("new-promotion-end").value = '.json_encode(date('Y-m-d H:i', $prEnd)).';
                </script>
            ';
        }
    }