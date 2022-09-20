<?php
    require("../../includes/helper.php");
    require("../../includes/db.php");

    changeTitle("../templates/header.php", "Operation - WT");
    validateUserPage($_SESSION["tid"], $_SERVER["REQUEST_URI"]);

    $sql1 = "SELECT COUNT(order_total_price) as count FROM orders WHERE order_timestamp BETWEEN DATE_FORMAT(CURRENT_DATE()-6, '%Y-%m-%d') AND CURRENT_TIMESTAMP();";
    $mysql_result = prepareSQL($conn, $sql1);
    $result1 = mysqli_fetch_array($mysql_result);

    $sql2 = "SELECT COUNT(order_total_price) as count FROM orders WHERE order_timestamp BETWEEN DATE_FORMAT(CURRENT_DATE()-6, '%Y-%m-%d') AND CURRENT_TIMESTAMP() AND order_status_id = 2;";
    $mysql_result2 = prepareSQL($conn, $sql2);
    $result2 = mysqli_fetch_array($mysql_result2);

    $sql3 = "SELECT COUNT(order_total_price) as count FROM orders WHERE order_timestamp BETWEEN DATE_FORMAT(CURRENT_DATE()-6, '%Y-%m-%d') AND CURRENT_TIMESTAMP() AND order_status_id = 3;";
    $mysql_result3 = prepareSQL($conn, $sql3);
    $result3 = mysqli_fetch_array($mysql_result3);

    $sql4 = "SELECT COUNT(order_total_price) as count FROM orders;";
    $mysql_result4 = prepareSQL($conn, $sql4);
    $result4 = mysqli_fetch_array($mysql_result4);

    $sql5 = "SELECT COUNT(order_total_price) as count FROM orders;";
    $mysql_result5 = prepareSQL($conn, $sql5);
    $result5 = mysqli_fetch_array($mysql_result5);

    $sql6 = "SELECT COUNT(order_total_price) as count FROM orders;";
    $mysql_result6 = prepareSQL($conn, $sql6);
    $result6 = mysqli_fetch_array($mysql_result6);
?>
    <div class="container mt-3">
		<h1>Operation Dashboard</h1>
        <h2>All-Time</h2>
        <div class="row mt-4">
            <div class="col-3 border-hidden"><h3 class="align-bottom">Total Orders</h3></div>
            <div class="col border"><h3><?= $result4["count"] ?></h3></div>
        </div>
		<div class="row mt-4">
            <div class="col-3 border-hidden"><h3 class="align-bottom">Accepted Orders</h3></div>
            <div class="col border"><h3><?= $result5["count"] ?></h3></div>
        </div>
		<div class="row mt-4 mb-5">
            <div class="col-3 border-hidden"><h3 class="align-bottom">Rejected Orders</h3></div>
            <div class="col border"><h3><?= $result6["count"] ?></h3></div>
        </div>

        <h2>Weekly</h2>
         <div class="row mt-4">
            <div class="col-3 border-hidden"><h3 class="align-bottom">Total Orders</h3></div>
            <div class="col border"><h3><?= $result1["count"] ?></h3></div>
        </div>
		<div class="row mt-4">
            <div class="col-3 border-hidden"><h3 class="align-bottom">Accepted Orders</h3></div>
            <div class="col border"><h3><?= $result2["count"] ?></h3></div>
        </div>
		<div class="row mt-4 mb-5">
            <div class="col-3 border-hidden"><h3 class="align-bottom">Rejected Orders</h3></div>
            <div class="col border"><h3><?= $result3["count"] ?></h3></div>
        </div>
    </div>
<?php
    require("../templates/footer.php");