<?php
    session_set_cookie_params(0);
    session_start();

    $json_data = file_get_contents("php://input");
    $prodInfo = json_decode($json_data, true);

    switch($prodInfo["type"]) {
        case "cart":
            addCart($prodInfo["product"]);
            break;

        case "price":
            getPrice($prodInfo["product"]);
            break;
        
        default:
            break;
    }

    function addCart(string $prod) {
        if(!isset($_SESSION["cartList"])) {
            $_SESSION["cartList"] = array();
        }

        if(!in_array($prod, $_SESSION["cartList"])) {
            array_push($_SESSION["cartList"], $prod);
            $_SESSION["cartCount"] = count($_SESSION["cartList"]);
        }

        $response = array(
            "count" => count($_SESSION["cartList"])
        );

        header("Content-type: application/json");
        echo json_encode($response);
    }

    function getPrice(string $prod) {
        require ("db.php");

        $sql = "SELECT * FROM products LEFT JOIN types AS t ON product_type=t.type_id LEFT JOIN prices AS p ON p.price_product_id=product_id INNER JOIN inventory AS i ON i.inventory_product_id=product_id WHERE p.price_end_timestamp IS NULL AND product_end_timestamp IS NULL AND i.inventory_product_count > 0 AND product_code=?";
        $result = prepareSQL($conn, $sql, "s", $prod);
        $item = mysqli_fetch_array($result);

        $response = array(
            "product" => $prod,
            "price" => $item["price_amount"]
        );

        header("Content-type: application/json");
        echo json_encode($response);
    }