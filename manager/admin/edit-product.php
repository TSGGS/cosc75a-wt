<?php
    require("../../includes/helper.php");
    require("../../includes/db.php");

    changeTitle("../templates/header.php", "Update Product");
    validateUserPage($_SESSION["tid"], $_SERVER["REQUEST_URI"]);
?>
    <div class="container mt-3">
        <h1>Update Product</h1>
        <form class="mt-4" action="./edit-product.php" method="get" autocomplete="off">
            <div class="row">
                <div class="col-11">
                    <input class="form-control" type="text" name="update-search" id="update-search" list="product-list" placeholder="Search Product" autocfocus>
                    <datalist id="product-list">
                        <?php
                            $sql = "SELECT p.product_code AS product_code, p.product_name AS product_name FROM products AS p WHERE product_end_timestamp IS NULL AND p.product_type != 4";
                            $result = prepareSQL($conn, $sql);
                            while($resultRow = mysqli_fetch_array($result)) {
                                echo '
                                    <option value="'.$resultRow["product_code"].'">'.$resultRow["product_name"].'</option>
                                ';
                            }
                        ?>
                    </datalist>
                </div>
                <div class="col-1">
                    <button type="submit" class="btn btn-light" name="search-product" value="search">Search</button>
                </div>
            </div>
            <div class="row d-none error" id="update-search-error">
                <span>Product Not Found</span>
            </div>
        </form>
        <form class="mt-4" action="./edit-product.php" method="post" enctype="multipart/form-data" autocomplete="off">
        <div class="row">
                <div class="col-2">
                    <label class="col-form-label" for="update-product-code"><h4>Product Code</h4></label>
                </div>
                <div class="col">
                    <div class="row">
                        <input type="text" class="form-control" name="update-product-code" id="update-product-code" required>
                    </div>
                    <div class="row d-none mb-2 error" id="update-product-code-error">
                        Product Not Found
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-2">
                    <label class="col-form-label" for="update-product-name"><h4>Product Name</h4></label>
                </div>
                <div class="col">
                    <div class="row">
                        <input type="text" class="form-control" name="update-product-name" id="update-product-name" required>
                    </div>
                    <div class="row d-none mb-2 error" id="update-product-name-error">
                        Invalid Product Name
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-2">
                    <label class="col-form-label" for="update-product-type"><h4>Product Type</h4></label>
                </div>
                <div class="col">
                    <div class="row">
                        <select class="form-select" name="update-product-type" id="update-product-type" required>
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
                    <div class="row d-none mb-2 error" id="update-product-type-error">
                        Invalid Product Type
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-2">
                    <label class="col-form-label" for="update-product-image"><h4>Product Image</h4></label>
                </div>
                <div class="col">
                    <div class="row">
                        <div class="col">
                            <div class="row">
                                <input type="file" class="form-control" name="update-product-image" id="update-product-image">
                            </div>
                            <div class="row d-none mb-2 error" id="update-product-image-error">
                                Invalid File
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-2 mb-4 d-none" id="update-product-image-display-div">
                        <div class="col gx-0">
                            <img class="update-image-display" id="update-product-image-display">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-2">
                    <label class="col-form-label" for="update-product-price"><h4>Product Price</h4></label>
                </div>
                <div class="col">
                    <div class="row">
                        <input type="number" class="form-control" name="update-product-price" id="update-product-price" min="0" required>
                    </div>
                    <div class="row d-none mb-2 error" id="update-product-price-error">
                        Invalid Price
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-2">
                    <label class="col-form-label" for="update-product-desc"><h4>Product Description</h4></label>
                </div>
                <div class="col">
                    <div class="row">
                        <textarea class="form-control" name="update-product-desc" id="update-product-desc" required></textarea>
                    </div>
                    <div class="row d-none mb-2 error" id="update-product-desc-error">
                        Description Required
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-2">
                    <label class="col-form-label" for="update-product-status"><h4>Product Status</h4></label>
                </div>
                <div class="col">
                    <div class="row">
                        <select class="form-select" name="update-product-status" id="update-product-status" required>
                            <option value="ACTIVE" selected>ACTIVE</option>
                            <option value="INACTIVE">INACTIVE</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row my-3">
                <div class="col gx-0 inline-right">
                    <button type="submit" class="btn btn-light" name="update-product"><strong>Update Product</strong></button>
                </div>
            </div>
        </form>
    </div>
