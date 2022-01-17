<?php
    require("includes/db.php");
    require("templates/customer-header.php");
    echo '
        <title>Walang Tatak - Products</title>
        <script src="https://kit.fontawesome.com/dbed6b6114.js" crossorigin="anonymous"></script>
		    <script src="store.js"></script>
    ';
?>

<div class="content mt-2">
  <h1 class="mt-3" id="page-title">Products</h1>
  <div class="product">
    <img class="product-image" src="images/product_images/20220115112631_61e23ee70ac70.JPG" alt="product-image">
    <div class="product-buttons">
      <button>Add to Cart</button>
    </div>
    <div class="product-info">
      <span class="product-category">CATEGORY</span>
      <span class="product-title">NEW YORK STYLE CHEESECAKE</span>
      <span class="product-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus ornare.</span>
    </div>
  </div>
</div>