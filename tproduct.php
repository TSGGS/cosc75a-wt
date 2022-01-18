<?php
    require("includes/db.php");
    require("templates/customer-header.php");
    echo '
        <title>Walang Tatak - Products</title>
        <script src="https://kit.fontawesome.com/dbed6b6114.js" crossorigin="anonymous"></script>
		    <script src="js/store.js"></script>
    ';
?>
    <div class="content mt-2">
        <h1 class="mt-3" id="page-title">Products</h1>
        <?php
            $sql = "SELECT * FROM products LEFT JOIN types AS t ON product_type=t.type_id LEFT JOIN prices AS p ON p.price_product_id=product_id INNER JOIN inventory AS i ON i.inventory_product_id=product_id WHERE p.price_end_timestamp IS NULL AND product_end_timestamp IS NULL AND i.inventory_product_count > 0 ORDER BY type_name ASC;";
            $result = prepareSQL($conn, $sql);
            while($resultRow = mysqli_fetch_array($result)) {
                echo '
                    <div class="product">
                        <img class="product-image" src="images/product_images/'.$resultRow["product_image"].'" alt="product-image">
                        <div class="product-buttons">
                            <button onclick="addtoCart(\''.$resultRow["product_code"].'\')">Add to Cart</button>
                        </div>
                        <div class="product-info">
                            <span class="product-category">'.$resultRow["type_name"].'</span>
                            <span class="product-title">'.$resultRow["product_name"].'</span>
                            <span class="product-price">₱ '.$resultRow["price_amount"].'</span>
                            <span class="product-description">'.$resultRow["product_description"].'</span>
                        </div>
                    </div>
                ';
            }
        ?>
    </div>
    <div class="cart" style="background-color: red; clear: both;">
        f
    </div>
    <?php 
        include("templates/footer.php")
    ?>