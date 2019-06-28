<?php

require 'vendor/autoload.php';

$app = new \Slim\App;

//slim application routes

$app->get('/api/get_products', 'get_products');


$app->get('/api/login', 'login');
$app->get('/api/post_sale', 'postTotalSale');
$app->get('/api/post_stock', 'postStock');
$app->get('/api/post_schweppes_stock', 'post_schweppes_stock');
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


function get_products(){

    //Creating sql query
    $sql = "SELECT * from tbl_products";


    try {
        $db = DB_Connection();

        if ($res = $db->query($sql)) {

            echo json_encode($res->fetch(PDO::FETCH_ASSOC));

        } else {
            return '{"success":0}';
        }

    } catch (PDOException $e) {
        return '{"success":0}';
    }
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
function post_schweppes_stock($request){
    $sql = "insert into tbl_schweppes_stock (`agent_id`,`type`,`schweppes300ml`,`schweppes500ml`,`region`,`van_number`,`date_created`) values(:agent_id,:type,:schweppes300ml,:schweppes500ml,:region,:van_number,:date_created)";

     try{

        $db = DB_Connection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("agent_id", $request->getParam('agent_id'));
        $stmt->bindParam("type", $request->getParam('type'));
        $stmt->bindParam("schweppes300ml", $request->getParam('schweppes300ml'));
        $stmt->bindParam("schweppes500ml", $request->getParam('schweppes500ml'));
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



    

