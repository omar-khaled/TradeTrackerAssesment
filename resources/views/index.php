<html>
	<head>
		<title>Products - Trade Tracker</title>
		<link rel="stylesheet" href="<?php echo asset('css/app.css'); ?>" type="text/css">
		<script type='text/javascript' src="<?php echo asset('js/app.js'); ?>"></script>
		<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
	</head>
	<body>
		<div class="container" class="padding-bottom-15px">
			<?php
				// getting fields from object sent from controller to view
				$productsPerPage = $data['productsPerPage'];
				$n = $data['n'] * $productsPerPage;
				$pageNumber = $data['n'];
				$reader = $data['reader'];
				$url = $data['url'];
				$doc = new \DOMDocument;
				// initializing while loop counter used for pagination
				$i = 1;
				// initializing boolean used to determine when xml products ended
				$endFeed = false;
			?>
				<div class="col-lg-12" align="center">
					<!-- form used to request next page depending on number of products per page selected and current page number and csrf token used for securing the request -->
					<form id="form" action="/products" method="post">
						<input type="hidden" name="feedUrl" value="<?php echo $url; ?>">
						<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">
						<!-- pagination: if first page hide previous page link else show both previous page, next page links and set first and last products index to display in the current page -->
						<div id="top_pagination">
							<?php if($pageNumber > 1) { 
								$end = $n;
								$start = $end - $productsPerPage;
							?>
								<button class="link-button" onclick="previousPage()"><-- Previous <?php echo $productsPerPage; ?> product</button> - products from <?php echo ($start)." ~ ".($end); ?>  -  <button class="link-button" onclick="nextPage()"> next <?php echo $productsPerPage; ?> product --></button>
							<?php } else { 
								$start = 1;
								$end = $productsPerPage;
							?>
								Products from <?php echo ($start)." ~ ".($end); ?>  -  <button class="link-button" onclick="nextPage()"> next <?php echo $productsPerPage; ?> product --></button>
							<?php } ?>
						</div>
						<!-- drop down to select products per page - which on change set the new products per page number and calculate the new start index of the products to display -->
						<div align="center" id="productsNumberDropdown">
							Products per page:
							<select id="productsPerPage" name="productsPerPage" onchange="productsPerPageChange()">
							  	<option value="100">100</option>
							  	<option value="250">250</option>
							  	<option value="500">500</option>
							  	<option value="1000">1000</option>
							  	<option value="5000">5000</option>
							</select>
						</div>
					</form>
				</div>
			<?php
				// move to the first <product /> node
				while ($reader->read() && $reader->name !== 'product');
				// loop through the products between start and end indexes and displays them
				while ($reader->name === 'product' && $i <= $end )
				{
					// loop to skip first n-elemets if needed to display the correct products indexes
					while($i <= $start) {
						$i++;
						$reader->next('product');
						continue;
					}
						try 
						{
							// parsing current node to create product object from xml
					    	$product = simplexml_import_dom($doc->importNode($reader->expand(), true));
					    }
					    catch(Exception $e)
					    {
					    	// products feed reached its end, in order to display a friendly message notifiying the user with a link to previous page and break outside of the while loop
					    	$endFeed = true;
					    	break;
					    }
					?>
						<!-- individual product div -->
						<div class="col-lg-12 product">
							<div class="col-lg-3 margin-top-25px">
								<img src="<?php echo $product->imageURL; ?>"><br>
							</div>
							<div class="col-lg-9">
								<h3><?php echo $product->name; ?></h3>
								<p><b>Product ID:</b> <?php echo $product->productID; ?></p>
								<p><b>Price:</b> <?php echo $product->price." ".$product->price['currency']; ?></p>
								<p><b>Description:</b> <?php echo $product->description; ?></p>
								<p><a data-target="#<?php echo $product->productID;?>" data-toggle="modal">More details!</a></p>
								<!-- extra product details modal -->
								<div id="<?php echo $product->productID; ?>" class="modal fade" role="dialog">
								  	<div class="modal-dialog">
										<!-- Modal header-->
										<div class="modal-content">
										  	<div class="modal-header" align="center">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h4 class="modal-title"><?php echo $product->name; ?> extra details</h4>
										  	</div>
										  	<!-- Modal body -->
										  	<div class="modal-body">
												<p><b>Categories:</b> 
													<!-- loop through categories list of product object -->
													<?php foreach ($product->categories as $cat) { 
														if($cat->category == end($product->categories)) {
															echo $cat->category;
														} else {
															echo $cat->category." - ";	
														}
													} ?>
												</p>
												<!-- loop through additional details of product object -->
												<?php foreach ($product->additional->field as $additional) { ?>
													<p>
														<?php if(strlen($additional) == 0) {
															continue;
														}
														// check if additional node name contains 'url' to display it as a link, else display as simple string
														if (strpos(strtolower($additional['name']), 'url') !== false){
															echo "<a href=".$additional." target='_blank'>Click here to open".$additional['name']."</a><br>";
															continue;
														}
														echo "<b>".$additional['name'].":</b> ".$additional."<br>"; ?>
													</p>
												<?php } ?>
										  	</div>
										  	<!-- Modal footer -->
										  	<div class="modal-footer">
											  	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
										  	</div>									  	
										</div>
								  	</div>
								</div>
								<!-- add button to open product page in a new tab -->
								<p><a class="btn btn-default" href="<?php echo $product->productURL; ?>" target="_blank">Open product page</a></p>
							</div>
						</div>						
				<?php
			   		// go to next <product />
			    	$reader->next('product');
			    	// increment counter used to display correct products between $start and $end indexes
			    	$i++;
				}
				// close xml reader
				$reader->close();
				// check if products feed ended
				if($endFeed)
				{
				?>
					<div class="col-lg-9" align="center">
						<button class="link-button" onclick="previousPage()"><-- Previous <?php echo $productsPerPage; ?> product</button>-  Products feed ended!
					</div>
				<?php
				}
				?>
				<!-- pagination: if first page hide previous page link else show both previous page, next page links and set first and last products index to display in the current page -->
				<div class="col-lg-12 margin-bottom-25px" align="center" id="bottom_pagination">
					<?php if($pageNumber > 1) { ?>
						<button class="link-button" onclick="previousPage()"><-- Previous <?php echo $productsPerPage; ?> product</button> - products from <?php echo ($start)." ~ ".($end); ?>  -  <button class="link-button" onclick="nextPage()"> next <?php echo $productsPerPage; ?> product --></button>
					<?php } else { ?>
						Products from <?php echo ($start)." ~ ".($end); ?>  -  <button class="link-button" onclick="nextPage()"> next <?php echo $productsPerPage; ?> product --></button>
					<?php } ?>
				</div>
		</div>
	</body>
	<script type="text/javascript">
		// select products per page value
		var productsPerPage = <?php echo json_encode($productsPerPage); ?>;
		$('#productsPerPage').val(productsPerPage);
		// check if end of products
		var end_bool = <?php echo json_encode($endFeed); ?>;
		// if products feed ended --> hide pagination and products per page drop down menu
		if(end_bool)
		{
			$("#top_pagination").hide();
			$("#bottom_pagination").hide();
			$("#productsNumberDropdown").hide();
		}
		// move to previous page
		function previousPage() {
			$form = $("#form");
			$form.append('<input type="hidden" name="pageNumber" value="<?php echo $pageNumber-1; ?>">');
			$form.submit();
		}
		// move to next page
		function nextPage(){
			$form = $("#form");
			$form.append('<input type="hidden" name="pageNumber" value="<?php echo $pageNumber+1; ?>">');
			$form.submit();
		}
		// change number of products per page, calculate the correct index of the first product to display
		function productsPerPageChange() {
			$form = $("#form");
			var first_product_index = <?php echo json_encode($start); ?>;
			var selected_productPerPage = $('#productsPerPage').val();
			var newStartPage = Math.floor(first_product_index / selected_productPerPage) + 1;
			$form.append('<input type="hidden" name="pageNumber" value="' +  newStartPage + '">');
			$form.submit();
		}
	</script>
</html>