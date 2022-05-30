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
            <div class="col">
                Name
            </div>
            <div class="col">
                Quantity
            </div>
            <div class="col">
                Amount
            </div>
            <div class="col">
                Total Price
            </div>
        </div>
        <?php
            foreach($_SESSION["cartList"] as $prod) {
                $sql = "SELECT * FROM ";
            }
        ?>
        <div class="row mt-3">
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
        </div>
    </div>