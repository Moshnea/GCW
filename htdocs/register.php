<?php
	session_start();
	$username = $nume = $prenume = $pass = $cpass = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
	   $username = test_input($_POST["username"]);
	   $nume = test_input($_POST["nume"]);
	   $prenume = test_input($_POST["prenume"]);
	   $pass = test_input($_POST["password"]);
	   $cpass = test_input($_POST["cpassword"]);
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


<section id="section2">

<?php

	
	$ok = 1;
	if(isset($_SESSION['username']))
	{
		echo "<h1>Mai intai trebuie sa va delogati!</h1>";
	} 
	elseif ($pass != $cpass)
	{
		echo "<h1>Parolele nu coincid!</h1>";
	}
    elseif ($nume == $prenume)
    {
    	echo "<h1>Numele si prenumele nu sunt diferite!</h1>";
    }
    elseif ( strlen($username) < 3 ||  strlen($username) > 21 )
    {
    	echo "<h1>Username-ul nu are intre 3 si 20 caractere!</h1>";
    }
    elseif ( strlen($pass) < 3 ||  strlen($pass) > 21 )
    {
    	echo "<h1>Parola nu are intre 3 si 20 caractere!</h1>";
    }
    else
    {
       	require("bd_connection.php");
	    if (!$conn) {
	        echo 'Failed to connect to Data Base';
	        die();
	    }
    	$s0 = oci_parse($conn, "BEGIN SELECT count(*) INTO :bind2 FROM users WHERE username = :bind1 ; END;");
	    oci_bind_by_name($s0, ":bind1", $username);
	    oci_bind_by_name($s0, ":bind2", $nr, 32);

	    oci_execute($s0, OCI_DEFAULT);

	    if ($nr >= 1)
	    {
	    	echo "<h1>Nu va puteti inregistra cu acest username! Va rugam sa reincercati!</h1>";
	    }
	    else
	    { 
		    $s = oci_parse($conn, "BEGIN user_pkg.add_new_user(:user, :pass, :nume, :prenume); commit; END;");
	     	oci_bind_by_name($s, ":user", $username);
	     	oci_bind_by_name($s, ":pass", $pass);
	     	oci_bind_by_name($s, ":nume", $nume);
	     	oci_bind_by_name($s, ":prenume", $prenume);

	     	if(oci_execute($s, OCI_DEFAULT))
	     	{      
	      		echo "<h1>V-ati inregistrat cu succes!</h1>";       
	    	}
	    	else
	    	{
	    		$e = oci_error($s);
	        	echo "Nu s-a putut realiza inregistrarea! Va rugam sa verificati datele pe care le introduceti!";
	     	}
     	}
     	oci_close($conn);
	}

	
?>    
	

</section>


<?php require("the_footer.php"); ?>

</body>

</html>