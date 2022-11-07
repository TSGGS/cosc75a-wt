<?php
    require("../../includes/helper.php");
    require("../../includes/db.php");

    changeTitle("../templates/header.php", "Adminitstrator - WT");
    validateUserPage($_SESSION["tid"], $_SERVER["REQUEST_URI"]);

    // Get Total
    $sql = "SELECT SUM(order_total_price) as SUM FROM orders WHERE order_timestamp BETWEEN DATE_FORMAT(CURRENT_DATE()-6, '%Y-%m-%d') AND CURRENT_TIMESTAMP();";
    $mysql_result = prepareSQL($conn, $sql);
    $result = mysqli_fetch_array($mysql_result);
    
    // Get Hero Product
    $sql2 = "SELECT products.product_name AS Name, SUM(order_items.order_product_quantity) AS SUM FROM `order_items` LEFT JOIN orders ON order_items.order_id = orders.order_id LEFT JOIN products ON order_items.order_product_id=products.product_id WHERE orders.order_timestamp BETWEEN DATE_FORMAT(CURRENT_DATE()-6, '%Y-%m-%d') AND CURRENT_TIMESTAMP() AND orders.order_status_id=2 GROUP BY order_items.order_product_id ORDER BY SUM DESC;";
    $mysql_result2 = $conn->query($sql2);

    //Get Sold Products
    $sql3 = "SELECT products.product_name AS Name, SUM(order_items.order_product_quantity) AS SUM FROM `order_items` LEFT JOIN orders ON order_items.order_id = orders.order_id LEFT JOIN products ON order_items.order_product_id=products.product_id WHERE orders.order_timestamp BETWEEN DATE_FORMAT(CURRENT_DATE()-6, '%Y-%m-%d') AND CURRENT_TIMESTAMP() AND orders.order_status_id=2 GROUP BY order_items.order_product_id ORDER BY SUM DESC;";
    $mysql_result3 = $conn->query($sql3);
    
    // Get Inventory
    $sql4 = "SELECT products.product_name AS Name, inventory.inventory_product_count AS qty FROM inventory INNER JOIN products ON inventory_id=products.product_id;";
    $mysql_result4 = $conn->query($sql4);
?>

    <div class="container mt-3">
        <h1>Week Report</h1>
        <div class="row mt-4">
            <div class="col-3 border-hidden"><h3 class="align-bottom">Total Sales</h3></div>
            <?php
                if($result["SUM"] == NULL) {
            ?>
                    <div class="col border"><h3><?= "Php 0" ?></h3></div>
            <?php
                } else {
            ?>
                    <div class="col border"><h3><?= "Php ".$result["SUM"] ?></h3></div>
            <?php
                }
            ?>
        </div>
        <div class="row mt-4">
            <div class="col-3 border-hidden"><h3 class="align-bottom">Hero Product</h3></div>
            <div class="col border">
                <?php
                    $highest = 0;
                    while($result = mysqli_fetch_array($mysql_result2)) {
                        if($result["SUM"] >= $highest) {
                            echo "<h3>".$result["Name"]."<br></h3>";
                            $highest = $result["SUM"];
                        }
                    }
                ?>
            </div>
        </div>
        <h3 class="mt-4">Ordered Products</h3>
        <table class="table table-sm" style="text-align: center;">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Sold Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if(mysqli_num_rows($mysql_result3) == 0) {
                        echo '
                            <tr>
                                <td colspan=2><h5>No products ordered</h5></td>
                            </tr>
                        ';
                    } else {
                        while($result = mysqli_fetch_array($mysql_result3)) {
                            echo '
                                <tr>
                                    <td><h5>'.$result["Name"].'</h5></td>
                                    <td><h5>'.$result["SUM"].'</h5></td>
                                </tr>
                            ';
                        }
                    }
                ?>
            </tbody>
        </table>
        <h3 class="mt-4">Inventory</h3>
        <table class="table table-sm" style="text-align: center;">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Remaining Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    while($result = mysqli_fetch_array($mysql_result4)) {
                        echo '
                            <tr>
                                <td><h5>'.$result["Name"].'</h5></td>
                                <td><h5>'.$result["qty"].'</h5></td>
                            </tr>
                        ';
                    }
                ?>
            </tbody>
        </table>
    </div>

<?php
    require("../templates/footer.php"); 