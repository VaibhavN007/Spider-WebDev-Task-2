<html>
<head>
<title>CONFIRM PASSWORD</title>
</head>
<body>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "demo_form";
$message="";
$result="";
$row="";

$Roll = substr($_SERVER['REQUEST_URI'],-9);
$In_Passcode = "";
$Passcode = "dresqw3";
$Next_url="";

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

$sql_pass = "SELECT passcode FROM student WHERE roll_number=".$Roll;

if($result = mysqli_query($con,$sql_pass)){
	while($row = mysqli_fetch_row($result)){
		$Passcode = $row[0];
    }
}
if(isset($_POST['submit'])){
	$In_Passcode = $_POST["pass"];
	echo nl2br("\n\nEntered Passcode is ".$In_Passcode);
	if($In_Passcode===$Passcode){
		$Next_url = "http://localhost/newedit.php?edit_roll_no=".$Roll;
	}
	else{
		$message = "Wrong Passcode";
		$Next_url = "http://".$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]."?edit_roll_no=".$Roll;
	}
	header("Location: ".$Next_url);
}

?>

<form method="POST" action="" name="confirmForm">
<p>Roll number: <input type="text" name="roll" readonly value="<?php echo $Roll;?>"></input></p>
<p>Enter Passcode : <input type="password" name="pass"></input></p>   
<input type="submit" name="submit" value="submit"/>
<?php echo $message?>
</form>
</body>
<html>