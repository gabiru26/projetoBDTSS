<?php
function OpenCon()
{
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $db = "projeto_bdtss";

    $conn = new mysqli($dbhost, $dbuser, $dbpass, $db) 
    or die("Connect failed: %s\n". $conn->error);

    return $conn;
}

$conn = OpenCon();

?>