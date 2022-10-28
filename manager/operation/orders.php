<?php
    require("../../includes/helper.php");
    require("../../includes/db.php");
    changeTitle("../templates/header.php", "View Orders");
    validateUserPage($_SESSION["tid"], $_SERVER["REQUEST_URI"]);
?>
<div class="my-modal d-none" id="modal"></div>
<div class="d-none" id="orderFull">
    <img id="close" src="..\..\images\resources\close.png" onclick="removeModal();">
    <div id="info">
        <h2 id="orderID"></h2>
        <br>
        <div class="row">
            <div class="col-3"><h4>Name</h4></div>
            <div class="col"><h4 id="orderName"></h4></div>
        </div>
        <div class="row">
            <div class="col-3"><h4>Address</h4></div>
            <div class="col"><h4 id="orderAddress"></h4></div>
        </div>
        <div class="row">
            <div class="col-3"><h4>Contact No.</h4></div>
            <div class="col"><h4 id="orderContact"></h4></div>
        </div>
        <div class="row">
            <div class="col-3"><h4>Price</h4></div>
            <div class="col"><h4 id="orderPrice"></h4></div>
        </div>
        <div class="row">
            <div class="col-3"><h4>Delivery</h4></div>
            <div class="col"><h4 id="orderDelivery"></h4></div>
        </div>
        <div class="row">
            <div class="col-3"><h4>Order(s)</h4></div>
            <div class="col"><h4 id="orderList">
            </h4></div>
        </div>
    </div>
</div>

<div class="container mt-3">
    <div class="mt-3" id="orderSet">
            <h2 class="col-title" id="orderSetTitle">Orders</h2>
        <table class="table table-light table-hover" id="orderTable" style="text-align: center;">
            <thead>
                <th>Order ID</th>
                <th>Customer Name</th>
                <th>Total Price</th>
                <th>Delivery Date and Time</th>
                <th>Action</th>
            </thead>
            <tbody>
            <?php
                $sql = 'SELECT o.*, s.status_name, CONCAT(e.employee_firstname, " ", e.employee_lastname) AS employee_name FROM orders AS o LEFT JOIN status AS s ON s.status_id=o.order_status_id LEFT JOIN employees AS e ON e.employee_id=o.order_handler_employee_id ORDER BY o.order_timestamp DESC;';
                $result = prepareSQL($conn, $sql);

                if(mysqli_num_rows($result) == 0) {
                    echo '
                            <tr>
                                <td colspan=5>There is no orders</td>
                            </tr>
                        ';
                } else {
                    while($row = mysqli_fetch_array($result)) {
                        echo '
                            <tr>
                                <td id="order_id">'.$row["order_id"].'</td>
                                <td>'.$row["order_customer_name"].'</td>
                                <td>'.$row["order_total_price"].'</td>
                                <td>'.$row["order_delivery_date"].'</td>
                                <td>
                                    <button type="button" class="btn btn-light" onclick="viewOrder(\''.$row["order_id"].'\', \''.$row["order_customer_name"].'\',\''.$row["order_mobile_number"].'\', \''.$row["order_address"].'\', \''.$row["order_total_price"].'\', \''.$row["order_status_id"].'\', \''.$row["order_delivery_date"].'\', \''.$row["status_name"].'\', \''.$row["employee_name"].'\');"><img  class= "btn-icon" src="..\..\images\resources\view.png"></button>
                                </td>
                            </tr>
                        ';
                    }
                }
            ?>
            </tbody>
        </table>
    </div>
</div>