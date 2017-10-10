<html>
	<head>
		<title>Error - Trade Tracker</title>
		<link rel="stylesheet" href="<?php echo asset('css/app.css')?>" type="text/css"> 
	</head>
	<body>
		<div class="container">
			<!-- display error message and link to home.php to re-submit feed url -->
			<div class="col-lg-12" align="center" style="margin-top: 5%;">
				<p>Oops! something wrong happened!! <text style="color: red">(<?php echo $exception->getMessage(); ?>)</text></p>
				<p>Please <a href="/">click here to try again</a></p>
			</div>
		</div>
	</body>
</html>