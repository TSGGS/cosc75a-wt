<?php
    require("../../includes/helper.php");
    require("../../includes/db.php");

    changeTitle("../templates/header.php", "New Product - Administrator");
    validateUserPage($_SESSION["tid"], $_SERVER["REQUEST_URI"]);
?>
    <div class="container mt-3">
        <h1>Add New Product</h1>
        <form class="mt-4" action="./new-product.php" method="POST" enctype="multipart/form-data" autocomplete="off">
            <div class="row">
                <div class="col-2">
                    <label class="col-form-label" for="new-product-code"><h4>Product Code</h4></label>
                </div>
                <div class="col">
                    <div class="row">
                        <input type="text" class="form-control" name="new-product-code" id="new-product-code" autofocus required>
                    </div>
                    <div class="row mb-2 d-none error" id="new-product-code-error">
                        <span>Invalid Product Code</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-2">
                    <label class="col-form-label" for="new-product-name"><h4>Product Name</h4></label>
                </div>
                <div class="col">
                    <div class="row">
                        <input type="text" class="form-control" name="new-product-name" id="new-product-name" required>
                    </div>
                    <div class="row mb-2 d-none error" id="new-product-name-error">
                        <span>Invalid Product Name</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-2">
                    <label class="col-form-label" for="new-product-type"><h4>Product Type</h4></label>
                </div>
                <div class="col">
                    <div class="row">
                        <select class="form-select" name="new-product-type" id="new-product-type" required>
                            <option selected disabled>--SELECT PRODUCT TYPE--</option>
                            <?php
                                $sql = "SELECT * FROM types WHERE type_name != 'DISCOUNT' ORDER BY type_name ASC";
                                $result = prepareSQL($conn, $sql);
                                while($rowResult = mysqli_fetch_array($result)) {
                                    echo '<option value="'.$rowResult["type_id"].'">'.$rowResult["type_name"].'</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="row mb-2 d-none error" id="new-product-type-error">
                        <span>Invalid Product Type</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-2">
                    <label class="col-form-label" for="new-product-image"><h4>Product Image</h4></label>
                </div>
                <div class="col">
                    <div class="row">
                        <input type="file" class="form-control" name="new-product-image" id="new-product-image" required>
                    </div>
                    <div class="row mb-2 d-none error" id="new-product-image-error">
                        <span>Invalid File</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-2">
                    <label class="col-form-label" for="new-product-price"><h4>Product Price</h4></label>
                </div>
                <div class="col">
                    <div class="row">
                        <input type="number" class="form-control" name="new-product-price" id="new-product-price" min="0" value="0" required>
                    </div>
                    <div class="row mb-2 d-none error" id="new-product-price-error">
                        <span>Invalid Price</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-2">
                    <label class="col-form-label" for="new-product-desc"><h4>Product Description</h4></label>
                </div>
                <div class="col">
                    <div class="row">
                        <textarea class="form-control" name="new-product-desc" id="new-product-desc"></textarea>
                    </div>
                </div>
                <div class="row mb-2 d-none error" id="new-product-desc-error">
                        <span>Description Requied</span>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col inline-right">
                    <button type="submit" class="btn btn-light" name="new-product"><strong>Add New Product</strong></button>
                </div>
            </div>
        </form>
    </div>
<?php
    require("../templates/footer.php");

    if(isset($_POST["new-product"])) {
        $pCode = strtoupper($_POST["new-product-code"]);
        $pName = $_POST["new-product-name"];
        $pType = $_POST["new-product-type"] ?? "0";
        $pPrice = $_POST["new-product-price"];
        $pDesc = $_POST["new-product-desc"];

        $img = $_FILES["new-product-image"]["name"];
        $imgTmp = $_FILES["new-product-image"]["tmp_name"];
        $imgExt = pathinfo($img, PATHINFO_EXTENSION);

        $emptyCode = isEmpty($pCode, "new-product-code");
        $emptyName = isEmpty($pName, "new-product-name");
        $emptyType = isEmpty($pType, "new-product-type");
        $emptyDesc = isEmpty($pDesc, "new-product-desc");

        $error1 = false;
        $error2 = false;
        $error3 = false;


        $sql = "SELECT product_id, product_code FROM products WHERE product_code=?";
        $result = prepareSQL($conn, $sql, "s", $pCode);
        if(mysqli_num_rows($result) != 0) {
            echo '
                <script>
                    toggleError("new-product-code-error", "show");
                </script>
            ';

            $error1 = true;
        } else {
            echo '
                <script>
                    toggleError("new-product-code-error", "hide");
                </script>
            ';

            $error1 = false;
        }

        if(!isImage($imgExt)) {
            echo '
                <script>
                    toggleError("new-product-image-error", "show");
                </script>
            ';

            $error2 = true;
        } else {
            echo '
                <script>
                    toggleError("new-product-image-error", "hide");
                </script>
            ';

            $error2 = false;
        }

        if($pPrice >= 0) {
            echo '
                <script>
                    toggleError("new-product-price-error", "hide");
                </script>
            ';

            $error3 = false;
        } else {
            echo '
                <script>
                    toggleError("new-product-price-error", "show");
                </script>
            ';

            $error3 = true;
        }

        if(!$emptyCode && !$emptyName && !$emptyType && !$error1 && !$error2 && !$error3 && !$emptyDesc) {
            $rename = date('YmdHis').'_'.uniqid().'.'.$imgExt;
        
            move_uploaded_file($imgTmp, "../../images/product_images/$rename");

            while($products = mysqli_fetch_array($result)) {
                $productID = $products["product_id"];
            }
    
            $sql = "INSERT INTO products VALUES (NULL, ?, ?, ?, ?, ?, NULL, NULL)";
            prepareSQL($conn, $sql, "ssiss", $pCode, $pName, $pType, $pDesc, $rename);

            $sql = "INSERT INTO prices VALUES (NULL, ?, ?, NULL, NULL)";
            prepareSQL($conn, $sql, "ii", $productID, $pPrice);

            echo '
                <script>
                    window.location.replace("dashboard.php");
                </script>
            ';
            
        } else {
            echo '
                <script>
                    document.getElementById("new-product-code").value = '.json_encode($pCode).';
                    document.getElementById("new-product-name").value = '.json_encode($pName).';
                    document.getElementById("new-product-price").value = '.json_encode($pPrice).';
                    document.getElementById("new-product-desc").value = '.json_encode($pDesc).';
                </script>
            ';
        }
    }