<?php

require 'vendor/autoload.php';

$app = new \Slim\App;

//slim application routes

$app->get('/api/sales', 'getSales');

$app->get('/api/login', 'login');

$app->get('/api/post_sale', 'postTotalSale');
$app->get('/api/post_stock', 'postStock');
$app->post('/api/post_pic', 'postAgentPic');





$app->run();

// Get Database Connection
function DB_Connection() {
    $dbhost = "127.0.0.1";
    $dbuser = "root";
    $dbpass = "root123**";
//    $dbuser = "root";
//    $dbpass = "root";
    $dbname = "gamechanger";
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
}

function postStock($request){
    $sql = "insert into tbl_app_stock (`agent_id`,`type`,`rubia`,`rubia5l`,`quatz`,`hiperf`,`region`,`van_number`,`date_created`) values(:agent_id,:type,:rubia,:rubia5l,:quatz,:hiperf,:region,:van_number,:date_created)";
    
         
     try{

        $db = DB_Connection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("agent_id", $request->getParam('agent_id'));
        $stmt->bindParam("type", $request->getParam('type'));
        $stmt->bindParam("rubia", $request->getParam('rubia'));
         $stmt->bindParam("rubia5l", $request->getParam('rubia5l'));
        $stmt->bindParam("quatz", $request->getParam('quatz'));
        $stmt->bindParam("hiperf", $request->getParam('hiperf'));
        $stmt->bindParam("region", $request->getParam('region'));
        $stmt->bindParam("van_number", $request->getParam('van_number'));
        $stmt->bindParam("date_created", date('Y-m-d H:i:s'));

        $stmt->execute();

        echo '{"success":"true"}';
    } catch (PDOException $e) {
        echo '{"success":"false","error":"'. $e->getMessage().'"}';

    }
}

function postAgentPic($request){
    $sql = "insert into tbl_app_agentpic (`agent_id`,`van_number`,`region`,`img_path`,`date_created`) values (:agent_id,:van_number,:region,:img_path,:date_created)";
    
    $img_path = "";
    
    if ($_FILES['pic']['name']) {
            $rand = rand();
            $img_path = $rand . $_FILES['pic']['name'];
             move_uploaded_file($_FILES["pic"]["tmp_name"], "public/".$img_path);
    }
    
  
    
     try {
       $db = DB_Connection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("agent_id",  $request->getParam('agent_id'));
        $stmt->bindParam("van_number", $request->getParam('van_number'));
        $stmt->bindParam("region",  $request->getParam('region'));
        $stmt->bindParam("img_path", $img_path);
        
        $stmt->bindParam("date_created", date('Y-m-d H:i:s'));
        $stmt->execute();
        
         echo '{"success":"true"}';
        
    } catch (PDOException $e) {
        echo '{"success":"false","error":"' . $e->getMessage() . '""}';
    }
}

