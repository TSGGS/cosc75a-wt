<?php
    require("includes/db.php");
    require("templates/customer-header.php");
    echo '
        <title>Walang Tatak - Cart</title>
        <script src="https://kit.fontawesome.com/dbed6b6114.js" crossorigin="anonymous"></script>
		<script src="js/store.js"></script>
    ';
?>
    <div class="content mt-2" id="cart-item-area">
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
                echo '
                    <script>
                        initTotal('.json_encode($_SESSION["cartList"]).');
                    </script>
                ';
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
                                <input type="number" class="form-control" id="'.$item["product_code"].'-qty" name="" onclick="updateQty(\''.$item["product_code"].'\','.$item["price_amount"].')" onblur="updateQty(\''.$item["product_code"].'\','.$item["price_amount"].','.$item["inventory_product_count"].')" min="1" max="'.$item["inventory_product_count"].'" value="'.$_SESSION["itemCount"][$item["product_code"]].'">
                            </div>
                            <div class="col-1 text-center">
                                <span class="cart-name">₱ '.$item["price_amount"].'
                            </div>
                            <div class="col-1 text-center">
                                <span class="cart-name" id="'.$item["product_code"].'-total">₱ '.$item["price_amount"] * $_SESSION["itemCount"][$item["product_code"]].'</span>
                            </div>
                            <div class="col-1 text-center">
                                <button type="button" class="btn btn-light" onclick="deleteItem(\''.$item["product_code"].'\')"><img  class= "trash-can" src=".\images\resources\trash.png"></button>
                            </div>
                        </div>
                    ';
                }
            }
        ?>
        <div class="row text-center" id="grandTotalRow">
            <div class="col-1">
            </div>
            <div class="col">
                TOTAL PRICE
            </div>
            <div class="col-1">
            </div>
            <div class="col-1">
            </div>
            <div class="col-1" id="grandTotalDisplay">
                Calculating...
            </div>
            <div class="col-1">
            </div>
        </div>
        <div class="row mt-3" id="discountRow">
		    <div class="col-1">
            </div>
            <div class="col">
            </div>
			<div class="col">
            </div>
            <div class="col">
            </div>
            <div class="col">
            </div>
            <div class="col">
            </div>
            
			<div class="col">
            <label class="col-form-label" for="apply-discount-code">Discount Code</label>
            </div>
            <div class="col-2">
		        <input type="text" class="form-control" name="apply-discount-code" id="apply-discount-code">
            </div>
			<div class="col-1">
		        <button type="button" class="btn btn-dark" id="apply-discount" onclick="applyDiscount();" onchange="applyDiscount();">Apply</button>
                <button type="button" class="btn btn-danger" style="display:none;" id="remove-discount" onclick="removeDiscount();">Clear</button>
            </div>
        </div>
        <div class="row mt-3 mb-3" id="orderButtonRow">
		    <div class="col-1">
            </div>
			<div class="col">
            </div>
			<div class="col">
            </div>
			<div class="col">
            </div>
			<div class="col-3">
		        <button type="button" class="btn btn-dark" onclick="placeOrder();">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Place Order&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>
            </div>					   
        </div>
    </div>
<?php
    echo '
        <script>
            removeTotal();
            
            let elmt = document.getElementById("apply-discount-code");
            elmt.value = "'.(isset($_SESSION["discountCode"]) ? $_SESSION["discountCode"] : "").'";
            applyDiscount();
        </script>     
    ';