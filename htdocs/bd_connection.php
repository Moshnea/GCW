<?php
$user_bd = "system"; // "system"
$pass_bd = "pass";
$name_bd = "localhost/orcl"; //sau altceva
$conn=oci_connect($user_bd, $pass_bd, $name_bd);
?>