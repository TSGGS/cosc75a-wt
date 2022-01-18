<?php
    require("../../includes/helper.php");
    require("../../includes/db.php");
    changeTitle("../templates/header.php", "Update Promotion");
    validateUserPage($_SESSION["tid"], $_SERVER["REQUEST_URI"]);
?>

<div class="container mt-3">
        <h1>INVENTORY</h1>
        <form class="mt-4" action="./inventory.php" method="get" autocomplete="off">
            <div class="row">
                <div class="col-11">
                    
                    <datalist id="inventory-list">
                        <?php
                            $sql = "SELECT discount_code FROM discounts WHERE discount_start_timestamp > current_timestamp() AND discount_end_timestamp > current_timestamp() OR discount_start_timestamp < current_timestamp() AND discount_end_timestamp > current_timestamp()";
                            $result = prepareSQL($conn, $sql);
                            while($resultRow = mysqli_fetch_array($result)) {
                                echo '
                                    <option value="'.$resultRow["discount_code"].'"></option>
                                ';
                            }
                        ?>
                    </datalist>
                </div>
            </div>
            <div class="row d-none error" id="">
                <span></span>
            </div>
        </form>
        <form class="mt-4" action="./new-discount.php" method="POST" enctype="multipart/form-data" autocomplete="off">
            <div class="row my-2">
                <div class="col-3 col-title">
                    <label class="col-form-label" for="discount-code"><h4>Inventory Product</h4></label>
                </div>
                <div class="col-9 col-input">
                    <div class="row">
                        <input type="text" class="form-control" name="discount-product" id="inventory-product" autofocus required>
                    </div>
                    <div class="row mb-2 d-none error" id="discount-code-error">
                        Invalid Discount Code
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <div class="col-3 col-title">
                    <label class="col-form-label" for="discount-amount"><h4>Inventory Count</h4></label>
                </div>
                <div class="col-9 col-input">
                    <div class="row">
                        <input type="number" id="quantity" name="quantity" min="1" max="5" class="form-control" name="discount-amount" id="discount-amount">
                    </div>
                    <div class="row mt-2 mb-4 d-none" id="discount-amount-div">
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-3 col-title">
                    <label class="col-form-label" for="discount-end"><h4>Inventory Start</h4></label>
                </div>
                <div class="col-9 col-input">
                    <div class="row">
                        <input type="datetime-local" class="form-control" name="inventory-end" id="inventory-s" required>
                    </div>
                    <div class="row mb-2 d-none error" id="discount-end-error">
                        Invalid Inventory Start
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-3 col-title">
                    <label class="col-form-label" for="discount-end"><h4>Inventory End</h4></label>
                </div>
                <div class="col-9 col-input">
                    <div class="row">
                        <input type="datetime-local" class="form-control" name="discount-end" id="discount-end" required>
                    </div>
                    <div class="row mb-2 d-none error" id="discount-end-error">
                        Invalid Inventory End
                    </div>
                </div>
            </div>
            <div class="row my-3">
                <div class="col pe-0 inline-right">
                    <button type="submit" class="btn btn-light" name="insert-discount"><strong>Insert Inventory</strong></button>
                </div>
            </div>
        </form>
    </div>