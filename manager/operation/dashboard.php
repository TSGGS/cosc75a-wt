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
?>
    <div class="container mt-3">
		<h1>Operation Dashboard</h1>
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

		<h1>Deliverables</h1>
		<table class="table">
            <tr>
                <th>Company</th>
                <th>Contact</th>
                <th>Country</th>
            </tr>
            <tr>
                <td>Alfreds Futterkiste</td>
                <td>Maria Anders</td>
                <td>Germany</td>
            </tr>
            <tr>
                <td>Centro comercial Moctezuma</td>
                <td>Francisco Chang</td>
                <td>Mexico</td>
            </tr>
        </table>
		
    </div>
<?php
    require("../templates/footer.php");