function postTotalSale($request){
     $sql = "insert into tbl_total_sales (`customer_name`,`customer_type`,`phone`,`isCustomer`,`pricing`,`quality`,`recommend`,`retail`,`isCustomerReason`,`merchandise`,`agent_id`,`lat`,`lng`,`region`,`van_number`,`check_in`,`check_out`,`date_created`) values (:customer_name,:customer_type,:phone,:isCustomer,:pricing,:quality,:recommend,:retail,:isCustomerReason,:merchandise,:agent_id,:lat,:lng,:region,:van_number,:check_in,:check_out,:date_created)";
     
     try{

        $db = DB_Connection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("customer_name", $request->getParam('customer_name'));
        $stmt->bindParam("phone", $request->getParam('phone'));
        $stmt->bindParam("customer_type", $request->getParam('customer_type'));
        $stmt->bindParam("isCustomer", $request->getParam('isCustomer'));
        $stmt->bindParam("isCustomerReason", $request->getParam('isCustomerReason'));
        $stmt->bindParam("merchandise", $request->getParam('merchandise'));
        $stmt->bindParam("pricing", $request->getParam('pricing'));
        $stmt->bindParam("quality", $request->getParam('quality'));
        $stmt->bindParam("recommend", $request->getParam('recommend'));
        $stmt->bindParam("retail", $request->getParam('retail'));
      
        $stmt->bindParam("van_number", $request->getParam('van_number'));
        $stmt->bindParam("agent_id", $request->getParam('agent_id'));
        $stmt->bindParam("lat", $request->getParam('lat'));
        $stmt->bindParam("lng", $request->getParam('lng'));
        $stmt->bindParam("check_in", $request->getParam('check_in'));
        $stmt->bindParam("check_out", $request->getParam('check_out'));
        $stmt->bindParam("region", $request->getParam('region'));
        $stmt->bindParam("date_created", date('Y-m-d H:i:s'));

        $stmt->execute();
        
        $id = $db->lastInsertId();
        
        $rubia = $request->getParam('rubia');
        $quatz = $request->getParam('quatz');
        $hiperf = $request->getParam('hiperf');
        
        
        //check if has order
        if(isset($rubia) || $quatz|| $hiperf){
            //post order
            
        $sql = "insert into tbl_app_orders (`sale_id`,`rubia`,`rubia5l`,`quatz`,`hiperf`,`date_created`) values (:sale_id,:rubia,:rubia5l,:quatz,:hiperf,:date_created)";
        $stmt = $db->prepare($sql);  
        $stmt->bindParam("sale_id",$id);
        $stmt->bindParam("rubia", $request->getParam('rubia'));
        $stmt->bindParam("rubia5l", $request->getParam('rubia5l'));
        $stmt->bindParam("quatz", $request->getParam('quatz'));
        $stmt->bindParam("hiperf", $request->getParam('hiperf'));
        $stmt->bindParam("date_created", date('Y-m-d H:i:s'));
        $stmt->execute();   
        
        }
        

        echo '{"success":"true"}';
    } catch (PDOException $e) {
        echo '{"success":"false","error":"'. $e->getMessage().'"}';

    }
    

    
 
    
}


function postGoTvSale($request){
    $sql = "insert into tbl_gotv_sales (`full_name`,`phone`,`estate`,`houseno`,`hasGoTv`,`followUpQuestion`,`followUpAnswer`,`paymentOption`,`agent_id`,`lat`,`lng`,`reference`,`date_created`) values (:full_name,:phone,:estate,:houseno,:hasGoTv,:followUpQuestion,:followUpAnswer,:paymentOption,:agent_id,:lat,:lng,:reference,:date_created)";

    try{

        $db = DB_Connection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("full_name", $request->getParam('full_name'));
        $stmt->bindParam("phone", $request->getParam('phone'));
        $stmt->bindParam("estate", $request->getParam('estate'));
        $stmt->bindParam("houseno", $request->getParam('houseno'));
        $stmt->bindParam("hasGoTv", $request->getParam('hasGoTv'));
        $stmt->bindParam("followUpQuestion", $request->getParam('followUpQuestion'));
        $stmt->bindParam("followUpAnswer", $request->getParam('followUpAnswer'));
        $stmt->bindParam("paymentOption", $request->getParam('paymentOption'));
        $stmt->bindParam("agent_id", $request->getParam('agent_id'));
        $stmt->bindParam("lat", $request->getParam('lat'));
        $stmt->bindParam("lng", $request->getParam('lng'));
        $stmt->bindParam("reference", $request->getParam('reference'));
        $stmt->bindParam("date_created", date('Y-m-d H:i:s'));

        $stmt->execute();

        echo '{"success":"true"}';
    } catch (PDOException $e) {
        echo '{"success":"false","error":"'. $e->getMessage().'"}';

    }
}



function getSales($request){
    $agent_id=$request->getParam('agent_id');

    $sql = "select * from tbl_gotv_sales where agent_id=".$agent_id;

    try {
        $db = DB_Connection();
        $stmt = $db->query($sql);
        $list = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode(array('sales'=>$list));
    } catch (PDOException $e) {
        echo '{"success":"false","error":"' . $e->getMessage() . '""}';
    }
}



