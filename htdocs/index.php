<?php
	session_start();
?>
<!DOCTYPE HTML>

<html>

<head>

	<meta charset = "UTF-8">
	<title>Juicy</title>
	<link rel="stylesheet" type="text/css" href="style.css" />

</head>

<body>

	<?php 
		require("the_header.php");
		require("pop-up.php");  
	?>


	<section id="section1">

		<?php 
			if(isset($_SESSION['username']))
			{
				$n = $_SESSION['name'] . " " . $_SESSION['lastname'];
				//echo $n;
				echo "<h1>Welcome to Juicy " . $n . "!</h1>";
			}
			else
			{
				echo "<h1>Welcome to Juicy m8! </h1>";
			}
		?>

		<div id="bgVid">
			<object width="720" height="360"
		data="http://www.youtube.com/v/-rJb0BA-KDw">
		</object>
			<!-- <div id="playbtn"></div>
		</div> -->
		

	</section>
	<div id="text-intro"
	<h2>OUR FRESHLY SQUEEZED JUICES ARE DELICIOUS AND NUTRITIOUS, PACKED
	 WITH WHOLESOME INGREDIENTS. JUICING MADE SIMPLE FRESH ON-THE-SPOT, AND
	 NOW AVAILABLE IN COLD PRESSED ON-THE-GO.</h2>
	</div>
			<!--
				begin-slider-imagini
			-->

	<section id="section2">
		<h2><b>New Types of juices</b></h2>
		<div id = "imagini">

	<?php
	try {
		$conn = NULL;
		require("bd_connection.php");
		$s = ociparse($conn, "SELECT DENUMIRE, PRET from produse where rownum <= 24");
		if (ociexecute($s))
		{
			for ($i=0; $i < 6; $i++)
			{	
				ocifetch($s);
				$den1 = ociresult($s, "DENUMIRE");
				$pret1 = ociresult($s, "PRET");
				
				ocifetch($s);
				$den2 = ociresult($s, "DENUMIRE");
				$pret2 = ociresult($s, "PRET");
				
				ocifetch($s);
				$den3 = ociresult($s, "DENUMIRE");
				$pret3 = ociresult($s, "PRET");
				
				ocifetch($s);
				$den4 = ociresult($s, "DENUMIRE");
				$pret4 = ociresult($s, "PRET");

				echo "<section id=\"s\" class=\"sliderHiddenSection\" class=\"sections\">
							<div id = \"sImg1\" class=\"slSection\">
								<img src=\"img/slider/img1.png\" class = \"imajine\" />
								<h3>$den1 $$pret1</h3>
							</div>
							<div id = \"sImg2\" class=\"slSection\">
								<img src=\"img/slider/img2.png\" class = \"imajine\" />
								<h3>$den2 $$pret2</h3>
							</div>
							
							<div id = \"sImg3\" class=\"slSection\">
								<img src=\"img/slider/img3.png\" class = \"imajine\" />
								<h3>$den3 $$pret3</h3>
							</div>
							
							<div id = \"sImg4\" class=\"slSection\">
								<img src=\"img/slider/img4.png\" class = \"imajine\" />
								<h3>$den4 $$pret4</h3>
							</div>
						
						</section>";

			}
		}
		else
		{
			throw new Exception("An error has occurred!");
			
		}
	}catch (Exception $e)
	{
		$_SESSION['message'] = $e->getMessage();
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL = index.php#errors">';
	} finally {
		if ($conn != NULL)
			oci_close($conn);
	}

	?>
<div id = "arrayBar">
			<ul>
				<li> <span class="arraySelected">1</span> </li>
				<li> <span>2</span>  </li>
				<li> <span>3</span>  </li>
				<li> <span>4</span>  </li>
				<li> <span>5</span>  </li>
				<li> <span>6</span>  </li>
			</ul>
		</div>
	</section>

	

	<script src = "script/slider.js" type="text/javascript"></script>

	<section id="section3">
		<div id = "bgGal">
		<h2>Image Gallery</h2>
		
			<img id = "cimg1" src = "img/6.jpg" height = "320px"/>
			<img id = "cimg2" src = "img/1.jpg" height = "320px"/>
			<img id = "cimg3" src = "img/2.jpg" height = "320px"/>
			<img id = "cimg4" src = "img/3.jpg" height = "320px"/>
			<img id = "cimg5" src = "img/4.jpg" height = "320px"/>
			<img id = "cimg6" src = "img/5.jpg" height = "320px"/>
		</div>
	</section>

	<section id="imageDialog">
		
		<h1 id="closeBtnImageDialog">X</h1>
		<center><img id="imgOutput" src="" /></center>
		
	</section>
	 
	 <section></section>

	<script src = "script/popups.js" type = "text/javascript" ></script>

	<?php require("the_footer.php"); ?>

</body>

</html>