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
$message = "";
$name_error = "";
$roll_error = "";
$email_error = "";
$passcode = "";
$passcode_message = "";
if(isset($_POST["submit"])){
	
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

if(empty($_POST["name"])){
	$name_error = "*Name is required.";
	$message = "Data not inserted.";
}
else{
	$Name = test_input($con,$_POST['name']);

    if(!preg_match("/^[a-zA-Z ]*$/",$Name)){
		$name_error = "Only letters and white spaces allowed.";
		$message = "Data not inserted.";
	}
}

if(empty($_POST["roll"])){
	$roll_error = "*Roll number is required.";
	$message = "Data not inserted.";
}
else{
	
	$Roll = test_input($con,$_POST['roll']);

	if(strlen($Roll)==9){
		if(preg_match("/^[1-9][0-9]{0,8}$/",$Roll)){
			$roll_error = "";
		}
		else{
			$roll_error = "Invalid Roll number";
			$message = "Data not inserted.";
		}
	}
	else{
		$roll_error = "Roll number should contain 9 digits";
		$message = "Data not inserted.";
	}
    
}

$Dept = test_input($con,$_POST['dept']);
$Email = test_input($con,$_POST['email']);
if(!empty($Email)){
	
	if(!filter_var($Email,FILTER_VALIDATE_EMAIL)){
		$email_error = "Invalid email format.";
		$message = "Data not inserted.";
	}
	
	$email_end = "@nitt.edu";        //email should end with @nitt.edu

	$pos = stripos($Email,$email_end);	

	$email_length = strlen($Email);

	if(($email_length-$pos)===9){
		$email_error = "";
	}
	else{
		$email_error = "Email should end with @nitt.edu";
		$message = "Data not inserted.";
	}
}
$Address = test_input($con,$_POST['address']);
$About = test_input($con,$_POST['about']);

if(empty($message)){
	
	$passcode = strtolower(substr(str_shuffle($Name.$Roll.$Dept),0,6));
	
	$passcode_message = "Your password is ".$passcode;
	
	$sql = "INSERT INTO student VALUES ('$Name','$Roll','$Dept','$Email','$Address','$About','$passcode')";

	if(!mysqli_query($con,$sql)){
		$message = "Data not inserted.";
	}
	else
	{
		$message = "Data successfully inserted!";
	}
}

}
?>

<html>
<head>
<title>STUDENT DATABASE</title>
<style>
.error{
color:#FF0000;
}
</style>
</head>
<?php

?>
<body>
<form  method="post" action=" <?php echo ($_SERVER['PHP_SELF']);?> ">

<p>Name* : 
<input type="text" name="name" value="<?php echo $Name;?>">
<span class="error"><?php echo $name_error;?></span>
</p>

<p>Roll Number* : <input type="text" name="roll" value="<?php echo $Roll;?>" >
<span class = "error"><?php echo $roll_error;?></span>
</p>

<p>Department : 
<select name="dept">
	<option value="archi">ARCHI</option>
	<option value="civil">CIVIL</option>
	<option value="cse">CSE</option>
	<option value="eee">EEE</option>
	<option value="ece">ECE</option>
	<option value="ice">ICE</option>
	<option value="mech">MECH</option>
	<option value="meta">META</option>
	<option value="prod">PROD</option>
</select>
</p>

<p>Email Address : <input type="text" name="email" value="<?php echo $Email;?>" >
<span class = "error"><?php echo $email_error;?></span>
</p>

<p>Physical Address : <input type="text" name="address" value="<?php echo $Address;?>" ></p>

<p>About Me : <br><textarea name ="about" rows="5" cols="30" value="<?php echo $About; ?>" ></textarea></p>

<input type="submit" name = "submit" value="Submit"/>

</form>
<br>
<?php echo $message?>
<?php echo $passcode_message?>
<form method="post" action=<?php echo "http://".$_SERVER['HTTP_HOST']."/viewStudent.php" ?>>
<input type="submit" name = "submit" value="VIEW STUDENT"/>
</form>
</body>
</html>