<?php
$default_locatie_pagina = htmlspecialchars($_SERVER["PHP_SELF"]);


    require("bd_connection.php");
	if (isset($_GET['elem']) && isset($_GET['pagina']))
        {
            $default_pagina = $_GET['pagina'];
            $default_elem = $_GET['elem'];
        }
        else{
            $default_pagina = 1;
            $default_elem = 20;
        }

        
        $s = ociparse($conn, "SELECT count(*) FROM emp");
        echo '<br />';

        if(ociexecute($s))
        {
            //while (ocifetch($s)) 
            //{
                ocifetch($s);
                  
                 $rows = ociresult($s, "COUNT(*)");
            //}
        }
        else
        {
            $e = oci_error($s); 
            echo htmlentities($e['message']);
        }

        $max_pagini = floor($rows / $default_elem);
        if ($rows % $default_elem != 0)
            $max_pagini++;
        //echo $max_pagini;

        $s = ociparse($conn, 
            "SELECT * from 
                ( select p.denumire, v.data_cumparare, rownum r from vanzari v, produse p 
                    where v.username = :user_name and v.id_produs = p.id_produs)
            where r <= $default_pagina*$default_elem and r > ($default_pagina-1)*$default_elem");

        oci_bind_by_name($s, ":user_name", $_SESSION["username"]);
        if (ociexecute($s, OCI_DEFAULT))
        {
            while (ocifetch($s)) 
            {
                
                echo "<td>Produsul: ".ociresult($s, "DENUMIRE") . " cumparat la data: " . ociresult($s, "DATA_CUMPARARE") ."</td>";
                echo '<br />';
                
             
            }
        }

        
        $next = $default_pagina + 1;
        $anterior  = $default_pagina - 1;

        //echo '<form action="http://localhost/oracle/selectDemo.php?pagina=$next&elem=$default_elem">      <input type="button" name="next" value="next"> </form>';
        //echo "<form action=\"http://localhost/oracle/selectDemo.php?pagina=$next&elem=$default_elem\">      <input type=\"submit\"> </form>";
        echo "<br>";
        if ($default_pagina <= 1)
            echo "<button disabled onclick=\"location.href='$default_locatie_pagina"."?pagina=$anterior&elem=$default_elem'\"> Previous </button>";
        else
            echo "<button onclick=\"location.href='$default_locatie_pagina"."?pagina=$anterior&elem=$default_elem'\"> Previous </button>";
        echo " Pagina: $default_pagina";
        if ($default_pagina >= $max_pagini)
         echo    "<button disabled onclick=\"location.href='$default_locatie_pagina"."?pagina=$next&elem=$default_elem'\"> Next </button>";
        
        else
            echo    "<button onclick=\"location.href='$default_locatie_pagina"."?pagina=$next&elem=$default_elem'\"> Next </button>";
        echo "<br><br>";
?>


    <form action="my_account.php" method="GET">
Pagina:<br>
<input type="text" name="pagina">
<br>
Numar de elemente:<br>
<input type="text" name="elem">
<br><br>
<input type="submit" value="Submit">
</form> 

</div>