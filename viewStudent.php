<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "demo_form";
$con="";
$Name="";
$Roll = "";
$Dept = "";
$Email = "";
$Address = "";
$About = "";
$details = "";
$roll_error = "";
$result="";
$row="";

if(isset($_GET["submit"])){
	
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

//Create a function to validate the input data
function test_input($link,$data){
	$data = trim($data);
	$data = stripslashes($data);
	$data = strip_tags($data);
	$data = htmlspecialchars($data);
	$data = mysqli_real_escape_string($link,$data);
	return $data;
}


if(empty($_GET["roll"])){
	$roll_error = "*Roll number is required.";
}
else{
	
	$Roll = test_input($con,$_GET['roll']);

	if(strlen($Roll)==9){
		if(preg_match("/^[1-9][0-9]{0,8}$/",$Roll)){
			$roll_error = "";
		}
		else{
			$roll_error = "Invalid Roll number";
		}
	}
	else{
		$roll_error = "Roll number should contain 9 digits";
	}
    
}

if(empty($roll_error)){
	
	$sql = "SELECT * FROM student WHERE Roll_Number = ".$Roll;

	if($result = mysqli_query($con,$sql)){
		while($row = mysqli_fetch_row($result)){
			$Name = $row[0];
			$Roll = $row[1];
			$Dept = $row[2];
			$Email = $row[3];
			$Address = $row[4];
			$About = $row[5];
		}
		if(empty($Name)){
			$details = "Sorry! No match found";
		}
		else{
			$details = " Name : ".$Name."\n Roll number : ".$Roll."\n Department : ".$Dept."\n Email : ".$Email
						."\n Address : ".$Address."\n About : ".$About;
		}
		mysqli_free_result($result);
	}
	else
	{
		$details = "Sorry! No data found!";
	}
}

}
?>

<html>
<head>
<title>VIEW STUDENT DATABASE</title>
<style>
.error{
color:#FF0000;
}
</style>
</head>
<?php

?>
<body>
<form  method="get" name="viewForm" action=" <?php echo ($_SERVER['PHP_SELF']);?> ">

<p>Roll Number* : <input type="text" name="roll" value="<?php echo $Roll;?>" >
<span class = "error"><?php echo $roll_error;?></span>
</p>
<input type="submit" name = "submit" value="Submit" onclick="clickEvent()" />

</form>

<button onclick="sendData()">EDIT</button>

<form name="editForm" method="get" action="http://localhost/confirmStudent.php?edit_roll_no="<?php echo $Roll;?>>

<input type = "hidden" name = "edit_roll_no"></input>

</form>

<script>
	function sendData(){
		document.editForm.edit_roll_no.value = document.viewForm.roll.value;
		document.editForm.submit();
	}
</script>

<br>
<?php echo nl2br($details) ?>
</body>
</html>