<header>
	<!-- Imagini/ overlay !-->
	<div id="overlay"></div>
	<div id="logo"></div>
	<div id="juicy"> <strong><h1>Juicy</h1></strong> </div>
	<div id ="best"> <h2>- Cel mai mișto site de profil din Romania -</h2> </div>

	<!-- Meniu !-->
	<div id = "menu">
		<ul>
			<strong><li><a href="index.php">Our Story</a></li></strong>
			<strong><li><a href="meniu.php">Meniu</a></li></strong>
			<strong><li><a href="news.php">News</a></li>  </strong>
			<strong><li><a href="#openModal">Contact</a></li></strong>
			<?php
				if(isset($_SESSION['username']))
				{
					echo '<strong><li><a href="my_account.php">Contul Meu</a></li></strong>';
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