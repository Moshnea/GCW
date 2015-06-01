<?php
	session_start();
	$username = $pass = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
	   $username = test_input($_POST["username"]);
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
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="style.css" />

</head>

<body>

<?php 
    require("the_header.php");
    require("pop-up.php"); 
?>

<section id="section2">

<?php
try
{
    if(isset($_SESSION['username']))
        throw new Exception("You are already logged-in!");
        
    else 
    {
    	require("bd_connection.php");
        if (!$conn) {
            throw new Exception("Unable to connect to database!");
        }
        
        $s = oci_parse($conn, "BEGIN user_pkg.exist_user(:bind1 , :bind2, :bind3); END;");
        oci_bind_by_name($s, ":bind1", $username);
        oci_bind_by_name($s, ":bind2", $pass);
        oci_bind_by_name($s, ":bind3", $ok, 32);

        if(oci_execute($s, OCI_DEFAULT))
        {	    	
            if($ok == 1)
            {
            	$s0 = oci_parse($conn, "BEGIN
                                            SELECT u.nume, u.prenume, u.profile.gender, u.profile.regiune, u.profile.url_avatar, u.wallet
                                            INTO :name, :lastname, :gender, :region, :url_avatar, :wallet
                                            FROM users u 
                                            WHERE u.username = :username ; 
                                        END;");
            	oci_bind_by_name($s0,":username",$username);
            	oci_bind_by_name($s0,":name",$name, 40);
            	oci_bind_by_name($s0,":lastname",$lastname, 40);
                oci_bind_by_name($s0,":gender",$gender, 40);
                oci_bind_by_name($s0,":region",$region, 40);
                oci_bind_by_name($s0,":url_avatar",$avatar, 40);
                oci_bind_by_name($s0,":wallet",$wallet, 40);
            	if(!oci_execute($s0, OCI_DEFAULT))
                {
                    throw new Exception("An error has occurred!");
                }

            	$_SESSION['username'] = $username;
            	$_SESSION['name'] = $name;
            	$_SESSION['lastname'] = $lastname;
                $_SESSION['wallet'] = $wallet;

                if($gender == 0)
                {
                    $_SESSION['gender'] = "M";
                }
                else
                { 
                    $_SESSION['gender'] = "F";
                }

                if($region == NULL)
                {
                    $_SESSION['region'] = "N/A";
                }
                else
                {
                    $_SESSION['region'] = $region;
                }
                if($avatar == NULL)
                {
                    $_SESSION['avatar'] = "/img/default_avatar.jpg";
                }
                else
                {
                    $_SESSION['avatar'] = $avatar;
                }
            	echo '<META HTTP-EQUIV="Refresh" Content="0; URL = my_account.php">';
            }
            else
            	throw new Exception("The username or password are wrong!");        
        }
        else
        {
            throw new Exception("An error has occurred!");
            
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