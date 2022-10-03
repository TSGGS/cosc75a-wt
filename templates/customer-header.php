<!DOCTYPE html>
<?php
    session_start();
    if(!isset($_SESSION["cartCount"])) {
        $count = 0;
    } else {
        $count = $_SESSION["cartCount"];
    }

    if(!isset($_SESSION["itemCount"])) {
        $_SESSION["itemCount"] = array();
    }
?>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="css/customer-header.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    </head>

    <body>
        <div class="wrapper">
            <img class="logo" src="images/resources/logo.jpg" alt="logo">
            <ul class="nav-area">
                <li><a href="index.php">Home</a></li>
                <li><a href="product.php">Products</a></li>
                <li><a href="cart.php">Cart</a><span id="cart-count"><?php echo $count; ?></span></li>
            </ul>
        </div>