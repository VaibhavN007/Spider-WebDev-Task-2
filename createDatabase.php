<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Student_Database";
$tablename = "student";
$message = "";

// Create connection
$con = mysqli_connect($servername, $username, $password);

// Check connection
if (!$con) {
    $message = "Not connected to server";
}

//echo "Connected successfully";

if(!mysqli_select_db($con,$dbname))
{
	$message = "Database not selected";
}
//Create database named 'Student_Database'
$sql_createDB = "CREATE DATABASE ".$dbname;
if(!mysqli_query($con,$sql_createDB)){
	$message = "Database cannot be created.";
}
//Select the database just created
if(!mysqli_select_db($con,$dbname))
{
	$message = "Database not selected";
}
//Create the table named Student
$sql_createTB = "CREATE TABLE ".$tablename."( Name VARCHAR(255) NOT NULL,Roll_Number INT(9) UNSIGNED PRIMARY KEY, Department VARCHAR(20) NOT NULL, Email VARCHAR(255), Address VARCHAR(255),About VARCHAR(255),Passcode VARCHAR(6))";

if(mysqli_query($con,$sql_createTB)){
	$message = "Database 'Student_Database' created successfully.\n\nTable 'Student' created successfully.";	
}
else
{
	$message = "Database 'Student_Database' created successfully.\n\nTable 'Student' cannot be created.";
}

echo nl2br($message);

?>