<?php
    session_set_cookie_params(0);
    session_start();

    $json_data = file_get_contents("php://input");
    $data = json_decode($json_data, true);

    switch($data["type"]) {
        case "cartAdd":
            addCart($data["product"]);
            break;

        case "updateQty":
            updateCartItem($data["product"], $data["quantity"]);

        case "updateTotal":
            updateTotal();
            break;

        case "delete":
            deleteItem($data["product"]);
            break;

        case "discountApply":
            checkDiscount($data["code"]);
            break;

        case "discountRemove":
            removeDiscount();
            break;

        case "orderList":
            getOrders($data["id"]);
            break;

        default:
            break;
    }

    function addCart(string $prod) {
        if(!isset($_SESSION["cartList"])) {
            $_SESSION["cartList"] = array();
        } else {
            if(array_key_exists($prod, $_SESSION["cartList"])) {
                if($_SESSION["cartList"][$prod]["count"] <= $_SESSION["cartList"][$prod]["max"]) {
                    $_SESSION["cartList"][$prod]["count"]++;
                }
            } else {
                require ("db.php");

                $sql = "SELECT p.price_amount, i.inventory_product_count FROM products LEFT JOIN types AS t ON product_type=t.type_id LEFT JOIN prices AS p ON p.price_product_id=product_id INNER JOIN inventory AS i ON i.inventory_product_id=product_id WHERE p.price_end_timestamp IS NULL AND product_end_timestamp IS NULL AND i.inventory_product_count > 0 AND product_code=?";
                $result = prepareSQL($conn, $sql, "s", $prod);
                $item = mysqli_fetch_array($result);

                $_SESSION["cartList"][$prod] = array();
                $_SESSION["cartList"][$prod]["count"] = 1;
                $_SESSION["cartList"][$prod]["price"] = $item["price_amount"];
                $_SESSION["cartList"][$prod]["max"] = $item["inventory_product_count"];
            }
        }

        $response = array(
            "count" => count($_SESSION["cartList"])
        );

        header("Content-type: application/json");
        echo json_encode($response);
    }

    function updateCartItem($prod, $qty) {
        if($qty == 0) {
            deleteItem($prod);
        } else {
            $_SESSION["cartList"][$prod]["count"] = $qty;
        }
    }

    function updateTotal() {
        $grandTotal = 0;
        $discount = $_SESSION["cartInfo"]["dAmount"];

        foreach($_SESSION["cartList"] as $item) {
            $total = 0;

            $total = $item["price"] * $item["count"];
            $grandTotal += $total;
        }

        $grandTotal = $grandTotal - ($grandTotal * ($discount / 100));
        $_SESSION["cartInfo"]["grandTotal"] = ceil($grandTotal);

        $response = array(
            "grandTotal" => $_SESSION["cartInfo"]["grandTotal"]
        );

        header("Content-type: application/json");
        echo json_encode($response);
    }

    function deleteItem($prod) {
        if (array_key_exists($prod, $_SESSION["cartList"])) {
            unset($_SESSION["cartList"][$prod]);
            $_SESSION["cartList"] = array_merge($_SESSION["cartList"]);

            $response = array(
                "count" => count($_SESSION["cartList"])
            );
    
            header("Content-type: application/json");
            echo json_encode($response);
        }
    }

    function checkDiscount($code) {
        require ("db.php");

        $sql = "SELECT * FROM discounts WHERE discount_code=?";
        $result = prepareSQL($conn, $sql, "s", $code);

        $status = "INVALID";

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
                $_SESSION["cartInfo"]["dAmount"] = $disc["discount_amount"];
                $_SESSION["cartInfo"]["dCode"] = $code;
            }
        }

        $response = array(
            "status" => $status
        );

        header("Content-type: application/json");
        echo json_encode($response);
    }

    function removeDiscount() {
        $_SESSION["cartInfo"]["dAmount"] = 0;
        $_SESSION["cartInfo"]["dCode"] = null;
    }

    function getOrders($oid) {
        require ("db.php");
        $sql = "SELECT o.order_product_quantity, p.product_name FROM order_items AS o LEFT JOIN products AS p ON o.order_product_id=p.product_id WHERE order_id=?";
        $result = prepareSQL($conn, $sql, "i", $oid);
        $list = "";
        while($row = mysqli_fetch_array($result)) {
            $listItem = $row["order_product_quantity"]." x ".$row["product_name"]."<br>";
            $list = $list . $listItem;
        }
        $response = array(
            "orders" => $list
        );
        header("Content-type: application/json");
        echo json_encode($response);
    }