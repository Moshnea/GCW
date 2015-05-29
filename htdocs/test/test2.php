<html>
<head><title>Oracle demo</title></head>
<body>
<?php 

    // define variables and set to empty values
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
    

    $user_bd = "user"; // "system"
    $pass_bd = "pass";
    $name_bd = "localhost/XE"; //sau altceva
    $conn=oci_connect($user_bd, $pass_bd, $name_bd);
    if (!$conn) {
        echo 'Failed to connect to Oracle';
    }

    echo "<h2>Your Input:</h2>";
    echo $name;
    echo "<br>";
    echo $pass;
    echo "<br>";

    $s = oci_parse($conn, "BEGIN USER_PKG.EXIST_USER(:bind1 , :bind2, :bind3); END;");
    oci_bind_by_name($s, ":bind1", $name);
    oci_bind_by_name($s, ":bind2", $pass);
    oci_bind_by_name($s, ":bind3", $out_var, 40); // 32 is the return length
    oci_execute($s, OCI_DEFAULT);
    echo "Procedure returned value: " . $out_var;
    
    
    // if(oci_execute($s, OCI_DEFAULT))
    // {
    //     echo "Procedure returned value: " . $out_var;
    // }
    // else
    // {
    //     echo $s . "<br>";
    //     $e = oci_error($s); 
    //     echo htmlentities($e['message']);
    //     echo '<br>error';
    // }
    oci_free_statement($s);
    oci_close($conn);

?>
 
</body>
</html>