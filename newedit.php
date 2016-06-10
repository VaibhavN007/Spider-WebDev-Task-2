<html>
<head>
<title></title>
</head>
<body>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "demo_form";
$con="";
$Name="";
$Roll = substr($_SERVER['REQUEST_URI'],-9);
$Dept = "";
$Email = "";
$Address = "";
$About = "";
$message = "";
$name_error = "";
$roll_error = "";
$email_error = "";
$result = "";
$row = "";

	
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

$sql_get = "SELECT * FROM student WHERE Roll_Number = ".$Roll;

if($result = mysqli_query($con,$sql_get))
{
	while($row = mysqli_fetch_row($result)){
		$Name = $row[0];
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
if(isset($_POST["submit"])){
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
	
	$sql_update = "UPDATE ".$dbname.".student SET Name = '".$Name."' ,Department = '".$Dept."' ,Email = '".$Email."' "
			.",Address = '".$Address."' ,About = '".$About."'"." WHERE Roll_Number=".$Roll;
	
	
	if(!mysqli_query($con,$sql_update)){
		$message = "Data not Updated.";
	}
	else
	{
		$message = "Data successfully Updated!";
	}
}

}
?>

<form  method="post" name ="editForm" action=" <?php echo ("http://".$_SERVER["HTTP_HOST"].$_SERVER['REQUEST_URI']);?> ">

<p>Name* : 
<input type="text" name="name" value="<?php echo $Name;?>">
<span class="error"><?php echo $name_error;?></span>
</p>

<p>Roll Number* : <input type="text" name="roll" value="<?php echo $Roll;?>" readonly></input>
<span class = "error"><?php echo $roll_error;?></span>
</p>

<p>Department : 
<select name="dept">
	<option value="archi" <?php if($Dept == "archi") echo "selected";?> >ARCHI</option>
	<option value="civil" <?php if($Dept == "civil") echo "selected";?> >CIVIL</option>
	<option value="cse" <?php if($Dept == "cse") echo "selected";?> >CSE</option>
	<option value="eee" <?php if($Dept == "eee") echo "selected";?> >EEE</option>
	<option value="ece" <?php if($Dept == "ece") echo "selected";?> >ECE</option>
	<option value="ice" <?php if($Dept == "ice") echo "selected";?> >ICE</option>
	<option value="mech" <?php if($Dept == "mech") echo "selected";?> >MECH</option>
	<option value="meta" <?php if($Dept == "meta") echo "selected";?> >META</option>
	<option value="prod" <?php if($Dept == "prod") echo "selected";?> >PROD</option>
</select>
</p>

<p>Email Address : <input type="text" name="email" value="<?php echo $Email;?>" >
<span class = "error"><?php echo $email_error;?></span>
</p>

<p>Physical Address : <input type="text" name="address" value="<?php echo $Address;?>" ></p>

<p>About Me : <br><textarea name ="about" rows="5" cols="30" ><?php echo $About; ?></textarea></p>

<input type="submit" name = "submit" value="Submit"/>

</form>
<?php echo $message?>

<form method="post" action=<?php echo "http://".$_SERVER['HTTP_HOST']."/viewStudent.php" ?>>
<input type="submit" name = "submit" value="VIEW STUDENT"/>
</form>

</body>
</html>