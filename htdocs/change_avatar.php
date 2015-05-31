<?php
	if($_GET['actiune'] == "change_avatar")
	{
		$pass = test_input($_POST["password"]);
		$avatar = test_input($_POST["avatar"]);

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
	     			else
	     			{
	     				throw new Exception ('An error has occurred!');
	     			}
	     		}
	 			else
	 			{
	 				throw new Exception ('No URL!');
	 			}	
			}
			else
			{
				//echo '<p class="error">Nu s-a gasit accest account!!</p>';
				throw new Exception ('Wrong Password!');
			}
		}
		oci_close($conn);
	}
?>