<?php
    require("../../includes/helper.php");
    require("../../includes/db.php");

    changeTitle("../templates/header.php", "Marketing - WT");
    validateUserPage($_SESSION["tid"], $_SERVER["REQUEST_URI"]);
?>
    <div class="content mt-3">
        <div class="row mx-3">
            <div class="col mx-3">
                <h1>Promotions</h1>
                <table class="table" style="text-align: center;">
                    <thead>
                        <tr>
                            <th>Promotion Code</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Image</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sql = "SELECT promotion_code, promotion_start_timestamp, promotion_end_timestamp, promotion_image FROM promotions WHERE current_timestamp() BETWEEN promotion_start_timestamp AND promotion_end_timestamp;";
                            $promotion = prepareSQL($conn, $sql);
                            
                            if(mysqli_num_rows($promotion) > 0) {
                                while($result = mysqli_fetch_array($promotion)) {
                                    echo'
                                        <tr>
                                            <td>'.$result["promotion_code"].'</td>
                                            <td>'.$result["promotion_start_timestamp"].'</td>
                                            <td>'.$result["promotion_end_timestamp"].'</td>
                                            <td><a href="../../images/promotions/'.$result["promotion_image"].'" target="_blank">View</a></td>
                                        </tr>
                                    ';
                                }
                            } else {
                                    echo'
                                        <tr>
                                            <td colspan="4">There are no promotions running</td>
                                        </tr>
                                    ';
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="col mx-3">
                <h1>Discounts</h1>
                <table class="table" style="text-align: center;">
                    <thead>
                        <tr>
                            <th>Discount Code</th>
                            <th>Percentage</th>
                            <th>Start</th>
                            <th>End</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sql = "SELECT discount_code, discount_amount, discount_start_timestamp, discount_end_timestamp FROM discounts WHERE discount_start_timestamp < CURRENT_TIMESTAMP() AND discount_end_timestamp > CURRENT_TIMESTAMP()";
                            $discount = prepareSQL($conn, $sql);
                            
                            if(mysqli_num_rows($discount) > 0) {
                                while($result = mysqli_fetch_array($discount)) {
                                    echo'
                                        <tr>
                                            <td>'.$result["discount_code"].'</td>
                                            <td>'.$result["discount_amount"].'%</td>
                                            <td>'.$result["discount_start_timestamp"].'</td>
                                            <td>'.$result["discount_end_timestamp"].'</td>
                                        </tr>
                                    ';
                                }
                            } else {
                                    echo'
                                        <tr>
                                            <td colspan="4">There are no discounts running</td>
                                        </tr>
                                    ';
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php
    require("../templates/footer.php");