<?php include "header/nav.php" ?>
<?php include "config/config.php" ?>

<?php 

	// FOR SINGLE PRODUCTS
	$prodId = $_GET['prodId'];

	$single_product_query = "SELECT * FROM `products` WHERE prod_id = :prodId";
	$single_product_prepare = $connection->prepare($single_product_query);
	$single_product_prepare->bindParam(':prodId',$prodId);
	$single_product_prepare->execute();

	$single_product = $single_product_prepare->fetch(PDO::FETCH_ASSOC);

	print_r($single_product);



	// FOR RELATED PRODUCTS

	$related_products_query = "SELECT * FROM `products` WHERE prod_id != :prodId and prod_categories = 				:category";
	$related_products_prepare = $connection->prepare($related_products_query);
	$related_products_prepare->bindParam(':prodId',$prodId);
	$related_products_prepare->bindParam(':category',$single_product['prod_categories']);
	$related_products_prepare->execute();

	$related_products = $related_products_prepare->fetchAll(PDO::FETCH_ASSOC);

	print_r($related_products);


	// ADD TO CART START

	
		if(isset($_POST['cart']))
		{

			if(isset($_SESSION['userId']))
			{

			$price_input = $_POST['priceInput'];
			$size = $_POST['size'];
			$quantity = $_POST['quantity'];

			$cart_query = "INSERT INTO `cart`(`prod_name`, `prod_description`, `prod_price`, `prod_quantity`, `prod_size`, `prod_image`, 						`cart_user_id`) VALUES (:prodName, :prodDescription, :prodPrice, :prodQuantity, :prodSize, :prodImage, :cartUser)";
			$cart_prepare = $connection->prepare($cart_query);
			$cart_prepare->bindParam(':prodName', $single_product['prod_name']);
			$cart_prepare->bindParam(':prodDescription', $single_product['prod_description']);
			$cart_prepare->bindParam(':prodPrice', $price_input);
			$cart_prepare->bindParam(':prodQuantity', $quantity);
			$cart_prepare->bindParam(':prodSize', $size);
			$cart_prepare->bindParam(':prodImage', $single_product['prod_image']);
			$cart_prepare->bindParam(':cartUser', $_SESSION['userId']);

			$cart_prepare->execute();

		}
		else
		{
			echo "<script>alert('Kindly Login for Adding products into the cart')</script>";
		}
	}
	






