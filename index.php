<?php
    require("templates/customer-header.php");
    echo '
        <title>Walang Tatak</title>
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
    require("templates/footer.php");
?>