<?php
    require("../templates/footer.php");

    if(isset($_GET["search-product"])) {
        if(!empty($_GET["update-search"])) {
            echo '
                <script>
                    toggleError("update-search-error", "hide");
                </script>
            ';

            $sql = "SELECT product_code, product_name, type_id AS product_type, product_image, price_amount, product_description FROM products INNER JOIN types ON product_type = type_id INNER JOIN prices ON product_id=price_product_id  WHERE product_code=? AND price_end_timestamp IS NULL";
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

                while($resultRow = mysqli_fetch_array($result)) {
                    $product_code = json_encode($resultRow["product_code"]);
                    $product_name = json_encode($resultRow["product_name"]);
                    $product_type = json_encode($resultRow["product_type"]);
                    $product_image = json_encode($resultRow["product_image"]);
                    $price_amount = json_encode($resultRow["price_amount"]);
                    $product_description = json_encode($resultRow["product_description"]);

                    $_SESSION["product_image"] = $resultRow["product_image"];
    
                    echo '
                        <script>
                            displayUpdateData('.$product_code.', '.$product_name.', '.$product_type.', '.$product_image.', '.$price_amount.', '.$product_description.', '.');
                        </script>
                    ';
                }
            }

        } else {
            echo '
                <script>
                    toggleError("update-search-error", "show");
                </script>
            ';
        }

    }

    if(isset($_POST["update-product"])) {
        $pStatus = $_POST["update-product-status"];
        $pCode = strtoupper($_POST["update-product-code"]);
        $pName = $_POST["update-product-name"];
        $pType = $_POST["update-product-type"] ?? 0;
        $pPrice = $_POST["update-product-price"];
        $pDesc = $_POST["update-product-desc"];

        $emptyCode = isEmpty($pCode, "update-product-code");
        $emptyName = isEmpty($pName, "update-product-name");
        $emptyType = isEmpty($pType, "update-product-type");
        $emptyPrice = isEmpty($pPrice, "update-product-price");
        $emptyDesc = isEmpty($pDesc, "update-product-desc");

        $emptyImage = (isset($_FILES["update-product-image"]["size"])) && ($_FILES["update-product-image"]["size"] > 0);
        if($emptyImage) {
            $img = $_FILES["update-product-image"]["name"];
            $imgTmp = $_FILES["update-product-image"]["tmp_name"];
            $imgExt = pathinfo($img, PATHINFO_EXTENSION);
        }

        $errorImage = false;
        $errorPrice = false;

        $sql = "SELECT product_id, product_code FROM products WHERE product_code=?";
        $result = prepareSQL($conn, $sql, "s", $pCode);
        if(mysqli_num_rows($result) != 1) {
            echo '
                <script>
                    toggleError("update-product-code-error", "show");
                </script>
            ';
        } else {
            echo '
                <script>
                    toggleError("update-product-code-error", "hide");
                </script>
            ';

            if($pStatus == "INACTIVE") {
                $sql = "UPDATE products SET product_end_timstamp=? WHERE product_code=?";
                prepareSQL($conn, $sql, "ss", date('Y-m-d H:i:s'), $pCode);
    
            } else {
                if($pPrice >= 0) {
                    echo '
                        <script>
                            toggleError("update-product-price-error", "hide");
                        </script>
                    ';

                    $errorPrice = false;
                } else {
                    echo '
                        <script>
                            toggleError("update-product-price-error", "show");
                        </script>
                    ';

                    $errorPrice = true;
                }

                if($emptyImage) {
                    if(!isImage($imgExt)) {
                        echo '
                            <script>
                                document.getElementById("update-product-image").classList.remove("mt-2");
                                toggleError("update-product-image-error", "show");
                            </script>
                        ';
            
                        $errorImage = true;
                    } else {
                        echo '
                            <script>
                                document.getElementById("update-product-image").classList.add("mt-2");
                                toggleError("update-product-image-error", "hide");
                            </script>
                        ';
            
                        $errorImage = false;
                    }
                }

                if(!$emptyCode && !$emptyName && !$emptyType && !$errorImage && !$errorPrice && !$emptyDesc){
                    $sql = "SELECT product_id, price_id, price_amount FROM products INNER JOIN prices ON product_id=price_product_id WHERE product_code=? AND price_end_timestamp IS NULL";
                    $result = prepareSQL($conn, $sql, "s", $pCode);
                    $resultRow = mysqli_fetch_array($result);
                    $priceDiff = $pPrice != $resultRow["price_amount"];

                    $sql = "UPDATE products SET product_code=?, product_name=?, product_type=?, product_description=? WHERE product_id=?";
                    prepareSQL($conn, $sql, "ssisi", $pCode, $pName, $pType, $pDesc, $resultRow["product_id"]);

                    if(!$emptyImage) {
                        $rename = date('YmdHis').'_'.uniqid().'.'.$imgExt;
                        move_uploaded_file($imgTmp, "../../images/product_images/$rename");

                        $sql = "UPDATE products SET product_image=? WHERE product_id=?";
                        prepareSQL($conn, $sql, "ss", $rename, $resultRow["product_id"]);                    
                    }

                    if($priceDiff) {
                        $sql = "UPDATE prices SET price_end_timestamp=? WHERE price_id=?";
                        prepareSQL($conn, $sql, "si", date('Y-m-d H:i:s'), $resultRow["price_id"]);

                        $sql ="INSERT INTO prices VALUES (NULL, ?, ?, NULL, NULL)";
                        prepareSQL($conn, $sql, "ii", $resultRow["product_id"], $pPrice);
                    }

                    echo '
                        <script>
                            window.location.replace("dashboard.php");
                        </script>
                    ';
                } else {
                    echo '
                        <script>
                            document.getElementById("update-product-code").value = '.json_encode($pCode).';
                            document.getElementById("update-product-name").value = '.json_encode($pName).';
                            document.getElementById("update-product-type").value = '.json_encode($pType).';
                            document.getElementById("update-product-price").value = '.json_encode($pPrice).';
                            document.getElementById("update-product-desc").value = '.json_encode($pDesc).';

                            document.getElementById("update-product-image-display").src = "../../images/product_images/'.$_SESSION["product_image"].'";
                            document.getElementById("update-product-image-display-div").classList.remove("d-none");
                        </script>
                    ';
                }
            }
        }
    }