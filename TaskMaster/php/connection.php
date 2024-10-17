<?php

$dbhost = "localhost";
$dbport = 3310;
$dbuser = "root";
$dbpass = "123";
$dbname = "taskdb";

if(!$con = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname, $dbport))
{

	die("failed to connect!");
}

