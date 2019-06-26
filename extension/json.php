<?php 

	$sql = "SELECT * FROM tbl_returnreasons";
	
	require_once('dbConnect.php');
	
	$r = mysqli_query($con,$sql);
	
	$result = array();
	
	while($row = mysqli_fetch_array($r)){
		array_push($result,array(
			'description'=>$row['description'],
			'ID'=>$row['ID'],
		));
	}
	
	echo json_encode(array('result'=>$result));
	
	mysqli_close($con);