<?php
    session_set_cookie_params(0);
    session_start();

    $json_data = file_get_contents("php://input");
    $prodInfo = json_decode($json_data, true);

    switch($prodInfo["type"]) {
        case "cart":
            addCart($prodInfo["product"]);
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