?>


    <section class="home-slider owl-carousel">

      <div class="slider-item" style="background-image: url(images/bg_3.jpg);" data-stellar-background-ratio="0.5">
      	<div class="overlay"></div>
        <div class="container">
          <div class="row slider-text justify-content-center align-items-center">

            <div class="col-md-7 col-sm-12 text-center ftco-animate">
            	<h1 class="mb-3 mt-5 bread">Product Detail</h1>
	            <p class="breadcrumbs"><span class="mr-2"><a href="index.php">Home</a></span> <span>Product Detail</span></p>
            </div>

          </div>
        </div>
      </div>
    </section>

    <section class="ftco-section">
    	<div class="container">
    		<div class="row">
    			<div class="col-lg-6 mb-5 ftco-animate">
    				<a href="images/menu-2.jpg" class="image-popup"><img src="images/<?php echo $single_product['prod_image'] ?>" class="img-fluid" alt="Colorlib Template"></a>
    			</div>
    			<div class="col-lg-6 product-details pl-md-5 ftco-animate">
    				<h3><?php echo $single_product['prod_name'] ?></h3>
    				<p class="price"><span id="price">$<?php echo $single_product['prod_price'] ?></span></p>
					<form action="<?php $_SERVER['PHP_SELF'] ?>"  method="post">

					<input type="hidden" name="priceInput" id="priceInput" value="<?php echo $single_product['prod_price'] ?>">
    				<p><?php echo $single_product['prod_description'] ?></p>
    				
						<div class="row mt-4">
							<div class="col-md-6">
								<div class="form-group d-flex">
		              <div class="select-wrap">
	                  <div class="icon"><span class="ion-ios-arrow-down"></span></div>
	                  <select name="size" id="size" class="form-control">
	                  	<option value="Small">Small</option>
	                    <option value="Medium">Medium</option>
	                    <option value="Large">Large</option>
	                    <option value="eLarge">Extra Large</option>
	                  </select>
	                </div>
		            </div>
							</div>
							<div class="w-100"></div>
							<div class="input-group col-md-6 d-flex mb-3">
	             	<span class="input-group-btn mr-2">
	                	<button type="button" class="quantity-left-minus btn"  data-type="minus" data-field="">
	                   <i class="icon-minus"></i>
	                	</button>
	            		</span>
	             	<input type="text" id="quantity" name="quantity" class="form-control input-number" value="1" min="1" max="100">
	             	<span class="input-group-btn ml-2">
	                	<button type="button" class="quantity-right-plus btn" data-type="plus" data-field="">
	                     <i class="icon-plus"></i>
	                 </button>
	             	</span>
	          	</div>
				 

          	</div>
          	<p><input type="submit" name ="cart" value="Add to Cart" class="btn btn-primary py-3 px-5"></p>
			  </form>
    			</div>
    		</div>
    	</div>
    </section>

    <section class="ftco-section">
    	<div class="container">
    		<div class="row justify-content-center mb-5 pb-3">
          <div class="col-md-7 heading-section ftco-animate text-center">
          	<span class="subheading">Discover</span>
            <h2 class="mb-4">Related products</h2>
            <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
          </div>
        </div>
        <div class="row">
   
		<?php foreach($related_products as $product){ ?>
			
			<div class="col-md-3">
        		<div class="menu-entry">
    					<a href="#" class="img" style="background-image: url(images/<?php echo $product['prod_image'] ?>);"></a>
    					<div class="text text-center pt-4">
    						<h3><a href="#"><?php echo $product['prod_name'] ?></a></h3>
    						<p><?php echo $product['prod_description'] ?></p>
    						<p class="price"><span>$<?php echo $product['prod_price'] ?></span></p>
    						<p><a href="product-single.php?prodId=<?php echo $product['prod_id'] ?>" class="btn btn-primary btn-outline-primary">Show</a></p>
    					</div>
    				</div>
        	</div>

			<?php } ?>




        </div>
    	</div>
    </section>

	<?php include "header/footer.php" ?>


  <script>
		$(document).ready(function(){

		var quantitiy=0;
		   $('.quantity-right-plus').click(function(e){
		        
		        // Stop acting like a button
		        e.preventDefault();
		        // Get the field name
		        var quantity = parseInt($('#quantity').val());
		        
		        // If is not undefined
		            
		            $('#quantity').val(quantity + 1);

		          
		            // Increment
		        
		    });

		     $('.quantity-left-minus').click(function(e){
		        // Stop acting like a button
		        e.preventDefault();
		        // Get the field name
		        var quantity = parseInt($('#quantity').val());
		        
		        // If is not undefined
		      
		            // Increment
		            if(quantity>0){
		            $('#quantity').val(quantity - 1);
		            }
		    });
		    
		});




		const size = document.getElementById('size');
		const price = document.getElementById('price');
		const priceInput = document.getElementById('priceInput');

		size.addEventListener('change',()=>{
			console.log(size.value);

			if(size.value === 'Small')
			{
				price.innerHTML = '$<?php echo $single_product['prod_price'] ?>';
				priceInput.value = '<?php echo $single_product['prod_price'] ?>';
			}
			else if(size.value === 'Medium')
			{
				price.innerHTML = '$<?php echo $single_product['prod_price'] + 5 ?>';
				priceInput.value = '<?php echo $single_product['prod_price'] + 5 ?>';

			}
			else if(size.value === 'Large')
			{
				price.innerHTML = '$<?php echo $single_product['prod_price'] + 10 ?>';
				priceInput.value = '<?php echo $single_product['prod_price'] + 10 ?>';

			}
			else if(size.value === 'eLarge')
			{
				price.innerHTML = '$<?php echo $single_product['prod_price'] + 15 ?>';
				priceInput.value = '<?php echo $single_product['prod_price'] + 15 ?>';

			}










		})













	</script>

    
  </body>
</html>