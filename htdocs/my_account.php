<?php
	session_start();

	if(!isset($_GET['actiune']))
	$_GET['actiune']='';

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
	   $oldpass = test_input($_POST["oldpassword"]);
	   $pass = test_input($_POST["newpassword"]);
	   $cpass = test_input($_POST["newcpassword"]);
	}

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
<title></title>
<link rel="stylesheet" type="text/css" href="style.css" />


</head>

<body>

<?php require("the_header.php"); ?>

<?php require("pop-up.php"); ?>


<div id="openPass" class="modalDialog">
	<div>
		<a href="#close" title="Close" class="close">X</a>
		<br><br>
				<form action="my_account.php?actiune=schimbaparola" method="post" class="contact-form">
					<h1>Schimba Parola
						<span>Please fill all the texts in the fields.</span>
					</h1>
					<label>
						<span>Old Password: </span>
						<input id="message" type="password"  name="oldpassword" size="20" placeholder="Old Password" />
					</label> 
					<br>
					<label>
						<span>New Password: </span>
						<input id="message" type="password"  name="newpassword" size="20" placeholder="New Password" />
					</label> 
					<br>
					<label>
						<span>Confirm New Password: </span>
						<input id="message" type="password"  name="newcpassword" size="20" placeholder="Confirm New Password" />
					</label> 
					<br>
					 <label>
						<span>&nbsp;</span>
						<input type="submit" class="button" value="Submit" title="SubmitPassword" /> 
					</label>    
				</form>
	</div>
</div>

<?php
	if(!isset($_SESSION['username']))
		die("Mai intai trebuie sa va logati!");
?>

<div id = "img_contul_meu"></div>

<table id = "profil">
	<tr>
		<td>
			<img src="http://fc05.deviantart.net/fs71/f/2012/167/7/8/anime_avatar_by_spogjem-d53nmo2.png" alt="de completat" id = "img_avatar">
			<br/><br/>
		</td>
		
		<td id = "detalii_profil">
			Name: Cobuz <br/>
			Last Name: Veniamin <br/>
			Sex: Neutru <br/>
			Region: Crishana <br/>
			Wallet: 100 $ <br/>

		</td>
	<tr>
		<td>
			<a href="#openPass" id="text_profil">
				&nbsp;
				<img src="img/change_avatar.png" id = "img_button_avatar">
				Change Avatar
			</a>

			<br/><br/>

			<a href="#openPass" id="text_profil">
				<img src="img/shopping_cart.png" id = "img_button_cart">
				Shopping Cart
			</a>

			<br/><br/>

			<a href="#openPass" id="text_profil">
				&nbsp;&nbsp;
				<img src="img/purchase_history.png" id = "img_button_history">
				Purchase History
			</a>
		</td>
		<td>
			<a href="#openPass" id="text_profil">
				<img src="img/change_profil.png" id = "img_button_profil">
				Change Profil
			</a>
		</td>
	</tr>
			
		
	</tr>
</table>

<?php
	if($_GET['actiune'] == "schimbaparola")
	{
		if($pass == $cpass)
		{
			require("bd_connection.php");

    		if (!$conn) {
        		echo 'Failed to connect to Oracle';
        		die();
    		}
    		else
    		{
    			$sql = oci_parse($conn, "BEGIN SELECT count(*) INTO :bind1 FROM users WHERE username = :bind2 AND password = :bind3; END;");
				oci_bind_by_name($sql, ":bind1", $nr);
				oci_bind_by_name($sql, ":bind2", $_SESSION["username"]);
				oci_bind_by_name($sql, ":bind3", $oldpass);
				oci_execute($sql, OCI_DEFAULT);
				if($nr != 0)
				{
					$s = oci_parse($conn, "BEGIN user_pkg.change_password(:user, :newpass); commit; END;");
	     			oci_bind_by_name($s, ":user", $_SESSION["username"]);
	     			oci_bind_by_name($s, ":newpass", $pass);
	     			if(oci_execute($s, OCI_DEFAULT))
	     				echo "Parola a fost schimbata cu succes!!!!!!!!!!!!";
	     			else
	     				echo "nunu";
	     		}
				else
				{
					echo "nu e corect";
				}
    		}
		}
		else
		{
			echo "Noua parola si confirmarea acesteia nu se potrivesc!";
		}
	}
?>

<section id="section3">
	<?php require("paginare.php"); ?>
</section>

<?php require("the_footer.php"); ?>

</body>

</html>