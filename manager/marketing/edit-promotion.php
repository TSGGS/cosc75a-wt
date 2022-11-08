<?php
    require("../../includes/helper.php");
    require("../../includes/db.php");

    changeTitle("../templates/header.php", "Update Promotion");
    validateUserPage($_SESSION["tid"], $_SERVER["REQUEST_URI"]);
?>
    <div class="container mt-3">
        <h1>Update Promotion</h1>
        <form class="mt-4" action="./edit-promotion.php" method="get" autocomplete="off">
            <div class="row">
                <div class="col-11">
                    <input class="form-control"type="text" name="update-search" id="update-search" list="promotion-list" placeholder="Search Promotion Code" autocfocus>
                    <datalist id="promotion-list">
                        <?php
                            $sql = "SELECT promotion_code FROM promotions";
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
                    <button type="submit" class="btn btn-light" name="search-promotion" value="search">Search</button>
                </div>
            </div>
            <div class="row d-none error" id="update-search-error">
                <span>Promotion Not Found</span>
            </div>
        </form>
        <form class="mt-4" id="update-promotion-form" action="./edit-promotion.php" method="POST" enctype="multipart/form-data" autocomplete="off">
            <div class="row my-2">
                <div class="col-3 col-title">
                    <label class="col-form-label" for="update-promotion-code"><h4>Promotion Code</h4></label>
                </div>
                <div class="col-9 col-input">
                    <div class="row">
                        <input type="text" class="form-control" name="update-promotion-code" id="update-promotion-code" autofocus required>
                    </div>
                    <div class="row mb-2 d-none error" id="update-promotion-code-error">
                        Invalid Promotion Code
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-3 col-title">
                    <label class="col-form-label" for="update-promotion-image"><h4>Promotion Image</h4></label>
                </div>
                <div class="col-9 col-input">
                    <div class="row">
                        <input type="file" class="form-control" name="update-promotion-image" id="update-promotion-image">
                    </div>
                    <div class="row mb-2 d-none error" id="update-promotion-image-error">
                        Invalid File
                    </div>
                    <div class="row mt-2 mb-4 d-none" id="update-promotion-image-display-div">
                        <div class="col gx-0">
                            <img class="update-image-display" id="update-promotion-image-display">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-3 col-title">
                    <label class="col-form-label" for="update-promotion-start"><h4>Promotion Start</h4></label>
                </div>
                <div class="col-9 col-input">
                    <div class="row">
                        <input type="datetime-local" class="form-control" name="update-promotion-start" id="update-promotion-start" required>
                    </div>
                    <div class="row mb-2 d-none error" id="update-promotion-start-error">
                        Invalid Promotion Start
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-3 col-title">
                    <label class="col-form-label" for="update-promotion-end"><h4>Promotion End</h4></label>
                </div>
                <div class="col-9 col-input">
                    <div class="row">
                        <input type="datetime-local" class="form-control" name="update-promotion-end" id="update-promotion-end" required>
                    </div>
                    <div class="row mb-2 d-none error" id="update-promotion-end-error">
                        Invalid Promotion End
                    </div>
                </div>
            </div>
            <div class="row my-3">
                <div class="col pe-0 inline-right">
                    <button type="submit" class="btn btn-light" name="update-promotion" onclick="verifyUpdatePromotion(event)"><strong>Update Promotion</strong></button>
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

            $sql = "SELECT * FROM promotions WHERE promotion_code=?";
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
                $promotion_code = json_encode($resultRow["promotion_code"]);
                $promotion_image = json_encode($resultRow["promotion_image"]);
                $promotion_start = json_encode(date("Y-m-d\TH:i", strtotime($resultRow["promotion_start_timestamp"])));
                $promotion_end = json_encode(date("Y-m-d\TH:i", strtotime($resultRow["promotion_end_timestamp"])));

                $_SESSION["promotion_start"] = date("Y-m-d H:i:s", strtotime($resultRow["promotion_start_timestamp"]));
                $_SESSION["promotion_image"] = $resultRow["promotion_image"];

                echo '
                    <script>
                        displayUpdatePromotion('.$promotion_code.', '.$promotion_image.', '.$promotion_start.', '.$promotion_end.');
                    </script>
                ';
            }

        }
    }
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $prCode = strtoupper($_POST["update-promotion-code"]);
        $prStart = strtotime($_POST["update-promotion-start"]);
        $prEnd = strtotime($_POST["update-promotion-end"]);

        $emptyCode = isEmpty($prCode, "update-promotion-code");
        $emptyStart = isEmpty($prStart, "update-promotion-start");
        $emptyEnd = isEmpty($prEnd, "update-promotion-end");

        $emptyImage = !(isset($_FILES["update-promotion-image"]["size"]) && $_FILES["update-promotion-image"]["size"] > 0);
        if(!$emptyImage) {
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
                    toggleError("update-promotion-code-error", "hide");
                </script>
            ';
            $errorCode = false;
        }

        if(!$emptyImage) {
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
                    toggleError("update-promotion-start-error", "show");
                </script>
            ';
        } elseif($errorDate === -2) {
            echo '
                <script>
                    toggleError("update-promotion-end-error", "show");
                </script>
            ';
        } elseif($errorDate === -3) {
            echo '
                <script>
                    toggleError("update-promotion-start-error", "show");
                    toggleError("update-promotion-end-error", "show");
                </script>
            ';
        } else {
            echo '
                <script>
                    toggleError("update-promotion-start-error", "hide");
                    toggleError("update-promotion-end-error", "hide");
                </script>
            ';

            $sql = "SELECT IF(EXISTS(SELECT promotion_id FROM promotions WHERE promotion_code != ? AND promotion_end_timestamp >= ? AND promotion_start_timestamp <= ?), true, false) AS result";
            $result = prepareSQL($conn, $sql, "sss", $prCode, $prStart, $prEnd);
            $resultRow = mysqli_fetch_array($result);
            if($resultRow["result"]) {
                echo '
                    <script>
                        toggleError("update-promotion-start-error", "show");
                        toggleError("update-promotion-end-error", "show");
                    </script>
                ';

                $errorDate = -4;
            } else {
                echo '
                    <script>
                        toggleError("update-promotion-start-error", "hide");
                        toggleError("update-promotion-end-error", "hide");
                    </script>
                ';
                $errorDate = 1;
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
            prepareSQL($conn, $sql, "sssi", $prCode, date("Y-m-d H:i:s",$prStart), date("Y-m-d H:i:s",$prEnd), $resultRow["promotion_id"]);

            echo '
                <script>
                    window.location.replace("dashboard.php");
                </script>
            ';
        } else {
            echo '
                <script>
                    document.getElementById("update-promotion-code").value = '.json_encode($prCode).';
                    document.getElementById("update-promotion-start").value = '.json_encode(date('Y-m-d H:i', $prStart)).';
                    document.getElementById("update-promotion-end").value = '.json_encode(date('Y-m-d H:i', $prEnd)).';

                    document.getElementById("update-promotion-image-display").src = "../../images/promotions/'.$_SESSION["promotion_image"].'";
                    document.getElementById("update-promotion-image-display-div").classList.remove("d-none");
                </script>
            ';
        }
    }