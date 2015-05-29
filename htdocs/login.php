<?php
	session_start();
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
    if(isset($_SESSION['username']))
        echo "<h1>Sunteti deja conectat!!!";
    else 
    {
    	require("bd_connection.php");
        if (!$conn) {
            echo 'Failed to connect to Oracle';
            die();
        }

        
        $s = oci_parse($conn, "BEGIN user_pkg.exist_user(:bind1 , :bind2, :bind3); END;");
        oci_bind_by_name($s, ":bind1", $name);
        oci_bind_by_name($s, ":bind2", $pass);
        oci_bind_by_name($s, ":bind3", $ok, 32);

        if(oci_execute($s, OCI_DEFAULT))
        {	    	
            if($ok == 1)
            {
            	echo "<h1>V-ati conectat cu succes la server!</h1>";
            	$s0 = oci_parse($conn, "BEGIN SELECT nume, prenume INTO :bind2, :bind3 FROM users WHERE username = :bind1 ; END;");
            	oci_bind_by_name($s0,":bind1",$name);
            	oci_bind_by_name($s0,":bind2",$nume, 40);
            	oci_bind_by_name($s0,":bind3",$prenume, 40);
            	oci_execute($s0, OCI_DEFAULT);
            	$_SESSION['username'] = $name;
            	$_SESSION['nume'] = $nume;
            	$_SESSION['prenume'] = $prenume;
            	echo '<META HTTP-EQUIV="Refresh" Content="0; URL = my_account.php">';

            }
            else
            	echo "<h1>Nume sau parola gresite!</h1>";
        }
        else
        {
            $e = oci_error($s); 
            echo htmlentities($e['message']);
        }
        oci_close($conn);
    }
?>
	

</section>


<?php require("the_footer.php"); ?>

</body>

</html>