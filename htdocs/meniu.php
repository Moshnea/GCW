<?php
	session_start();
?>
<!DOCTYPE HTML>

<html>

<head>

<meta charset = "UTF-8">
<title>Menu</title>
<link rel="stylesheet" type="text/css" href="style.css" />


</head>

<body>

<?php require("the_header.php"); ?>

<?php require("pop-up.php"); ?>

</header>

<div id = "img_meniu"></div>
<section id = "listameniustg">

<ul style = "list-style-type:none;">

<li>
	<h2>Apple Juice</h2>
	<h3>Cheese, tomato, mushrooms, onions.</h3>
</li>

<li>
	<h2>Berry Juice </h2>
	<h3>Chicken, mozzarella cheese, onions.</h3>
</li>


<li>
	<h2>Buble Juice</h2>
	<h3>Tuna, Sweetcorn, Cheese.</h3>
</li>

<li>
	<h2>BlaBla Juice</h2>
	<h3>Pineapple, Minced Beef, Cheese.</h3>
</li>

</ul>

</section>

<?php require("the_footer.php"); ?>

</body>

</html>