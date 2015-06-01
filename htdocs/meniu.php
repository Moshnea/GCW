<?php
	session_start();
	
	function test_input($data) {
	   $data = trim($data);
	   $data = stripslashes($data);
	   $data = htmlspecialchars($data);
	   return $data;
	}

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
		try
		{
			if (isset($_SESSION['username']))
			{			
				require("bd_connection.php");
				$username = $_SESSION['username'];
				//echo $_SESSION['username'];
				$s = ociparse($conn, "begin user_pkg.get3pref('$username'); commit; end;");
				ociexecute($s);
				$s = ociparse($conn, "select * from rezultat");
				ociexecute($s);
				ocifetch($s);
				$unu = ociresult($s, "UNU");
				$doi = ociresult($s, "DOI");
				$trei = ociresult($s, "TREI");

				$s = ociparse($conn, "begin :aroma_unu := produse_pkg.get_aroma_string(produse_pkg.get_aroma($unu)); end;");
				oci_bind_by_name($s, ":aroma_unu", $aroma_unu, 100);
				ociexecute($s);
				$s = ociparse($conn, "begin :aroma_doi := produse_pkg.get_aroma_string(produse_pkg.get_aroma($doi)); end;");
				oci_bind_by_name($s, ":aroma_doi", $aroma_doi, 100);
				ociexecute($s);
				$s = ociparse($conn, "begin :aroma_trei := produse_pkg.get_aroma_string(produse_pkg.get_aroma($trei)); end;");
				oci_bind_by_name($s, ":aroma_trei", $aroma_trei, 100);
				ociexecute($s);

				//echo "$aroma_unu <br> $aroma_doi <br> $aroma_trei <br>";

				$s = ociparse($conn, "select denumire, pret, stoc from produse where id_produs = $unu or id_produs = $doi or id_produs = $trei");
				ociexecute($s);
				
				ocifetch($s);
				$denumire_unu = ociresult($s, "DENUMIRE");
				$pret_unu = ociresult($s, "PRET");
				$stoc_unu = ociresult($s, "STOC");

				ocifetch($s);
				$denumire_doi = ociresult($s, "DENUMIRE");
				$pret_doi = ociresult($s, "PRET");
				$stoc_doi = ociresult($s, "STOC");

				ocifetch($s);
				$denumire_trei = ociresult($s, "DENUMIRE");
				$pret_trei = ociresult($s, "PRET");
				$stoc_trei = ociresult($s, "STOC");
				echo "<div id=\"recomandari\">";
					echo "<h3>Recomandari</h3>";
				echo "<div id = \"recomand_unu\">
					Denumire: $denumire_unu
					Pret: $pret_unu
					Stoc: $stoc_unu
					Aroma: $aroma_unu
				</div>";
				echo "<div id = \"recomand_doi\">
					Denumire: $denumire_doi
					Pret: $pret_doi
					Stoc: $stoc_doi
					Aroma: $aroma_doi
				</div>";
				echo "<div id = \"recomand_trei\">
					Denumire: $denumire_trei
					Pret: $pret_trei
					Stoc: $stoc_trei
					Aroma: $aroma_trei
				</div>";
				echo "</div>";
			}
		} catch (Exception $e)
		{
			echo '<p class="error">' . $e->getMessage() . '!</p>';
		} finally {
			if ($conn != NULL)
				oci_close($conn);
		}
	?>

	<form action="" method="get">
		Look after:
		<input id="message" type="text" name="filter" size="20" placeholder="aroma" />
		<span>&nbsp;</span> 
		<input type="submit" class="button" value="Search" title="Search" />		
	</form>


	<?php
		try
		{
			$filter = NULL;
			if(isset($_GET['filter']))
			{
				$filter = test_input($_GET['filter']);
			}

			$default_locatie_pagina = htmlspecialchars($_SERVER["PHP_SELF"]);
			require("bd_connection.php");
			$s = ociparse($conn, "select denumire, pret, stoc, id_produs from produse");
			ociexecute($s);
			$index = 1;

			while (ocifetch($s))
			{
				$id_prod = ociresult($s, "ID_PRODUS");
				$stm = ociparse($conn, "begin :aroma := produse_pkg.get_aroma_string(produse_pkg.get_aroma($id_prod)); end;");
				oci_bind_by_name($stm, ":aroma", $aroma, 100);
				ociexecute($stm);

				if($filter != NULL)
				{
					if(strpos($aroma,$filter) === false)
						continue;
				}

				$vec_aroma[$index] = $aroma;
				$vec_den[$index] = ociresult($s, "DENUMIRE");
				$vec_pret[$index] = ociresult($s, "PRET");
				$vec_stoc[$index] = ociresult($s, "STOC");
				

				

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
	            echo "<button disabled onclick=\"location.href='$default_locatie_pagina"."?pagina=$anterior&elem=$default_elem&filter=$filter'\"> Previous </button>";
	        else
	            echo "<button onclick=\"location.href='$default_locatie_pagina"."?pagina=$anterior&elem=$default_elem&filter=$filter'\"> Previous </button>";
	        echo " Pagina: $default_pagina";
	        if ($default_pagina >= $max_pagini)
	         echo    "<button disabled onclick=\"location.href='$default_locatie_pagina"."?pagina=$next&elem=$default_elem&filter=$filter'\"> Next </button>";
	        
	        else
	            echo    "<button onclick=\"location.href='$default_locatie_pagina"."?pagina=$next&elem=$default_elem&filter=$filter'\"> Next </button>";
	        echo "</div><br><br>";
    	} catch (Exception $e)
		{
			echo '<p class="error">' . $e->getMessage() . '!</p>';
		} finally {
			if ($conn != NULL)
				oci_close($conn);
		}
	?>

	

	<?php require("the_footer.php"); ?>

</body>

</html>