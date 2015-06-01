<?php
	session_start();
	
	function test_input($data) {
	   $data = trim($data);
	   $data = stripslashes($data);
	   $data = htmlspecialchars($data);
	   return $data;
	}

	function print_produs($id, $den, $aroma, $stoc, $pret)
	{
		$aroma = substr($aroma, 0, -2);
		echo '<section id = "produs">
			<div id = "imagine_produs">
				<img src="img/slider/img1.png" id = "img_produs">
			</div>
			<div id="descriere_produs_rec">
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
				<form action = " " method = "POST">
					<input id="add_buc" type="text" name="quantity'. $id .'" size="3" placeholder=" buc"  />&nbsp;&nbsp;
					<input type = "image" name = "add_to_cart" src="img/add_to_cart_rec.png" id = "add_to_cart"/>';

					if((isset($_POST['quantity'.$id])) && (!empty($_POST['quantity'.$id])))
					{
						$aux_q = $_POST['quantity'.$id];
						if(($aux_q >= 0)&&(is_numeric($aux_q)))
						{
							if((isset($_SESSION['product'][$id]))&&(!empty($_SESSION['product'][$id])))
							{
								if($stoc >= $_SESSION['product'][$id] + $aux_q)
								{
									$_SESSION['product'][$id] += $aux_q;
								}
								else
								{
									throw new Exception("Not in stock!");
								}
							}
							else
							{
								if($stoc >= $aux_q)
								{
									$_SESSION['product'][$id] = $aux_q;
								}
								else
								{
									throw new Exception("Not in stock!");
								}
							}
							
						}
						else
						{
							throw new Exception("Not a valid number");
						}
					}
				echo '</form>
			</div>

			<img src = "img/meniu_bar_rec.png" id = "meniu_bar">
		</section>';
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
				$s = ociparse($conn, "begin user_pkg.get3pref('$username', :unu, :doi, :trei); commit; end;");
			    oci_bind_by_name($s, ":unu", $unu);
			    oci_bind_by_name($s, ":doi", $doi);
			    oci_bind_by_name($s, ":trei", $trei);
			    ociexecute($s);

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

				echo '<div id="recomandari">Recomandations</div>';

				echo '<section id = "produs">
						<img src = "img/meniu_bar_rec.png" id = "meniu_bar">
					</section>';
				print_produs($unu, $denumire_unu, $aroma_unu, $stoc_unu, $pret_unu);
				print_produs($doi, $denumire_doi, $aroma_doi, $stoc_doi, $pret_doi);
				print_produs($trei, $denumire_trei, $aroma_trei, $stoc_trei, $pret_trei);
			}
		} catch (Exception $e)
		{
			echo '<p class="error">' . $e->getMessage() . '!</p>';
		} finally {
			if ($conn != NULL)
				oci_close($conn);
		}
	?>

	<br/><br/>
	<div id="all_products">All Products</div>;
	<form action="" method="get">
		<div id="search_ul">Look after:
		<input id="message" type="text" name="filter" size="20" placeholder="aroma" />
		<span>&nbsp;</span> 
		<input type="submit" class="button" value="Search" title="Search" />	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</div>	
	</form>
	<section id = "produs">
		<img src = "img/meniu_bar.png" id = "meniu_bar">
	</section>

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
					if(strpos($aroma, $filter) === false)
						continue;
				}

				$vec_id[$index] = $id_prod;
				$vec_aroma[$index] = $aroma;
				$vec_den[$index] = ociresult($s, "DENUMIRE");
				$vec_pret[$index] = ociresult($s, "PRET");
				$vec_stoc[$index] = ociresult($s, "STOC");



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

			for ($i=($default_pagina-1)*$default_elem + 1; $i <= $default_pagina*$default_elem && $i < $index; $i++) { 
				$id = $vec_id[$i];
				$den = $vec_den[$i];
				$pret = $vec_pret[$i];
				$stoc = $vec_stoc[$i];
				$aroma = substr($vec_aroma[$i], 0, -2);
				echo 
					'<section id = "produs">

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
							<form action = " " method = "POST">
								<input id="add_buc" type="text" name="quantity'. $id .'" size="3" placeholder=" buc"  />&nbsp;&nbsp;
								<input type = "image" name = "add_to_cart "src="img/add_to_cart.png" id = "add_to_cart"/>';

								if((isset($_POST['quantity'.$id])) && (!empty($_POST['quantity'.$id])))
								{
									$aux_q = $_POST['quantity'.$id];
									if(($aux_q >= 0)&&(is_numeric($aux_q)))
									{
										if((isset($_SESSION['product'][$id]))&&(!empty($_SESSION['product'][$id])))
										{
											if($stoc >= $_SESSION['product'][$id] + $aux_q)
											{
												$_SESSION['product'][$id] += $aux_q;
											}
											else
											{
												throw new Exception("Not in stock!");
											}
										}
										else
										{
											if($stoc >= $aux_q)
											{
												$_SESSION['product'][$id] = $aux_q;
											}
											else
											{
												throw new Exception("Not in stock!");
											}
										}
										echo '<META HTTP-EQUIV="Refresh" Content="0; URL = shop.php">';
									}
									else
									{
										throw new Exception("Not a valid number");
									}
								}
							echo '</form>
						</div>

						<img src = "img/meniu_bar.png" id = "meniu_bar">
					</section>';
			}

			$next = $default_pagina + 1;
	        $anterior  = $default_pagina - 1;
			echo'<div id="paginare"><br/>';
			if ($default_pagina <= 1)
	            echo "<button disabled onclick=\"location.href='$default_locatie_pagina"."?pagina=$anterior&elem=$default_elem&filter=$filter'\"> Previous </button>";
	        else
	            echo "<button onclick=\"location.href='$default_locatie_pagina"."?pagina=$anterior&elem=$default_elem&filter=$filter'\"> Previous </button>";
	        echo " Pagina: $default_pagina ";
	        if ($default_pagina >= $max_pagini)
	         echo    "<button disabled onclick=\"location.href='$default_locatie_pagina"."?pagina=$next&elem=$default_elem&filter=$filter'\"> Next </button>";
	        
	        else
	            echo    "<button onclick=\"location.href='$default_locatie_pagina"."?pagina=$next&elem=$default_elem&filter=$filter'\"> Next </button>";
	        echo "</div></div><br><br>";
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