<?php
$default_locatie_pagina = htmlspecialchars($_SERVER["PHP_SELF"]);
try{
    $conn = NULL;
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
            throw new Exception("An error has occurred!");
        }

        $max_pagini = floor($rows / $default_elem);
        if ($rows % $default_elem != 0)
            $max_pagini++;

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
                    echo ' bgcolor="#f24a4a"' ;
                echo '>';   
                echo "<td style=\"font-family: Corbel;\">Ati cumparat produsul: <b>".ociresult($s, "DENUMIRE") . "</b> la data: <i>" . ociresult($s, "DATA_CUMPARARE") ."</i></td>";
                echo '</tr>';
                $index ++;             
            }
            echo "</table>";
        }
        else
        {
            throw new Exception("An error has occurred!");
        }

        $next = $default_pagina + 1;
        $anterior  = $default_pagina - 1;

        echo '<br>';
        if ($default_pagina <= 1)
            echo "<button disabled onclick=\"location.href='$default_locatie_pagina"."?pagina=$anterior&elem=$default_elem#openHistory'\"> Previous </button>";
        else
            echo "<button onclick=\"location.href='$default_locatie_pagina"."?pagina=$anterior&elem=$default_elem#openHistory'\"> Previous </button>";
        echo "<span style =\"font-family: Corbel; color: #f24a4a; font-weight: bold;\"> Page: $default_pagina </span>";
        if ($default_pagina >= $max_pagini)
         echo    "<button disabled onclick=\"location.href='$default_locatie_pagina"."?pagina=$next&elem=$default_elem#openHistory'\"> Next </button>";
        
        else
            echo    "<button onclick=\"location.href='$default_locatie_pagina"."?pagina=$next&elem=$default_elem#openHistory'\"> Next </button>";
        echo "</div><br><br>";
    }catch (Exception $e)
    {
        $_SESSION['message'] = $e->getMessage();
        echo '<META HTTP-EQUIV="Refresh" Content="0; URL = index.php#errors">';
    } finally {
        if ($conn != NULL)
            oci_close($conn);
    }

?>

</div>