<?php
	session_start();

	if(!isset($_GET['actiune']))
	$_GET['actiune']='';

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
	<title>Profile</title>
	<link rel="stylesheet" type="text/css" href="style.css" />

</head>

<body>

	<?php require("the_header.php"); ?>

	<?php require("pop-up.php"); ?>


	<div id="openProfile" class="modalDialog">
		<div>
			<a href="#close" title="Close" class="close">X</a>
			<br><br>
			<form action="my_account.php?actiune=change_profil" method="post" class="contact-form">
				<h1> Change Profil </h1>
				<label>
					<span>Password: </span>
					<input id="message" type="password"  name="password" size="20" placeholder = "Password" />
				</label> 
				<br>
				<label>
					<span>New Password: </span>
					<input id="message" type="password"  name="newpass" size="20" placeholder = "New Password" />
				</label> 
				<br>
				<label>
					<span>Confirm Password: </span>
					<input id="message" type="password" name="cnewpass" size="20" placeholder = "Confirm New Password" />
				</label> 
				<br>
				<label>
					<span>Name: </span>
					<input id="message" type="text" name="name" size="20" placeholder= <?php echo $_SESSION["name"]; ?> />
				</label> 
				<br>
				<label>
					<span>Last Name: </span>
					<input id="message" type="text" name="lastname" size="20" placeholder = <?php echo $_SESSION["lastname"]; ?> />
				</label> 
				<br>
				<label>
					<span>Gender: </span>
					<input type="radio" name="gender" <?php if ($_SESSION["gender"] == 'F') echo "checked";?>  value="F">F
					<input type="radio" name="gender" <?php if ($_SESSION["gender"] == 'M') echo "checked";?>  value="M">M
				</label> 
				<br>
				<label>
					<span>Region: </span>
					<!-- <input id="message" type="text" name="region" size="20" placeholder= <?php echo $_SESSION["region"]; ?> /> -->
					<select name="region">
						<?php
							if($_SESSION["region"] == "N/A")
								echo '<option value="N/A" selected>N/A</option>';
						?>
						<option value="Banat" <?php if($_SESSION["region"]=="Banat") echo "selected";?>>Banat</option>
						<option value="Bucovina" <?php if($_SESSION["region"]=="Bucovina") echo "selected";?>>Bucovina</option>
						<option value="Crisana" <?php if($_SESSION["region"]=="Crisana") echo "selected";?>>Crisana</option>
						<option value="Dobrogea" <?php if($_SESSION["region"]=="Dobrogea") echo "selected";?>>Dobrogea</option>
						<option value="Maramures" <?php if($_SESSION["region"]=="Maramures") echo "selected";?>>Maramures</option>
						<option value="Moldova" <?php if($_SESSION["region"]=="Moldova") echo "selected";?>>Moldova</option>
						<option value="Muntenia" <?php if($_SESSION["region"]=="Muntenia") echo "selected";?>>Muntenia</option>
						<option value="Oltenia" <?php if($_SESSION["region"]=="Oltenia") echo "selected";?>>Oltenia</option>
						<option value="Transilvania" <?php if($_SESSION["region"]=="Transilvania") echo "selected";?>>Transilvania</option>
					</select>
					<?php
						if(!isset($_SESSION["region"]) || $_SESSION["region"] == "N/A" )
							echo "<i>You don't have a region yet!</i>";

					?>
				</label> 
				<br>
				<label>
					<span>&nbsp;</span>
					<input type="submit" class="button" value="Submit" title="SubmitProfile" /> 
				</label>    
			</form>
		</div>
	</div>

	<div id="openAvatar" class="modalDialog">
		<div>
			<a href="#close" title="Close" class="close">X</a>
			<br><br>
			<form action="my_account.php?actiune=change_avatar" method = "post" class="contact-form">
				<h1> Change Avatar </h1>
				<label>
					<span>Password: </span>
					<input id="message" type="password"  name = "password" size="20" placeholder = "Password" />
				</label> 
				<br>
				<label>
					<span>New Avatar (quadratic): </span>
					<input id="message" type="text"  name="avatar" size="20" placeholder = "URL Avatar" />
				</label> 
				<br>
				<label>
					<span>&nbsp;</span>
					<input type="submit" class="button" value="Submit" title="SubmitAvatar" /> 
				</label>    
			</form>
		</div>
	</div>


	<div id="openMoney" class="modalDialog">
		<div>
			<a href="#close" title="Close" class="close">X</a>
			<br><br>
			<form action="my_account.php?actiune=add_money" method = "post" class="contact-form">
				<h1> Add money in your wallet! </h1>
				<label>
					<span>Password: </span>
					<input id="message" type="password"  name = "password" size="20" placeholder = "Password" />
				</label> 
				<br>
				<label>
					<span>Amount: </span>
					<input id="message" type="text"  name="amount" size="20" placeholder = "$" />
				</label> 
				<br>
				<label>
					<span>&nbsp;</span>
					<input type="submit" class="button" value="Submit" title="SubmitMoney" /> 
				</label>    
			</form>
		</div>
	</div>

	<div id="openHistory" class="modalDialog">
		<div>
			<a href="#close" title="Close" class="close">X</a>
			<br><br>
			<h1> Purchase History </h1>
			<?php require("paginare.php"); ?>
		</div>
	</div>


	<?php
		if(!isset($_SESSION['username']))
			echo '<META HTTP-EQUIV="Refresh" Content="0; URL = index.php">';
	?>

	<div id = "img_contul_meu"></div>

	<table id = "profil">
		<tr>
			<td>
				<img src=<?php echo $_SESSION['avatar']; ?> alt="Unavailable" id = "img_avatar">
				<br/><br/>
			</td>

			<td id = "detalii_profil">
				<?php
				echo "Name: " . $_SESSION['name'] . "<br/>";
				echo "Last Name: " . $_SESSION['lastname'] . "<br/>";
				echo "Gender: " . $_SESSION['gender'] . "<br/>";
				echo "Region: " . $_SESSION['region'] . "<br/>";
				echo "Wallet: " . $_SESSION['wallet'] . "$<br/>";
				?>
			</td>
		</tr>
	 	<tr>
			<td>
				<a href="#openAvatar" id="text_profil">
					&nbsp;
					<img src="img/change_avatar.png" id = "img_button_avatar">
					Change Avatar
				</a>

				<br/><br/>

				<a href="shop.php" id="text_profil">
					<img src="img/shopping_cart.png" id = "img_button_cart">
					Shopping Cart
				</a>

				<br/><br/>

				<a href="#openHistory" id="text_profil">
					&nbsp;&nbsp;
					<img src="img/purchase_history.png" id = "img_button_history">
					Purchase History
				</a>
			</td>
			<td>
				<a href="#openProfile" id="text_profil">
					<img src="img/change_profil.png" id = "img_button_profil">
					Change Profil
				</a>

				<br/><br/>

				<a href="#openMoney" id="text_profil">
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<img src="img/wallet.png" id = "img_button_wallet">
					Give us more money
				</a>
			</td>
		</tr>
				
			
		</tr>
	</table>

	<?php
		if($_GET['actiune'] == "change_profil")
		{
			//($_SERVER["REQUEST_METHOD"] == "POST")
			$pass = test_input($_POST["password"]);
		   	$name = test_input($_POST["name"]);
		   	$lastname = test_input($_POST["lastname"]);
		   	$gender = test_input($_POST["gender"]);
		   	$newpass = test_input($_POST["newpass"]);
		  	$cnewpass = test_input($_POST["cnewpass"]);
		  	$region = test_input($_POST["region"]);

			require("bd_connection.php");

			if (!$conn) {
	    		echo 'Failed to connect to Oracle';
	    		die();
			}
			else
			{
				$sql = oci_parse($conn, "BEGIN SELECT count(*) INTO :bind1 FROM users WHERE username = :username AND password = :pass; END;");
				oci_bind_by_name($sql, ":bind1", $nr);
				oci_bind_by_name($sql, ":username", $_SESSION["username"]);
				oci_bind_by_name($sql, ":pass", $pass);
				oci_execute($sql, OCI_DEFAULT);
				if($nr != 0)
				{
					if(($newpass != NULL) && ($newpass == $cnewpass))
					{
						$s = oci_parse($conn, "BEGIN user_pkg.change_password(:user, :newpass); commit; END;");
		     			oci_bind_by_name($s, ":user", $_SESSION["username"]);
		     			oci_bind_by_name($s, ":newpass", $newpass);
		     			if(oci_execute($s, OCI_DEFAULT))
		     				echo "Parola a fost schimbata cu succes!!!!!!!!!!!!";
		     			else
		     				echo '<p class="error"> nunu</p>';
	     			}

	     			if ($name != NULL) 
	     			{
		     			$_SESSION["name"] = $name;
	     			}

	     			if($lastname != NULL)
	     			{
	     				$_SESSION["lastname"] = $lastname;
	     			}

	     			if($gender != NULL)
	     			{
	     				if($gender == "M")
	     					$aux_gender = 0;
	     				else
	     					$aux_gender = 1;
	     				$_SESSION["gender"] = $gender;
	     			}
	     			else
	     			{
	     				if($_SESSION["gender"] == "M")
	     					$aux_gender = 0;
	     				else
	     					$aux_gender = 1;
	     			}

	     			if($region != NULL)
	     			{
	     				$_SESSION["region"] = $region;
	     			}

	     			$s = oci_parse($conn, "
	     				BEGIN 
	     					UPDATE users u SET u.nume = :name, u.prenume = :lastname, u.profile.gender = :gender, u.profile.regiune = :region
	     					WHERE u.username = :user; 
	     					commit; 
	     				END;");
	     			oci_bind_by_name($s, ":user", $_SESSION["username"]);
	     			oci_bind_by_name($s, ":name", $_SESSION["name"]);
	     			oci_bind_by_name($s, ":lastname", $_SESSION["lastname"]);
	     			oci_bind_by_name($s, ":gender", $aux_gender);
	     			oci_bind_by_name($s, ":region", $_SESSION["region"]);
	     			if(oci_execute($s, OCI_DEFAULT))
	     			{
	     				echo '<META HTTP-EQUIV="Refresh" Content="0; URL = my_account.php">';
	     			}
	     			else
	     				echo '<p class="error">nunu</p>';
	     		}
				else
				{
					echo '<p class="error">Nu s-a gasit accest account!!</p>';
				}
			}
			oci_close($conn);
		}

		if($_GET['actiune'] == "change_avatar")
		{
			$pass = test_input($_POST["password"]);
			$avatar = test_input($_POST["avatar"]);

			require("bd_connection.php");

			if (!$conn) 
			{
	    		echo 'Failed to connect to Oracle';
	    		die();
			}
			else
			{
				$sql = oci_parse($conn, "BEGIN SELECT count(*) INTO :bind1 FROM users WHERE username = :username AND password = :pass; END;");
				oci_bind_by_name($sql, ":bind1", $nr);
				oci_bind_by_name($sql, ":username", $_SESSION["username"]);
				oci_bind_by_name($sql, ":pass", $pass);
				oci_execute($sql, OCI_DEFAULT);
				if($nr != 0)
				{
					if ($avatar != NULL) 
	     			{
		     			$_SESSION["avatar"] = $avatar;

		     			$s = oci_parse($conn, "BEGIN UPDATE users u SET u.profile.url_avatar = :avatar WHERE u.username = :user; commit; END;");
		     			oci_bind_by_name($s, ":user", $_SESSION["username"]);
		     			oci_bind_by_name($s, ":avatar", $_SESSION["avatar"]);

		     			if(oci_execute($s, OCI_DEFAULT))
		     			{
		     				echo '<META HTTP-EQUIV="Refresh" Content="0; URL = my_account.php">';
		     			}
		     		}
	     			else
	     			{
	     				echo '<p class="error">nunu</p>';
	     			}	
				}
				else
				{
					echo '<p class="error">Nu s-a gasit accest account!!</p>';
				}
			}
			oci_close($conn);
		}

		if($_GET['actiune'] == "add_money")
		{
			$pass = test_input($_POST["password"]);
			$amount = test_input($_POST["amount"]);
			require("bd_connection.php");

			if (!$conn) 
			{
	    		echo 'Failed to connect to Oracle';
	    		die();
			}
			else
			{
				$sql = oci_parse($conn, "BEGIN SELECT count(*) INTO :bind1 FROM users WHERE username = :username AND password = :pass; END;");
				oci_bind_by_name($sql, ":bind1", $nr);
				oci_bind_by_name($sql, ":username", $_SESSION["username"]);
				oci_bind_by_name($sql, ":pass", $pass);
				oci_execute($sql, OCI_DEFAULT);
				if($nr != 0)
				{
					if ($amount != NULL)
	     			{
	     				if(is_int($amount))
	     				{
			     			$s = oci_parse($conn, "BEGIN UPDATE users SET wallet = wallet + :amount WHERE username = :user; commit; END;");
			     			oci_bind_by_name($s, ":user", $_SESSION["username"]);
			     			oci_bind_by_name($s, ":amount", $amount);

			     			if(oci_execute($s, OCI_DEFAULT))
			     			{
			     				$_SESSION["wallet"] += $amount;
			     				echo '<META HTTP-EQUIV="Refresh" Content="0; URL = my_account.php">';
			     			}
			     		}
			     		else
			     		{
			     			echo "not a number";
			     		}
		     		}
	     			else
	     			{
	     				echo '<p class="error">nunu</p>';
	     			}	
				}
				else
				{
					echo '<p class="error">Nu s-a gasit accest account!!</p>';
				}
			}
			oci_close($conn);
		}
	?>
	<?php require("the_footer.php"); ?>

</body>

</html>