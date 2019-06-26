<?php

	if($_SERVER['REQUEST_METHOD']=='POST'){
		$username = $_POST['user'];
		$date = $_POST['date'];
		$time_in = $_POST['time_in'];
		$client = $_POST['client'];
		$purpose = $_POST['purpose'];
		$time_out = $_POST['time_out'];
		$comment = $_POST['comment'];
		$longitude = $_POST['longitude'];
		$latitude = $_POST['latitude'];
		$contact = $_POST['contact'];
		$number = $_POST['number'];
		
		require_once('dbConnect.php');
		
		$sql = "INSERT INTO tbl_app_agentlocation (agent,date,time_in,client_name,purpose_of_visit,time_out,contact_person,phone_number,comment,longitude,latitude) VALUES ('$username','$date','$time_in','$client','$purpose','$time_out','$contact','$number','$comment','$longitude','$latitude')";
		
		
		if(mysqli_query($con,$sql)){
			echo "Successfully Inserted";
		}else{
			echo "Could not Insert";

		}
	}else{
echo 'error';
}