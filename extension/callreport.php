<?php

	if($_SERVER['REQUEST_METHOD']=='POST'){
		$username = $_POST['username'];
		$date = $_POST['date'];
		$client = $_POST['client'];
		$contact = $_POST['contact'];
		$phone = $_POST['phone'];
		$purpose = $_POST['purpose'];
		$outcome = $_POST['outcome'];
		$longitude = $_POST['longitude'];
		$latitude = $_POST['latitude'];
		
		require_once('dbConnect.php');
		
		$sql = "INSERT INTO tbl_app_agentLocation (agent,date,client_name,time_in,purpose_of_visit,longitude,latitude) VALUES ('$username','$date','$client','$contact','$purpose','$longitude','$latitude')";
		
		
		if(mysqli_query($con,$sql)){
			echo "Report Submitted Succesfully";
		}else{
			echo "Could not Submit";

		}
	}else{
echo 'error';
}