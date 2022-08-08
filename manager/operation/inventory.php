<?php
    require("../../includes/helper.php");
    require("../../includes/db.php");
    changeTitle("../templates/header.php", "Update Inventory");
    validateUserPage($_SESSION["tid"], $_SERVER["REQUEST_URI"]);
?>

<div class="container mt-3">
        <h1>INVENTORY</h1>
        <form class="mt-4" action="./inventory.php" method="POST" autocomplete="off">
            <?php 
                $products = array();
                $sql = "SELECT inventory.*, products.product_name, products.product_code FROM inventory INNER JOIN products ON inventory_id=products.product_id;";
                $result = prepareSQL($conn, $sql);
                while($row = mysqli_fetch_array($result)) {
                    echo '
                        <div class="row">
                            <div class="col-3">
                                <label class="col-form-label" for="update-promotion-code"><h4>'.$row["product_name"].'</h4></label>
                            </div>
                            <div class="col">
                                <input type="number" class="form-control" name="'.$row["product_code"].'" value="'.$row["inventory_product_count"].'" min="0" required>
                                <input type="hidden" name="hidden-'.$row["product_code"].'" value="'.$row["inventory_id"].'">
                            </div>
                        </div>
                    ';
                    array_push($products, $row["product_code"]);
                }
            ?>
            <div class="row mt-5">
                <div class="col inline-right">
                    <button type="submit" class="btn btn-light" name="update-inventory"><strong>Update Inventory</strong></button>
                </div>
            </div>
        </form>
</div>
<?php
    if(isset($_POST["update-inventory"])) {
        foreach($products as $product) {
            $p = $_POST[$product];
            $id = $_POST["hidden-".$product];

            $sql = "UPDATE inventory SET inventory_product_count=?, inventory_timestamp=? WHERE inventory_id=?";
            prepareSQL($conn, $sql, "isi", $p, date('Y-m-d H:i:s', time()), $id);
        }
    }