<?php
$servername = "localhost";
$user = "bsacoke_dcornel";
$password = "g-aX_XhC]Ow2";
$dbname = "bsacoke_module_reports";

//Fetch Data

$date = $_POST['date'];

$client = $_POST['client'];

$type = $_POST['type'];

$route = $_POST['route'];

$code = $_POST['code'];

$amount = $_POST['amount'];

$comment = $_POST['comment'];

$username = $_POST['username'];




//Establish connection and set a few what nots
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $user, $password);

// set the PDO error mode to exception
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


//Insert into table 1 
	try {   
		
		$sql1 = "INSERT INTO tbl_cash_collection (Collector, Route, Client, Payment_type, ChequeNumber, ChequeAmount, Comments, Date)
		VALUES ('" .$username. "', '" .$route. "', '" .$client. "', '" .$type. "', '" .$code. "','" .$amount. "','" .$comment. "', '" .$date. "')";
		
		// use exec() because no results are returned
		$conn->exec($sql1);
		echo "Table 1 New record created successfully <br/>";

	}catch(PDOException $e){
		
		echo $sql1 . "<br>" . $e->getMessage();
	}
	
//Insert into table 2 
	/*try {   
		
		$sql1 = "INSERT INTO tbl_ourspace (outlet, product, product_id, our_space, date)
		VALUES ('" .$client. "', '" .$product. "', '" .$pid. "', '" .$ospace. "', '" .$date. "')";
		
		// use exec() because no results are returned
		$conn->exec($sql1);
		echo "Table 2 New record created successfully <br/>";

	}catch(PDOException $e){
		
		echo $sql1 . "<br>" . $e->getMessage();
	}*/

$conn = null;