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

	<?php
		// if (isset($_SESSION['username']))
		// {
		// 	echo '<div id = "recomdari">';








		// 	echo '</div>';
			
		// 	require("bd_connection.php");
		// 	$username = $_SESSION['username'];
		// 	//echo $_SESSION['username'];
		// 	$s = ociparse($conn, "begin user_pkg.get3pref('$username'); commit; end;");
		// 	ociexecute($s);
		// 	$s = ociparse($conn, "select denumire, pret, stoc");
		// 	ociexecute($s);
		// 	ocifetch($s);
		// 	$unu = ociresult($s, "UNU");
		// 	$doi = ociresult($s, "DOI");
		// 	$trei = ociresult($s, "TREI");
		// 	echo "$unu $doi $trei";
		// }
	?>

	<?php
		$default_locatie_pagina = htmlspecialchars($_SERVER["PHP_SELF"]);
		require("bd_connection.php");
		$s = ociparse($conn, "select denumire, pret, stoc, id_produs from produse");
		ociexecute($s);
		$index =1;

		while (ocifetch($s))
		{
			$vec_den[$index] = ociresult($s, "DENUMIRE");
			$vec_pret[$index] = ociresult($s, "PRET");
			$vec_stoc[$index] = ociresult($s, "STOC");
			$id_prod = ociresult($s, "ID_PRODUS");

			$stm = ociparse($conn, "begin :aroma := produse_pkg.get_aroma_string(produse_pkg.get_aroma($id_prod)); end;");
			oci_bind_by_name($stm, ":aroma", $aroma, 100);
			ociexecute($stm);
			$vec_aroma[$index] = $aroma;

			//echo $vec_aroma[$index];

			$index++;



		}

		if (isset($_GET['elem']) && isset($_GET['pagina']))
        {
            $default_pagina = $_GET['pagina'];
            $default_elem = $_GET['elem'];
        }
        else
        {
            $default_pagina = 1;
            $default_elem = 10;
        }
        

        $max_pagini = floor(($index-1) / $default_elem);
        if (($index-1) % $default_elem != 0)
            $max_pagini++;


        //r <= $default_pagina*$default_elem and r > ($default_pagina-1)*$default_elem
		for ($i=($default_pagina-1)*$default_elem + 1; $i <= $default_pagina*$default_elem && $i < $index; $i++) { 
			# code...
			$den = $vec_den[$i];
			$pret = $vec_pret[$i];
			$stoc = $vec_stoc[$i];
			$aroma = substr($vec_aroma[$i], 0, -2);
			echo 
							'<section id = "produs">
						<img src = "img/meniu_bar.png" id = "meniu_bar">

						<div id = "imagine_produs">
							<img src="img/slider/img1.png" id = "img_produs">
						</div>
						<div id="descriere_produs">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Denumire:'. $den.'
							<br/>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Aroma: '.$aroma.'
							<br/>
							&nbsp;&nbsp;&nbsp;&nbsp;Stoc: '.$stoc.'
							<br/>
							Pret: '.$pret.' $
							<br/>

						</div>
						<div id = "imagine_produs">
							<a href = "">
								<img src="img/add_to_cart.png" id = "add_to_cart">
							</a>
						</div>

						<img src = "img/meniu_bar.png" id = "meniu_bar">
					</section>';
		}

		$next = $default_pagina + 1;
        $anterior  = $default_pagina - 1;

		if ($default_pagina <= 1)
            echo "<button disabled onclick=\"location.href='$default_locatie_pagina"."?pagina=$anterior&elem=$default_elem'\"> Previous </button>";
        else
            echo "<button onclick=\"location.href='$default_locatie_pagina"."?pagina=$anterior&elem=$default_elem'\"> Previous </button>";
        echo " Pagina: $default_pagina";
        if ($default_pagina >= $max_pagini)
         echo    "<button disabled onclick=\"location.href='$default_locatie_pagina"."?pagina=$next&elem=$default_elem'\"> Next </button>";
        
        else
            echo    "<button onclick=\"location.href='$default_locatie_pagina"."?pagina=$next&elem=$default_elem'\"> Next </button>";
        echo "</div><br><br>";
	?>

	

	<?php require("the_footer.php"); ?>

</body>

</html>