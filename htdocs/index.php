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

<?php require("pop-up.php"); ?>

<section id="section1">

	<?php 
		if(isset($_SESSION['username']))
		{
			$n = $_SESSION['nume'] . " " . $_SESSION['prenume'];
			//echo $n;
			echo "<h1>Welcome to Juicy " . $n . "!</h1>";
		}
		else
		{
			echo "<h1>Welcome to Juicy m8! </h1>";
		}
	?>

	<div id="bgVid">
		<div id="playbtn"></div>
	</div>
	<h2>OUR FRESHLY SQUEEZED JUICES ARE DELICIOUS AND NUTRITIOUS, PACKED
 WITH WHOLESOME INGREDIENTS. JUICING MADE SIMPLE FRESH ON-THE-SPOT, AND
 NOW AVAILABLE IN COLD PRESSED ON-THE-GO.</h2>
</section>

<section id="section2">
	<h2><b>New Types of juices</b></h2>
	<div id = "imagini">
		<section id="s1" class="sections">
			<div id = "sImg1" class="slSection">
				<img src="img/slider/img1.png" class = "imajine" />
				<h3>Apple Juice $1</h3>
			</div>
			<div id = "sImg2" class="slSection">
				<img src="img/slider/img2.png" class = "imajine" />
				<h3>Berry Juice $2</h3>
			</div>
			
			<div id = "sImg3" class="slSection">
				<img src="img/slider/img3.png" class = "imajine" />
				<h3>Bubble Juice $3</h3>
			</div>
			
			<div id = "sImg4" class="slSection">
				<img src="img/slider/img4.png" class = "imajine" />
				<h3>BlaBla Juice $4</h3>
			</div>
		
		</section>
		
		
		
		<section id="s2" class="sliderHiddenSection" class="sections" >
			<div id = "sImg1" class="slSection">
				<img src="img/slider/img1.png" class = "imajine" />
				<h3>Apple Juice $5</h3>
			</div>
			<div id = "sImg2" class="slSection">
				<img src="img/slider/img2.png" class = "imajine" />
				<h3>Berry Juice $6</h3>
			</div>
			
			<div id = "sImg3" class="slSection">
				<img src="img/slider/img3.png" class = "imajine" />
				<h3>Bubble Juice $7</h3>
			</div>
			
			<div id = "sImg4" class="slSection">
				<img src="img/slider/img4.png" class = "imajine" />
				<h3>BlaBla Juice $8</h3>
			</div>
		
		</section>
		
		
		<section id="s3" class="sliderHiddenSection" class="sections" >
			<div id = "sImg1" class="slSection">
				<img src="img/slider/img1.png" class = "imajine" />
				<h3>Apple Juice $9</h3>
			</div>
			<div id = "sImg2" class="slSection">
				<img src="img/slider/img2.png" class = "imajine" />
				<h3>Berry Juice $10</h3>
			</div>
			
			<div id = "sImg3" class="slSection">
				<img src="img/slider/img3.png" class = "imajine" />
				<h3>Bubble Juice $11</h3>
			</div>
			
			<div id = "sImg4" class="slSection">
				<img src="img/slider/img4.png" class = "imajine" />
				<h3>BlaBla Juice $12</h3>
			</div>
		
		</section>
		
		<section id="s4" class="sliderHiddenSection" class="sections" >
			<div id = "sImg1" class="slSection">
				<img src="img/slider/img1.png" class = "imajine" />
				<h3>Apple Juice $13</h3>
			</div>
			<div id = "sImg2" class="slSection">
				<img src="img/slider/img2.png" class = "imajine" />
				<h3>Berry Juice $14</h3>
			</div>
			
			<div id = "sImg3" class="slSection">
				<img src="img/slider/img3.png" class = "imajine" />
				<h3>Bubble Juice $15</h3>
			</div>
			
			<div id = "sImg4" class="slSection">
				<img src="img/slider/img4.png" class = "imajine" />
				<h3>BlaBla Juice $16</h3>
			</div>
		
		</section>
		
	</div>
	<div id = "arrayBar">
		
		<ul>
			<li> <span class="arraySelected">1</span> </li>
			<li> <span>2</span>  </li>
			<li> <span>3</span>  </li>
			<li> <span>4</span>  </li>
		</ul>
		
	</div>
	
</section>

<script src = "script/slider.js" type="text/javascript"></script>



<section id="section3">


	<h2>Image Gallery</h2>
	<div id = "bgGal">
		<img id = "cimg1" src = "img/6.jpg" height = "300px"/>
		<img id = "cimg2" src = "img/1.jpg" height = "300px"/>
		<img id = "cimg3" src = "img/2.jpg" height = "300px"/>
		<img id = "cimg4" src = "img/3.jpg" height = "300px"/>
		<img id = "cimg5" src = "img/4.jpg" height = "300px"/>
		<img id = "cimg6" src = "img/5.jpg" height = "300px"/>
		
	</div>

</section>




<section id="imageDialog">
	
	<h1 id="closeBtnImageDialog">X</h1>
	<center><img id="imgOutput" src="" /></center>
	
	
</section>


<script src = "script/popups.js" type = "text/javascript" ></script>

<?php require("the_footer.php"); ?>

</body>

</html>