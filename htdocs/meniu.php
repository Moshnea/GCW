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

	<div id = "img_meniu"></div>

	<section id = "produs">
		<img src = "img/meniu_bar.png" id = "meniu_bar">

		<div id = "imagine_produs">
			<img src="img/slider/img1.png" id = "img_produs">
		</div>
		<div id="descriere_produs">
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Denumire: Grande Juicy
			<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Aroma: Banane cu Pamant
			<br/>
			&nbsp;&nbsp;&nbsp;&nbsp;Stoc: 10
			<br/>
			Pret: 10 $
			<br/>

		</div>
		<div id = "imagine_produs">
			<a href = "">
				<img src="img/add_to_cart.png" id = "add_to_cart">
			</a>
		</div>

		<img src = "img/meniu_bar.png" id = "meniu_bar">
	</section>

	<?php require("the_footer.php"); ?>

</body>

</html>