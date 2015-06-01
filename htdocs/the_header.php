<header>
	<!-- Imagini/ overlay !-->
	
	<div id="overlay"></div>
	<?php
		if(isset($_SESSION['username']))
		{
			$total = 0;
			foreach ($_SESSION['product'] as $key => $value) {
				$total += $value;
			}
			echo 
				'<a href="shop.php">
					<img src="img/shopping_cart.png" id = "img_header_cart">
					<div id = "nr_prod">'. $total . '</div>
				</a>';
		}
	?>
	
	<div id="logo"></div>

	<!-- Meniu !-->
	<div id = "menu">
		<ul>
			<strong><li><a href="index.php">Home</a></li></strong>
			<strong><li><a href="meniu.php">Menu</a></li></strong>
			<strong><li><a href="news.php">News</a></li></strong>
			<strong><li><a href="#openModal">Contact</a></li></strong>
			<?php
				if(isset($_SESSION['username']))
				{
					echo '<strong><li><a href="my_account.php">Profile</a></li></strong>';
					echo '<strong><li><a href="logout.php">Log out</a></li></strong>';
				}
				else
				{
					echo '<strong><li><a href="#openLog">Log in</a></li></strong>';
					echo '<strong><li><a href="#openReg">Register</a></li></strong>';
				}
			?>			
		</ul>
	</div>	

</header>