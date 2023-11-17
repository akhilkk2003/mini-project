<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$db='driving-school(2)';
$conn=mysqli_connect($dbhost,$dbuser,$dbpass,$db);
if(!$conn){
    echo "Could not connect to database: ";
}


?>