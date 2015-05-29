<!DOCTYPE html>
<html>
<head><title>Register</title></head>

<body bgcolor="black" style="color:gray" align="center">
<?php

// 	p_user in varchar2
//  p_pass in varchar2
//  p_nume in varchar2
//  p_prenume in varchar2
	$user = $pass = $cpass = $nume= $prenume = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
	   $user = test_input($_POST["username"]);
	   $pass = test_input($_POST["password"]);
	   $cpass = test_input($_POST["c_password"]);
	   $nume = test_input($_POST["nume"]);
	   $prenume = test_input($_POST["prenume"]);
	}

	function test_input($data) {
	   $data = trim($data);
	   $data = stripslashes($data);
	   $data = htmlspecialchars($data);
	   return $data;
	}
?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 

	<p> Username: <input type="text" name="username" size="20" placeholder="Username" /> </p>
	<p> Password: <input type="password" name="password" size="20" placeholder="Parola" /> </p>
	<p> Confirm Password: <input type="password" name="c_password" size="20" placeholder="Confirmati Parola" /> </p>
	<p> Nume: <input type="text" name="nume" size="20" placeholder="Nume" /> </p>
	<p> Prenume: <input type="text" name="prenume" size="20" placeholder="Prenume" /> </p>

	<p><input type="submit" title="Login" /> </p>
	<?php
		session_start();
	?>
</form>

<?php
	
	$user_bd = "user"; // "system"
	$pass_bd = "pass";
	$name_bd = "localhost/XE"; //sau altceva
	$conn=oci_connect($user_bd, $pass_bd, $name_bd);
    if (!$conn) {
        echo 'Failed to connect to Oracle';
        die();
    }
    while ($user != ""){
    	if (strcmp($pass, $cpass) !=0 ){
    		echo "<br> Parolele trebuie sa se potriveasca!!";
    		break;
    	}
	    $s = oci_parse($conn, "BEGIN user_pkg.add_new_user(:user, :pass, :nume, :prenume); rollback; END;");
	    oci_bind_by_name($s, ":user", $user);
	    oci_bind_by_name($s, ":pass", $pass);
	    oci_bind_by_name($s, ":nume", $nume);
	    oci_bind_by_name($s, ":prenume", $prenume);
	    //oci_execute($s, OCI_DEFAULT);
	    //echo "Procedure returned value: " . $ok;
	    if(oci_execute($s, OCI_DEFAULT))
	    {	    	
	        echo "<h1>V-ati inregistrat cu succes!</h1>";	        
	    }
	    else
	    {
	        $e = oci_error($s); 
	        echo htmlentities($e['message']);
	        echo '<br>error';
	    }
	    break;
	}
    oci_close($conn);
?>

</body>
</html>