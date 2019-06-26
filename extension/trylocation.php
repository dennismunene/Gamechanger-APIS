<?php
$servername = "localhost";
$username = "bsacoke_dcornel";
$password = "g-aX_XhC]Ow2";
$dbname = "bsacoke_module_reports";

//Fetch Data
$username = $_POST['user'];

$date = $_POST['date'];

$client = $_POST['client'];

$time_in = $_POST['time_in'];

$purpose = $_POST['purpose'];

$time_out = $_POST['time_out'];

$comment = $_POST['comment'];

$contact_person = $_POST['contact'];

$number = $_POST['number'];

$longitude = $_POST['longitude'];

$latitude = $_POST['latitude'];




//Establish connection and set a few what nots
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

// set the PDO error mode to exception
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


	try {   
		
		$sql1 = "INSERT INTO tbl_app_agentlocation (agent, date, client_name, time_in, purpose_of_visit, time_out, contact_person, number, comment, longitude, latitude)
		VALUES ('" .$username. "','" .$date. "', '" .$client. "', '" .$time_in. "', '" .$purpose. "', '" .$time_out. "', '" .$contact_person. "', '" .$number. "' '" .$comment. "', '" .$longitude. "', '" .$latitude. "')";
		
		// use exec() because no results are returned
		$conn->exec($sql1);
		echo "Table 1 New record created successfully <br/>";

	}catch(PDOException $e){
		
		echo $sql1 . "<br>" . $e->getMessage();
	}
	

$conn = null;