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

<div id = "img_contul_meu"></div>


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

<div id="openUsername" class="modalDialog">
	<div>
		<a href="#close" title="Close" class="close">X</a>
		<br><br>
				<form action="my_account.php?actiune=schimbausername" method="post" class="contact-form">
					<h1>Schimba UserName-ul
						<span>Please fill all the texts in the fields.</span>
					</h1>
					<label>
						<span>Password: </span>
						<input id="mssage" type="text" name="username" size="20" placeholder="Password" />
					</label>
					<br>
					<label>
						<span>Old Username: </span>
						<input id="message" type="password"  name="password" size="20" placeholder="Old Username" />
					</label> 
					<br>
					<label>
						<span>New Username: </span>
						<input id="message" type="password"  name="cpassword" size="20" placeholder="New Username" />
					</label> 
					<br>
					<label>
						<span>Confirm New Username: </span>
						<input id="message" type="password"  name="cpassword" size="20" placeholder="Confirm New Username" />
					</label> 
					<br>
					 <label>
						<span>&nbsp;</span>
						<input type="submit" class="button" value="Submit" title="SubmitUsername" /> 
					</label>    
				</form>
	</div>
</div>

<?php
	if(!isset($_SESSION['username']))
		die("Mai intai trebuie sa va logati!");
	echo "Contul dumneavoastra [" . $_SESSION['username'] . "]";
?>

<div id="meniu_contul_meu">
	<ul>
		<strong><li><a id = "yo" href="#openUsername" target="_self">Schimba Username</a></li></strong><br/>
		<strong><li><a id = "yo" href="#openPass" target="_self">Schimba Parola</a></li></strong><br/>
	</ul>
</div>

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

<?php require("the_footer.php"); ?>

</body>

</html>