<html>
<head><title>Oracle demo</title></head>
<body>
    <?php 
    $user_bd = "user"; // "system"
    $pass_bd = "pass";
    $name_bd = "localhost/XE"; //sau altceva
    $conn=oci_connect($user_bd, $pass_bd, $name_bd);
    if (!$conn)
        echo 'Failed to connect to Oracle';
    else
        echo 'Succesfully connected with Oracle DB';
    $s = ociparse($conn, "SELECT username FROM users");
    echo '<br />';
    if(ociexecute($s))
    {
        while (ocifetch($s)) 
        {
              
            echo "<td>" . ociresult($s, "USERNAME")."</td>";
            echo '<br />';             
             
        }
        echo "<br> Job done!";
    }
    else
    {
        $e = oci_error($s); 
        echo htmlentities($e['message']);
    }
    oci_free_statement($s);
    oci_close($conn);
?>
 
</body>
</html>