<?php
    require("includes/db.php");
    require("templates/customer-header.php");
    echo '
        <title>Walang Tatak - Cart</title>
        <script src="https://kit.fontawesome.com/dbed6b6114.js" crossorigin="anonymous"></script>
		    <script src="js/store.js"></script>
    ';
?>
    <div class="content mt-2">
        <h1 class="mt-3 page-title">Cart</h1>
        <div class="row text-center">
            <div class="col-1">

            </div>
            <div class="col">
                Name
            </div>
            <div class="col-1">
                Quantity
            </div>
            <div class="col-1">
                Amount
            </div>
            <div class="col-1">
                Total Price
            </div>
            <div class="col-1">
                Action
            </div>
        </div>
        <?php
            if(isset($_SESSION["cartList"])) {
                foreach($_SESSION["cartList"] as $prod) {
                    $sql = "SELECT * FROM products LEFT JOIN types AS t ON product_type=t.type_id LEFT JOIN prices AS p ON p.price_product_id=product_id INNER JOIN inventory AS i ON i.inventory_product_id=product_id WHERE p.price_end_timestamp IS NULL AND product_end_timestamp IS NULL AND i.inventory_product_count > 0 AND product_code=?";
                    $result = prepareSQL($conn, $sql, "s", $prod);
                    $item = mysqli_fetch_array($result);

                    echo '
                        <div class="row mt-3" id="'.$item["product_code"].'-row">
                            <div class="col-1 text-center">
                                <img class="cart-picture"src="./images/product_images/'.$item["product_image"].'"></img>
                            </div>
                            <div class="col">
                                <span class="cart-name">'.$item["product_name"].'</span>
                            </div>
                            <div class="col-1">
                                <input type="number" class="form-control" id="'.$item["product_code"].'-qty" name="" onclick="updateQty(\''.$item["product_code"].'\','.$item["price_amount"].','.htmlspecialchars(json_encode($_SESSION["cartList"])).')" onblur="updateQty(\''.$item["product_code"].'\','.$item["price_amount"].','.htmlspecialchars(json_encode($_SESSION["cartList"])).')" min="1" value="1">
                            </div>
                            <div class="col-1 text-center">
                                <span class="cart-name">₱ '.$item["price_amount"].'
                            </div>
                            <div class="col-1 text-center">
                                <span class="cart-name" id="'.$item["product_code"].'-total">₱ '.$item["price_amount"].'</span>
                            </div>
                            <div class="col-1 text-center">
                                <button type="button" class="btn btn-light"><img  class= "trash-can" src=".\images\resources\trash.png"></button>
                            </div>
                        </div>
                    ';
                }
            }
            else {
                echo '
                    <div class="row mt-3">
                        <div class="col text-center">
                            There is no product added to cart 
                        </div>
                    </div>
                ';
            }
        ?>
        <div class="row text-center">
            <div class="col-1">

            </div>
            <div class="col">
                TOTAL PRICE
            </div>
            <div class="col-1">
            </div>
            <div class="col-1">
            </div>
            <div class="col-1">
                Price
            </div>
            <div class="col-1">
            </div>
        </div>
        <!-- <div class="row mt-3">
            <div class="col" style="background-color: #ff0000;">
                Name
            </div>
            <div class="col" style="background-color: #00ff00;">
                <input type="number" class="form-control" name="" min="1" value="1">
            </div>
            <div class="col  text-center" style="background-color: #0000ff;">
                Amount
            </div>
            <div class="col  text-center" style="background-color: #ffff00;">
                Total Price
            </div>
            <div class="col-1 text-center">
                <button type="button" class="btn btn-light"><img  class= "trash-can" src=".\images\resources\trash.png"></button>
            </div>
        </div> -->
    </div>