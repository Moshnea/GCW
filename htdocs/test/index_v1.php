<!DOCTYPE html>
<html>
<head><title>Formular Web</title></head>

<body bgcolor="black" style="color:gray" align="center">
<?php
	
	$name = $pass = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
	   $name = test_input($_POST["username"]);
	   $pass = test_input($_POST["password"]);
	}

	function test_input($data) {
	   $data = trim($data);
	   $data = stripslashes($data);
	   $data = htmlspecialchars($data);
	   return $data;
	}
?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 

	<p> Username: <input type="text" name="username" size="20" placeholder="Nume" /> </p>
	<p> Password: <input type="password" name="password" size="20" placeholder="Parola" /> </p>

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
    while ($name != ""){
	    $s = oci_parse($conn, "BEGIN user_pkg.gen_users(3); user_pkg.exist_user(:bind1 , :bind2, :bind3); rollback; END;");
	    oci_bind_by_name($s, ":bind1", $name);
	    oci_bind_by_name($s, ":bind2", $pass);
	    oci_bind_by_name($s, ":bind3", $ok, 32);
	    //oci_execute($s, OCI_DEFAULT);
	    //echo "Procedure returned value: " . $ok;
	    if(oci_execute($s, OCI_DEFAULT))
	    {	    	
	        if($ok == 1)
	        	echo "<h1>V-ati conectat cu succes la server!</h1>";
	        else
	        	echo "<h1>Nume sau parola gresite!</h1>";
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