// GET One Bus Details
function login($request, $response, $args) {
    
    //Getting values 
    $username = $request->getParam('email');
    $password = $request->getParam('password');
	
   //Creating sql query
    $sql = "SELECT id,team_leader FROM tbl_app_users WHERE username='$username' AND password='$password'";


    try {
	$db = DB_Connection();

	if ($res = $db->query($sql)) {
	
		/* Check the number of rows that match the SELECT statement */
		if ($res->rowCount() === 1) {
		
		    $userObject = $res->fetch(PDO::FETCH_OBJ);
		
		    return '{"success":1,"id":' . $userObject->id . ',"agent":"'.$username.'","team_leader":'.$userObject->team_leader .' }';
		} else {
		    return '{"success":0}';
		}
	} else {
		return '{"success":0}';
	}
    
    } catch (PDOException $e) {
        return '{"success":0}';
    }
}
    
function postCallReport($request, $response, $args) {
	    
    $books = json_decode($request->getBody());
    
    try{
	$sql = "INSERT INTO tbl_app_agentlocation 
	(`agent`,`date`,`time_in`, `client_name`, `purpose_of_visit`, `time_out`, `contact_person`, `phone_number`,`comment`,`longitude`,`latitude`, `address`) 
	VALUES 
	(:agent,:date, :time_in, :client_name, :purpose_of_visit, :time_out, :contact_person, :phone_number, :comment, :longitude, :latitude, :address)";
	
	$db = DB_Connection();
	foreach ($books as $book){
		$address = "";
		if($book->latitude == "0.0" || $book->longitude == "0.0"){ //Need to get the exact matching strings for the lat && long as sent by phone when it can't acquire coordinates
			$address = "No location posted by phone";
		}else{
			
			//call google APIs here to get the address
			$url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=" .$book->latitude. ","  .$book->longitude. "&key=AIzaSyCtsUzCViKyJJYRgInZ17WHe2rbdqtVvk0";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			// Set so curl_exec returns the result instead of outputting it.
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			// accept any CA
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

			$json_data = curl_exec($ch); 

			$json_data = json_decode($json_data, true);

            if(sizeof($json_data['results'])>=2)
                $address = $json_data['results'][2]['formatted_address'];
            else
			$address = $json_data['results'][1]['formatted_address'];
			
			curl_close($ch);
		}


		//date_default_timezone_set("Africa/Nairobi");
		//$date = date("Y-m-d h:i:sa"); //2016-06-08 09:45:11am
		//$file = fopen("callreport_log_file.txt", "a+");
		//fwrite($file, '[LOG TIME: ' .$date. '] [ADDRESS: ' .$address. '] [AGENT: ' .$book->user. '] [TIME IN: ' .$book->time_in. "\r");
		//fclose($file);


/* $params = "Date: " .$book->date. " TIME IN: " .$book->time_in. " CLIENT: " .$book->client. " PURPOSE: " .$book->purpose. " TIME OUT: " .$book->time_out. " CONTACT: " .$book->contact. " PHONE: " .$book->number. " COMMENT: " .$book->comment. " LONG: " .$book->longitude. " LAT: " .$book->latitude. "\r"; */


		$stmt = $db->prepare($sql);
		$stmt->bindParam("agent", $book->user);
		$stmt->bindParam("date", $book->date);
		$stmt->bindParam("time_in", $book->time_in);
		$stmt->bindParam("client_name", $book->client);
		$stmt->bindParam("purpose_of_visit", $book->purpose);
		$stmt->bindParam("time_out", $book->time_out);
		$stmt->bindParam("contact_person", $book->contact);
		$stmt->bindParam("phone_number", $book->number);
		$stmt->bindParam("comment", $book->comment);
		$stmt->bindParam("longitude", $book->longitude);
		$stmt->bindParam("latitude", $book->latitude);
		$stmt->bindParam("address", $address);
		$stmt->execute();	

	}
	    
	echo '{"success":1}';

    } catch (PDOException $e) {
        echo '{"success":}'.$e->getMessage();
        
    }

}

