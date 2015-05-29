<?php
	session_start();
?>
<!DOCTYPE HTML>

<html>

<head>

<meta charset = "UTF-8">
<title></title>
<link rel="stylesheet" type="text/css" href="style.css" />


</head>

<body>

<?php require("the_header.php"); ?>

<section id="section2">

<?php
	session_destroy();
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL = index.php">';
?>
	
</section>

<?php require("the_footer.php"); ?>

</body>

</html>