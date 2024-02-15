<?php

function connect_to_db(){
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "cinema";

$con= mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);

if(!$con)
{
    die("Conexiune esuata: " . mysqli_connect_error());
}
return $con;

}
?>
