<?php
	if($_GET['actiune'] == "add_money")
	{
		$pass = test_input($_POST["password"]);
		$amount = test_input($_POST["amount"]);
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
				if ($amount != NULL)
     			{
     				if(is_numeric($amount))
     				{
		     			$s = oci_parse($conn, "BEGIN UPDATE users SET wallet = wallet + :amount WHERE username = :user; commit; END;");
		     			oci_bind_by_name($s, ":user", $_SESSION["username"]);
		     			oci_bind_by_name($s, ":amount", $amount);

		     			if(oci_execute($s, OCI_DEFAULT))
		     			{
		     				$_SESSION["wallet"] += $amount;
		     				echo '<META HTTP-EQUIV="Refresh" Content="0; URL = my_account.php">';
		     			}
		     			else
		     			{
		     				throw new Exception ('An error has occurred!');
		     			}
		     		}
		     		else
		     		{
		     			throw new Exception ('The amount you entered is not a number!');
		     		}
	     		}
     			else
     			{
     				throw new Exception ("You didn't entered an amount!");
     			}	
			}
			else
			{
				throw new Exception ('Wrong Password!');
			}
		}
		oci_close($conn);
	}

?>