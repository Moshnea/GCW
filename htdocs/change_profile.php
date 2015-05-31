<?php
	if($_GET['actiune'] == "change_profile")
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

		if (!$conn) 
		{
    		throw new Exception ('Failed to connect to Data Base!');
		}
		else
		{
			$sql = oci_parse($conn, "BEGIN SELECT count(*) INTO :bind1 FROM users WHERE username = :username AND password = :pass; END;");
			oci_bind_by_name($sql, ":bind1", $nr);
			oci_bind_by_name($sql, ":username", $_SESSION["username"]);
			oci_bind_by_name($sql, ":pass", $pass);
			if(!oci_execute($sql, OCI_DEFAULT))
				throw new Exception ('An error has occurred!');
			
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
	     				throw new Exception ('The new passwords are different!');
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
     				throw new Exception ('An error has occurred!');
     		}
			else
			{
				throw new Exception ('Wrong Password!');
			}
		}
		oci_close($conn);
	}
?>