<?php
	session_start();
?>
<!DOCTYPE HTML>

<html>

<head>

<meta charset = "UTF-8">
<title>Store</title>
<link rel="stylesheet" type="text/css" href="style.css" />


</head>

<body>

<?php require("the_header.php"); ?>

<?php require("pop-up.php"); ?>

<section id = "produs">
	<img src = "img/meniu_bar.png" id = "meniu_bar">
</section>
<?php
	try{
		$conn = NULL;
		require("bd_connection.php");
		if(!isset($_SESSION['username']))
		{
			echo '<META HTTP-EQUIV="Refresh" Content="0; URL = index.php">';
		}
		if((isset($_SESSION['product']))&&(!empty($_SESSION['product'])))
		{
			$total = 0;
			foreach ($_SESSION['product'] as $key => $value) {

				$stm = ociparse($conn, "begin :aroma := produse_pkg.get_aroma_string(produse_pkg.get_aroma($key)); end;");
				oci_bind_by_name($stm, ":aroma", $aroma, 100);
				ociexecute($stm);

				$s = ociparse($conn, "BEGIN SELECT denumire, stoc, pret INTO :den, :stoc, :pret FROM produse WHERE id_produs = $key; END;");

				oci_bind_by_name($s, ":den", $den, 40);
				oci_bind_by_name($s, ":stoc", $stoc, 40);
				oci_bind_by_name($s, ":pret", $pret, 40);

				ociexecute($s);

				$total += $pret * $value;

				echo '<section id = "produs">
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
								<input id = "add_buc" type="text" name="quantity' . $key . '" size="3" placeholder="' . $value . '"  readonly/>&nbsp;&nbsp;
								<input type = "image" name = "remove" src="img/remove.png" id = "add_to_cart"/>';

								if(isset($_POST['quantity'.$key]))
								{
									unset($_SESSION['product'][$key]);
									echo '<META HTTP-EQUIV="Refresh" Content="0; URL = shop.php">';
								}
								
							echo '</form>
						</div>

						<img src = "img/meniu_bar.png" id = "meniu_bar">
					</section>';
			}
			echo '<section id = "produs">
						<div id="descriere_produs">
							TOTAL =' . $total .'
						</div>

						<div id = "imagine_produs">
							<form action = " " method = "POST">
								<input type = "image" name = "buy" src="img/buy_now.png" id = "add_to_cart"/>';

								if(isset($_POST['buy_x'], $_POST['buy_y']))
								{	
									if($_SESSION['wallet'] >= $total)
									{
										$succes = 0;
										$index = 0;
										foreach ($_SESSION['product'] as $key => $value) {
											$s = ociparse($conn, "BEGIN UPDATE produse SET stoc = stoc - $value WHERE id_produs = $key; END;");
											if(ociexecute($s))
											{
												$succes += 1;
											}
											$index += 1;
										}
										if($succes != $index)
										{
											$s = ociparse($conn, "rollback");
											ociexecute($s);
											throw new Exception("An error has occurred!");
										}

										$s = ociparse($conn, "BEGIN UPDATE users SET wallet = wallet - $total WHERE username = :user; END;");
										oci_bind_by_name($s, ":user", $_SESSION['username']);

										if(!ociexecute($s))
										{
											$s1 = ociparse($conn, "rollback");
											ociexecute($s1);
											throw new Exception("An error has occurred!");
										}

										$s = ociparse($conn, "commit");
										ociexecute($s);
										$_SESSION['wallet'] -= $total;
										unset($_SESSION['product']);
										echo '<META HTTP-EQUIV="Refresh" Content="0; URL = shop.php">';
									}
									else
									{
										throw new Exception("Insuficient money!");	
									}
								}


							echo '</form>
						</div>
						<img src = "img/meniu_bar.png" id = "meniu_bar">
					</section>';
			
		}
		else
		{
			echo ' <div>Your cart is empty</div>
					<section id = "produs">
						<img src = "img/meniu_bar.png" id = "meniu_bar">
					</section>';

		}
	}catch (Exception $e)
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