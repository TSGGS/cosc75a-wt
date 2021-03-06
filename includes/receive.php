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

        case "delete":
            deleteItem($prodInfo["product"]);
            break;

        case "discount":
            checkDiscount($prodInfo["code"]);
            break;

        case "remove-discount":
            removeDiscount($prodInfo["code"]);
            break;

        case "order":
            setOrder($prodInfo["amount"],$prodInfo["list"]);
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

    function deleteItem(string $prod) {
        if(!isset($_SESSION["cartList"])) {
            $_SESSION["cartList"] = array();
        } else {
            if (in_array($prod, $_SESSION["cartList"])) {
                $index = array_search($prod, $_SESSION["cartList"]);
                unset($_SESSION["cartList"][$index]);
                unset($_SESSION["cartInfo"][$prod]);
                $_SESSION["cartList"] = array_merge($_SESSION["cartList"]);
                $_SESSION["cartCount"] = count($_SESSION["cartList"]);
            }
        }

        $response = array(
            "status" => "OK"
        );

        header("Content-type: application/json");
        echo json_encode($response);
    }

    function checkDiscount($code) {
        require ("db.php");

        $sql = "SELECT * FROM discounts WHERE discount_code=?";
        $result = prepareSQL($conn, $sql, "s", $code);

        $status = "INVALID";
        $percent = 0;

        $disc = mysqli_fetch_array($result);

        if(mysqli_num_rows($result) == 0) {
            $status = "INVALID";
        } else {
            $ll = date("Y-m-d H:i:s", strtotime($disc["discount_start_timestamp"]));
            if ($disc["discount_end_timestamp"] == NULL) {
                $hl = date("Y-m-d H:i:s", strtotime("9999-12-31 23:59:59"));
            } else {
                $hl = date("Y-m-d H:i:s", strtotime($disc["discount_end_timestamp"]));
            }
            $cv = date("Y-m-d H:i:s");

            if($ll <= $cv && $hl >= $cv) {
                $status = "VALID";
                $percent = $disc["discount_amount"];
                $_SESSION["discountCode"] = $code;
            }
        }

        $response = array(
            "status" => $status,
            "percent" => $percent
        );

        header("Content-type: application/json");
        echo json_encode($response);
    }

    function removeDiscount($code) {
        unset($_SESSION["discountCode"]);
    }

    function setOrder($amount, $list) {
        $_SESSION["cartInfo"] = $list;
        $_SESSION["GRANDTOTAL"] = $amount;
    }