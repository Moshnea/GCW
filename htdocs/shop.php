<?php
	session_start();
?>
<!DOCTYPE HTML>

<html>

<head>

<meta charset = "UTF-8">
<title>Store</title>
<link rel="stylesheet" type="text/css" href="style.css" />


</head>

<body>

<?php require("the_header.php"); ?>

<?php require("pop-up.php"); ?>

<section id="section2">

<?php
	if(!isset($_SESSION['username']))
	{
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL = index.php">';
	}
	echo "Merge!!!!";
?>
	
</section>

<?php require("the_footer.php"); ?>

</body>

</html>