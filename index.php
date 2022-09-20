<?php
    require("includes/db.php");
    require("templates/customer-header.php");
    echo '
        <title>Walang Tatak</title>
        <script src="js/store.js"></script>
    ';
?>

    <div class="welcome-div">
        <h1 class="welcome-text index">WALANG TATAK</h1>
        <h3 class="welcome-subtext">masarap kahit... walang tatak</h3>
    </div>
    <div class="types">
        <img class="type-image" src="images/resources/cookies.jpg" alt="cookies">
        <span class="type-name" style="top: 0; left: 0; padding-top: 1px; padding-left: 35px;">COOKIES</span>
    </div>
    <div class="types">
        <img class="type-image" src="images/resources/crinkles.jpeg" alt="cookies">
        <span class="type-name" style="top: 70%; left: 0; padding-left: 35px;">CRINKLES</span>
    </div>
    <div class="types">
        <img class="type-image" src="images/resources/cakes.jpg" alt="cookies">
        <span class="type-name" style="top: 0; left: 72%; padding-top: 1px; padding-right: 35px;">CAKES</span>
    </div>
    <?php
        $sql = "SELECT promotion_image FROM promotions WHERE current_timestamp() BETWEEN promotion_start_timestamp AND promotion_end_timestamp;";
        $result = prepareSQL($conn, $sql);

        if(mysqli_num_rows($result) > 0) {
            $promotion = mysqli_fetch_array($result);

            echo '
                <script>document.body.style.overflow = "hidden";</script>
                <div class="my-modal" id="modal" onclick="destroyPromotion();"></div>
                <img src="./images/promotions/'.$promotion["promotion_image"].'" id="promotion" onclick="destroyPromotion();">
            ';
        }
    ?>

<?php
    require("templates/footer.php");
?>