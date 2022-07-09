<?php
    require("includes/db.php");
    require("templates/customer-header.php");

    // if(!isset($_SERVER['HTTP_REFERER'])){
    //     header("Location: index.php");
    //     die();
    // }

    echo '
        <title>Thank You | Walang Tatak</title>
		<script src="js/store.js"></script>
    ';

    unset($_SESSION["GRANDTOTAL"], $_SESSION["discountCode"]);
?>
    <div class="content mt-2">
        <span id="thank-you">THANK YOU</span>
        <img src="./images/resources/checked.png" alt="check" id="check">
        <span id="redirect-notice">You will be redirected to the homepage shortly.</span>
    </div>
    <script>
        setTimeout(() => {
            window.location.href = "index.php";
        }, 5000);
    </script>
    <?php 
        include("templates/footer.php");

    ?>