function postDailyMarketReport($request){
    $sql = "Insert into tbl_daily_market_reports (`customer_name`,`address`,`contact_person`,`contact_number`,`type_agrovet`,`type_broiler`,`type_dealer`,`type_layers`,`avg_broilers`,`avg_layers`,`feeds_source`,`chicks_source`,`remarks`,`sales_person`,`date_created`) Values (:customer_name,:address,:contact_person,:contact_number,:type_agrovet,:type_broiler,:type_dealer,:type_layers,:avg_broilers,:avg_layers,:feeds_source,:chicks_source,:remarks,:sales_person,:date_created)";


    try{

    	$db = DB_Connection();
    	$stmt = $db->prepare($sql);
    	$stmt->bindParam("customer_name", $request->getParam('customer_name'));
    		$stmt->bindParam("address", $request->getParam('address'));
    	$stmt->bindParam("contact_number", $request->getParam('contact_number'));
    	$stmt->bindParam("type_agrovet", $request->getParam('type_agrovet'));
    	$stmt->bindParam("type_broiler", $request->getParam('type_broiler'));
    	$stmt->bindParam("type_dealer", $request->getParam('type_dealer'));
    	$stmt->bindParam("type_layers", $request->getParam('type_layers'));
    $stmt->bindParam("contact_person", $request->getParam('contact_person'));
    	$stmt->bindParam("avg_layers", $request->getParam('avg_layers'));
    	$stmt->bindParam("avg_broilers", $request->getParam('avg_broilers'));
    	$stmt->bindParam("feeds_source", $request->getParam('feed_source'));
    	$stmt->bindParam("chicks_source", $request->getParam('chicks_source'));
    	$stmt->bindParam("remarks", $request->getParam('remarks'));
    	$stmt->bindParam("sales_person", $request->getParam('sales_person'));
    	$stmt->bindParam("date_created", date('Y-m-d H:i:s'));

		$stmt->execute();

	echo '{"success":"true"}';
    } catch (PDOException $e) {
        echo '{"success":"false"}';
        //.$e->getMessage();

    }

}

function postPerformanceFeedReport($request){
    $sql = "insert into tbl_feed_performace_report (`famer_names`,`address`,`region`,`dealer_name`,`total_doc`,`feed_company`,`doc_company`,`mortality`,`age`,`wtGain`,`feedCon`,`weightedStd`,`feedConStd`,`sales_person`,`date_created`) values (:farmer_names,:address,:region,:dealer_name,:total_doc,:feed_company,:doc_company,:mortality,:age,:wtGain,:feedCon,:weightedStd,:feedConStd,:sales_person,:date_created)";

      try{

    	$db = DB_Connection();
    	$stmt = $db->prepare($sql);
    	$stmt->bindParam("farmer_names", $request->getParam('farmer_names'));
    		$stmt->bindParam("address", $request->getParam('address'));
    	$stmt->bindParam("region", $request->getParam('region'));
    	$stmt->bindParam("dealer_name", $request->getParam('dealer_name'));
    	$stmt->bindParam("total_doc", $request->getParam('total_doc'));
    	$stmt->bindParam("feed_company", $request->getParam('feed_company'));
    	$stmt->bindParam("doc_company", $request->getParam('doc_company'));
    	$stmt->bindParam("mortality", $request->getParam('mortality'));
    	$stmt->bindParam("age", $request->getParam('age'));
		$stmt->bindParam("wtGain", $request->getParam('wtGain'));
    	$stmt->bindParam("feedCon", $request->getParam('feedCon'));
    	$stmt->bindParam("weightedStd", $request->getParam('weightedStd'));
    	$stmt->bindParam("feedConStd", $request->getParam('feedConStd'));
    	$stmt->bindParam("sales_person", $request->getParam('sales_person'));
    	$stmt->bindParam("date_created", date('Y-m-d H:i:s'));

		$stmt->execute();

	echo '{"success":"true"}';
    } catch (PDOException $e) {
        echo '{"success":"false"}';

    }
}
