<?php

$servername = "localhost";
$username = "mysql";
$password = "";
$db = "test";
// Create connection
$conn = new mysqli($servername, $username, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


/*$sql = "CREATE DATABASE IF NOT EXISTS myDB";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}
*/
// create table in php
$sql = "CREATE TABLE IF NOT EXISTS item_info (_id INT(32) AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(256),
description VARCHAR(256),
img_url VARCHAR(256)
)";
if ($conn->query($sql) !== TRUE) {
    echo "Error creating database: " . $conn->error;
}

?>
<!DOCTYPE html>
<html lang="en">

	<head>
		<title>Kitty Bunny Pony</title>
		<!--bootstrap-css-->
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<!--My css style-->
		<link rel="stylesheet" href="css/styles.css">
		<!-- title icon-->
		<link type="image/x-icon" rel="icon" href="images/icon.jpg">

	</head>

	<body class="bg">

		<div class="container">
			<h1>Kitty Bunny Pony</h2>
			<div class="row text-centr">

				<!--link to management page -->
  				<section class="col-lg-12 col-md-12 col-sm-12 col-12">

  					<?php 
  					echo '<a href="management.php">'
  					?>
					
						<button type="button" class="btn my-button" >
  				          Management page
			            </button>
			        </a>
					
				</section>
				
			<?php
			$sql = "SELECT name, description, img_url FROM item_info;";
			$result = $conn->query($sql);
			
			if ($result->num_rows > 0) {
				// output data of each row
				while($row = $result->fetch_assoc()) {
					$name =  $row["name"];
					$description =  $row["description"];
					$img_url = $row["img_url"];
					echo("<section class=\"col-lg-4 col-md-4 col-sm-6 col-12\">");
					echo(sprintf("<img class=\"item\" src=\"%s\" alt=\"Icon\">", $img_url));
					echo(sprintf("<h4>%s</h3>", $name));
					echo(sprintf("<p>%s</p></section>", $description));
				}
			}
			$conn->close();
			 ?>
			</div>
			<!-- row -->
		</div>
		<!-- content container -->
		<!--bootstrap js&jquery-->
		<script src="//cdn.bootcss.com/jquery/3.4.1/jquery.min.js"></script>
		<script src="//cdn.bootcss.com/twitter-bootstrap/4.4.1/js/bootstrap.js"></script>
	</body>

</html>