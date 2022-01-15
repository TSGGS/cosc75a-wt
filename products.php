<?php
    require("includes/db.php")
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Walang Tatak - Products</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/products.css">
      
        <script src="https://kit.fontawesome.com/dbed6b6114.js" crossorigin="anonymous"></script>
		<script src="store.js"></script>
    </head>
    <body>
        <div class = "products">
            <div class = "container">
                <h1 class = "lg-title">Our Products</h1>
                <p class = "text-light">masarap kahit... walang tatak</p>

                <div class = "product-items">
                <?php
                    $sql = "SELECT * FROM products LEFT JOIN types AS t ON product_type=t.type_id LEFT JOIN prices AS p ON p.price_product_id=product_id WHERE p.price_end_timestamp IS NULL AND product_end_timestamp IS NULL;";
                    $result = prepareSQL($conn, $sql);
                    while($resultRow = mysqli_fetch_array($result)) {
                        echo '
                        <div class = "product">
                            <div class = "product-content">
                                <div class = "product-img">
                                    <img src = "images/product_images/'.$resultRow["product_image"].'" alt = "product image">
                                </div>
                                <div class = "product-btns">
                                    <button type = "button" class = "btn-cart"> add to cart
                                        <span><i class = "fas fa-plus"></i></span>
                                    </button>
                                    <button type = "button" class = "btn-buy"> buy now
                                        <span><i class = "fas fa-shopping-cart"></i></span>
                                    </button>
                                </div>
                            </div>
        
                        <div class = "product-info">
                            <div class = "product-info-top">
                                <h2 class = "sm-title">'.$resultRow["type_name"].'</h2>
                                
                            </div>
                            <a href = "#" class = "product-name">'.$resultRow["product_name"].'</a>
                        
                            <a class = "product-price">₱ '.$resultRow["price_amount"].'</a>
                        </div>
        
                        <div cls = "off-info">
                        
                        </div>
                    </div>
                        ';
                    }
                ?>
                </div>
            </div>
        </div>


            </div>
        </div>
        
    </body>
</html>