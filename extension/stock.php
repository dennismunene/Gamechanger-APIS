<?php
$servername = "localhost";
$username = "bsacoke_dcornel";
$password = "g-aX_XhC]Ow2";
$dbname = "bsacoke_module_reports";

//Fetch Data

$date = $_POST['date'];

//$client = $_POST['client'];

$product = $_POST['product'];

$oprice = $_POST['ourprice'];

$cprice = $_POST['compprice'];

$competitor = $_POST['compname'];

$pid = $_POST['pid'];

$rid = $_POST['rid'];




//Establish connection and set a few what nots
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

// set the PDO error mode to exception
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


//Insert into table 1 
	try {   
		
		$sql1 = "INSERT INTO tbl_stock_level (c_route, c_outlet, stock_qty, product, product_uom, threshold, date)
		VALUES ('" .$rid. "','" .$pid. "', '" .$oprice. "', '" .$product. "', '" .$competitor. "', '" .$cprice. "', '" .$date. "')";
		
		// use exec() because no results are returned
		$conn->exec($sql1);
		echo "Table 1 New record created successfully <br/>";

	}catch(PDOException $e){
		
		echo $sql1 . "<br>" . $e->getMessage();
	}
	
/*Insert into table 2 
	try {   
		
		$sql1 = "INSERT INTO tbl_ourprice (outlet, product, product_id, our_price, date)
		VALUES ('" .$client. "', '" .$product. "', '" .$pid. "', '" .$oprice. "', '" .$date. "')";
		
		// use exec() because no results are returned
		$conn->exec($sql1);
		echo "Table 2 New record created successfully <br/>";

	}catch(PDOException $e){
		
		echo $sql1 . "<br>" . $e->getMessage();
	}*/

$conn = null;