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
            $default_elem = 5;
        }
        
        $s = ociparse($conn, "SELECT count(*) from 
                			( select p.denumire, v.data_cumparare from vanzari v, produse p 
                    		where v.username = :user_name and v.id_produs = p.id_produs)
        				");
        oci_bind_by_name($s, ":user_name", $_SESSION["username"]);
        echo '<br />';

        if(ociexecute($s))
        {
            ocifetch($s);
              
            $rows = ociresult($s, "COUNT(*)");
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
        $index = 1;
        echo '<div style="text-align:center;">';
        if (ociexecute($s, OCI_DEFAULT))
        {
            echo '<table style="border: 1px solid #AAA; text-align:center; margin: 0 auto;">';
            while (ocifetch($s)) 
            {
                echo "<tr";
                if ($index % 2 == 1 )
                    echo ' bgcolor="#B3B3B3"' ;
                echo '>';   
                echo "<td>Ati cumparat produsul: <b>".ociresult($s, "DENUMIRE") . "</b> la data: <i>" . ociresult($s, "DATA_CUMPARARE") ."</i></td>";
                echo '</tr>';
                $index ++;             
            }
            echo "</table>";
        }

        $next = $default_pagina + 1;
        $anterior  = $default_pagina - 1;


        echo '<br>';
        if ($default_pagina <= 1)
            echo "<button disabled onclick=\"location.href='$default_locatie_pagina"."?pagina=$anterior&elem=$default_elem#openHistory'\"> Previous </button>";
        else
            echo "<button onclick=\"location.href='$default_locatie_pagina"."?pagina=$anterior&elem=$default_elem#openHistory'\"> Previous </button>";
        echo " Pagina: $default_pagina";
        if ($default_pagina >= $max_pagini)
         echo    "<button disabled onclick=\"location.href='$default_locatie_pagina"."?pagina=$next&elem=$default_elem#openHistory'\"> Next </button>";
        
        else
            echo    "<button onclick=\"location.href='$default_locatie_pagina"."?pagina=$next&elem=$default_elem#openHistory'\"> Next </button>";
        echo "</div><br><br>";
?>

</div>