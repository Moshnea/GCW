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
	<title>Register</title>
	<link rel="stylesheet" type="text/css" href="style.css" />

</head>

<body>

<?php 
	require("the_header.php"); 
	require("pop-up.php"); 
	?>

<section id="section2">

<?php
	try{
		$ok = 1;
		$conn = NULL;
		if(isset($_SESSION['username']))
		{
			throw new Exception("Logout first!");
			
		} 
		elseif ($pass != $cpass)
		{
			throw new Exception("Passwords doesn't match!");
			
		}
	    elseif ($nume == $prenume)
	    {
	    	throw new Exception("The name and lastname aren't different!");
	    	
	    }
	    elseif ( strlen($username) < 3 ||  strlen($username) > 21 )
	    {
	    	throw new Exception("Insert an username between 3 - 20 characters!");
	    	
	    }
	    elseif ( strlen($pass) < 3 ||  strlen($pass) > 21 )
	    {
	    	throw new Exception("Insert an password between 3 - 20 characters!");
	    }
	    else
	    {
	       	require("bd_connection.php");
		    if (!$conn) {
		        throw new Exception("Unable to connect to the database");
		        
		    }
	    	$s0 = oci_parse($conn, "BEGIN SELECT count(*) INTO :bind2 FROM users WHERE username = :bind1 ; END;");
		    oci_bind_by_name($s0, ":bind1", $username);
		    oci_bind_by_name($s0, ":bind2", $nr, 32);

		    if(!oci_execute($s0, OCI_DEFAULT))
		    {
		    	throw new Exception("An error has occurred!");
		    }

		    if ($nr >= 1)
		    {
		    	throw new Exception("This username is already used! Please try again.");
		    	
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
		      		throw new Exception("Registration completed!");
		    	}
		    	else
		    	{
		    		throw new Exception("Failed! Try again!");
		     	}
	     	}
	     	oci_close($conn);
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
	

</section>


<?php require("the_footer.php"); ?>

</body>

</html>