<!DOCTYPE> 
<?php
Include("includes/db.php");
?>
<html>
<head>
	<title>Inserting Product</title>
	<script src="//tinymce.cachefly.net/4.1/tinymce.min.js"></script>
	<script>
		Tinymce.init({selector:'textarea'});
	</script>
	<style >
		.tblform{
			margin-top: 100px;
		}
	</style>
</head>
<meta charset="utf-8">
<title>WALANG TATAK</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&display=swap" rel="stylesheet">
</head>

<body>

    <div class="wrapper">
    <div class="logo">
        <img src="images/logo.jpg" alt="logo">
        </div>
       <div class="coupon">
  <div class="container">
  </div>
  <img src="images/walangt.jpg" alt="Avatar" style="width:100%;">
  <div class="container" style="background-color:white">
    <h2><b>20% OFF YOUR PURCHASE</b></h2>
  </div>
  <div class="container">
    <p>Use Promo Code: <span class="promo">WT2022</span></p>
    <p class="expire">Expires: Feb28,2022</p>
  </div>
</div>
<body bgcolor="white">
	<form action="insert_product.php" method="post" enctype="multipart/form-data">
		<table class="tblform" align="center" width="750"  border="2" bgcolor="brown" >
			<tr align="center">
				<td colspan="7"><h2>WALANG TATAK DISCOUNT</h2></td>
			</tr>
			<tr>
				<td align="right"><b>Product Title:</b></td>
				<td><input type="text" name="product_title" size="60" required></td>
			</tr>
			<tr>
				<td align="right"><b>Category:</b></td>
				<td>
					<select name="product_cat">
						<option>Select Category</option>
						<?php
						$get_cats="select * from category";
						$run_cats=mysqli_query($con,$get_cats);
						while ($row_cats=mysqli_fetch_array($run_cats)) {
							$cat_id=$row_cats['cat_id'];
							$cat_title=$row_cats['cat_title'];
							echo "<option value='$cat_id'>$cat_title</option>";
						}

						?>
					</select>
				</td>

			</tr>
			
			<tr>

				<td align="right"><b>Image:</b></td>
				<td><input type="file" name="product_image"/></td>

			</tr>
			<tr>
			</tr>
			<tr>
				<td align="right"><b>Description:</b></td>
				<td><textarea name="product_desc" cols="20"
					rows="10"></textarea></td>
			</tr>
			<tr>

				<td align="right"><b>Code:</b></td>
				<td><input type="text" name="product_code" size="50" /></td>

			</tr>
			<tr align="center">
				<td colspan="7"><input type="submit" name="insert_post" value="Insert Now"></td>
			</tr>

		</table>
	</form>
</body>
</html>
<?php

			
				If(isset($_POST['insert_post'])){
				//getting the text data from the fields
				$product_title=$_POST['product_title']; 
				$product_cat=$_POST['product_cat'];  
				//getting the image from the field 
				$product_image=$_FILES['product_image']['name']; 
				$product_image_tmp=$_FILES['product_image']['tmp_name']; 
				$product_desc=$_POST['product_desc']; 
				$product_code=$_POST['product_code']; 

				//To push the data to the database 
				$insert_product = " INSERT INTO product (product_title,product_cat, product_brand, product_image, product_desc, product_code) VALUES('$product_title','$product_cat','$product_image','$product_desc','$product_code')"; 
				move_uploaded_file($product_image_tmp,"product_image/$product_image");
				
				$insert_pro=mysqli_query($con, $insert_product);

				If($insert_pro){ 
				Echo"<script>alert('Product has been inserted!')</script>"; 
				Echo"<script>windows.open('insert_product.php','_self')</script>"; 
				}
			} 
		?> 
