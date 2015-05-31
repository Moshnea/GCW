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

	<!--
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
	-->
<!--
	<table style = "width: 100%">
		<tr>
			<td width = "20%">
				<img src="img/slider/img1.png" id = "img_produs">
			</td>
			<td width = "60%">
				Denumire: 10 $
				<br/>
				Stoc: 10
				<br/>
				Pret: 10 $
			</td>
			<td width = "20%">
				<img src="img/slider/img1.png" id = "img_produs">
			</td>
		</tr>
	</table>
-->
	<section id = "produs">
		<div id = "imagine_produs">
			<img src="img/slider/img1.png" id = "img_produs">
		</div>
		<div id="descriere_produs">
		<br/><br/>
			Denumire: 10 $
			<br/>
			Stoc: 10
			<br/>
			Pret: 10 $
		</div>
		<div id = "imagine_produs">
			<img src="img/slider/img1.png" id = "img_produs">
		</div>
	</section>
	<?php require("the_footer.php"); ?>

</body>

</html>