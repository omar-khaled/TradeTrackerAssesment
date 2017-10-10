<html>
	<head>
		<title>Feed - Trade Tracker</title>
		<link rel="stylesheet" href="<?php echo asset('css/app.css')?>" type="text/css"> 
		<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
	</head>
	<body>
		<div class="container">
			<div class="col-lg-12 margin-top-5" align="center">
				<!-- form used to request FeedController@fetchXml method used to parse xml url, with some default values (page = 1 & products per page = 100)--> 
				<form id="form" action="/products" method="post">
					<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">
					<input type="hidden" name="pageNumber" value="1">
					<input type="hidden" name="productsPerPage" value="100">
					<div class="form-group margin-top-50px">
						<input type="text" class="form-control width-75" name="feedUrl" placeholder="Enter Trade Traker products feed URL">
					</div>
					<button type="submit" class="btn btn-default" onclick="showLoading();">Submit</button>
				</form>
				<!-- loading gif -->
				<div id="loading_div" align="center" style="display: none;">
					<img src="<?php echo asset('pics/loading.gif')?>" height="50px;">
					<div class="caption center-block margin-top-20px">Loading products from XML...</div>
				</div>
			</div>
		</div>
	</body>
	<script type="text/javascript">
		// show loading gif after clicking on submit till form response is received
		function showLoading() {
			$("#loading_div").show();
		}
	</script>
</html>