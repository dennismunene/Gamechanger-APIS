<?php

require 'vendor/autoload.php';

$app = new \Slim\App;

//slim application routes

$app->get('/api/get_products', 'get_products');
$app->get('/api/post_product_stock', 'post_product_stock');
$app->post('/api/post_schweppes_sale', 'post_schweppes_sale');


$app->get('/api/login', 'login');
$app->get('/api/post_sale', 'postTotalSale');
$app->get('/api/post_stock', 'postStock');
$app->get('/api/post_schweppes_stock', 'post_schweppes_stock');
$app->post('/api/post_rothman_sale', 'post_rothman_sale');
$app->post('/api/post_pic', 'postAgentPic');





$app->run();

// Get Database Connection
function DB_Connection() {
    $dbhost = "127.0.0.1";
    $dbuser = "root";
    $dbpass = "root123**";
//    $dbuser = "root";
   // $dbpass = "root";
    $dbname = "gamechanger";
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
}


function get_products($request){

    $prod_id = $request->getParam('product_id');


    if(isset($prod_id))
        $sql = "SELECT * FROM tbl_products where id=".$prod_id;
    else
        $sql = "SELECT * FROM tbl_products";


    try {
        $db = DB_Connection();

        if ($res = $db->query($sql)) {

            $products = array();
            while($row = $res->fetch(PDO::FETCH_ASSOC)){

                //get purchase sources
                $sql = "select id,purchase_source from tbl_purchase_source where product_id=".$row['id'];
                $purchase_sources = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);


                //get traffic sources
                $sql = "select id,traffic_source from tbl_traffic_source where product_id=".$row['id'];
                $traffic_sources = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);


                //get usages
                $sql = "select id,product_usage from tbl_product_usage where product_id=".$row['id'];
                $product_usages = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);


                //non usage reasons
                $sql = "select id,reason from tbl_product_nonusage where product_id=".$row['id'];
                $product_nonusages = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

                //no purchase reasons
                $sql = "select id,reason from tbl_product_nopurchase where product_id=".$row['id'];
                $product_nopurchase = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

                //get sku details
                $sql = "select  tbl_sku_details.id,tbl_sku_details.sku_detail,tbl_product_skus.sku from tbl_product_skus inner join tbl_sku_details on tbl_product_skus.id = tbl_sku_details.sku_id where tbl_product_skus.product_id=".$row['id'];
                $skus = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

                //get product pruchase influence
                $sql = "select  id,influencer from tbl_purchase_influence where product_id=".$row['id'];
                $purchase_influences = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);


                $row['purchase_sources'] = $purchase_sources;
                $row['traffic_sources'] = $traffic_sources;
                $row['product_usages'] = $product_usages;
                $row['product_nonusages'] = $product_nonusages;
                $row['product_nopurchase'] = $product_nopurchase;
                $row['sku_details'] = $skus;
                $row['purchase_influences'] = $purchase_influences;


                $products[] = $row;

            }

            echo json_encode(array('product_details'=>$products));

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
function post_product_stock($request){
    $sql = "insert into tbl_stock (`agent_id`,`type`,`product_id`,`sku_detail`,`quantity`,`region`,`van_number`,`date_created`) values(:agent_id,:type,:product_id,:sku_detail,:quantity,:region,:van_number,:date_created)";


    $skus =  json_decode($request->getParam('skus'), true);




     try{

        $db = DB_Connection();
        $stmt = $db->prepare($sql);

         foreach($skus as $key=>$value) {


             $stmt->bindParam("agent_id", $request->getParam('agent_id'));
             $stmt->bindParam("type", $request->getParam('type'));
             $stmt->bindParam("product_id", $request->getParam('product_id'));
             $stmt->bindParam("sku_detail", $value['sku_detail_id']);
             $stmt->bindParam("quantity", $value['quantity']);
             $stmt->bindParam("region", $request->getParam('region'));
             $stmt->bindParam("van_number", $request->getParam('van_number'));
             $stmt->bindParam("date_created", date('Y-m-d H:i:s'));

             $stmt->execute();

         }

        echo '{"success":"true"}';
    } catch (PDOException $e) {
        echo '{"success":"false","error":"'. $e->getMessage().'"}';
    }
}

function postAgentPic($request){
    $sql = "insert into tbl_app_agentpic (`agent_id`,`product_id`,``van_number`,`region`,`img_path`,`date_created`) values (:agent_id,:van_number,:region,:img_path,:date_created)";
    
    $img_path = "";
    
    if ($_FILES['pic']['name']) {
            $rand = rand();
            $img_path = $rand . $_FILES['pic']['name'];
             move_uploaded_file($_FILES["pic"]["tmp_name"], "public/".$img_path);
    }
    
  
    
     try {
        $prod_id =  $request->getParam('product_id');

        if(!isset($prod_id))
            $prod_id =1;


       $db = DB_Connection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("agent_id",  $request->getParam('agent_id'));
        $stmt->bindParam("product_id",$prod_id );
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


function post_schweppes_sale($request){
    $sql = "insert into tbl_sales (`customer_name`,`is_aware`,`traffic_source`,`is_customer`,`purchase_source`,`stock_out_count`,`other_brand_count`,`product_description`,`product_usage`,`product_nouse`,`product_no_purchase`,`would_recommend`,`purchase_influence`,`sales_photo`,`engagement_photo`)
values (:customer_name,:is_aware,:traffic_source,:is_customer,:purchase_source,:stock_out_count,:other_brand_count,:product_description,:product_usage,:product_nouse,:product_no_purchase,:would_recommend,:purchase_influence,:sales_photo,:engagement_photo)";

    $img_path = "";

    if ($_FILES['pic']['name']) {
            $rand = rand();
            $img_path = $rand . $_FILES['pic']['name'];
             move_uploaded_file($_FILES["pic"]["tmp_name"], "public/".$img_path);
    }


    $img_path_engage = "";

    if ($_FILES['pic_engage']['name']) {
        $rand = rand();
        $img_path_engage = $rand . $_FILES['pic_engage']['name'];
        move_uploaded_file($_FILES["pic_engage"]["tmp_name"], "public/".$img_path_engage);
    }


     try {

       $db = DB_Connection();

       //record sale
        $stmt = $db->prepare($sql);
        $stmt->bindParam("customer_name",  $request->getParam('customer_name'));
        $stmt->bindParam("is_aware",$request->getParam('is_aware') );
        $stmt->bindParam("traffic_source", $request->getParam('traffic_source'));
        $stmt->bindParam("is_customer",  $request->getParam('is_customer'));
        $stmt->bindParam("purchase_source",  $request->getParam('purchase_source'));
        $stmt->bindParam("stock_out_count",  $request->getParam('stock_out_count'));
        $stmt->bindParam("other_brand_count",  $request->getParam('other_brand_count'));
        $stmt->bindParam("product_description",  $request->getParam('product_description'));
        $stmt->bindParam("product_usage",  $request->getParam('product_usage'));
        $stmt->bindParam("product_nouse",  $request->getParam('product_nouse'));
        $stmt->bindParam("product_no_purchase",  $request->getParam('product_no_purchase'));
        $stmt->bindParam("would_recommend",  $request->getParam('would_recommend'));
        $stmt->bindParam("purchase_influence",  $request->getParam('purchase_influence'));
        $stmt->bindParam("sales_photo", $img_path);
        $stmt->bindParam("engagement_photo", $img_path_engage);

        $stmt->execute();

        $sale_id = $db->lastInsertId();



        //record sku order details
         $skus =  json_decode($request->getParam('skus'), true);
         $sql = "insert into tbl_orders (`sale_id`,`sku_detail`,`quantity`) values (:sale_id,:sku_detail,:quantity)";

         foreach($skus as $key=>$value) {
             $sku_detail_id = $value['sku_detail_id'];
             $quantity = $value['quantity'];

             $stmt = $db->prepare($sql);
             $stmt->bindParam("sale_id", $sale_id);
             $stmt->bindParam("sku_detail", $sku_detail_id);
             $stmt->bindParam("quantity", $quantity);
             $stmt->execute();

         }


         //save sale detials
         $prod_id = $request->getParam('product_id');
         $sql ="insert into tbl_sale_details (`product_id`,`sale_id`,`agent_id`,`lat`,`lng`,`region`,`van_number`,`check_in`,`check_out`,`date_created`) values (:product_id,:sale_id,:agent_id,:lat,:lng,:region,:van_number,:check_in,:check_out,:date_created)";
         $stmt = $db->prepare($sql);
         $stmt->bindParam("product_id", $prod_id);
         $stmt->bindParam("sale_id", $sale_id);
         $stmt->bindParam("van_number", $request->getParam('van_number'));
         $stmt->bindParam("agent_id", $request->getParam('agent_id'));
         $stmt->bindParam("lat", $request->getParam('lat'));
         $stmt->bindParam("lng", $request->getParam('lng'));
         $stmt->bindParam("check_in", $request->getParam('check_in'));
         $stmt->bindParam("check_out", $request->getParam('check_out'));
         $stmt->bindParam("region", $request->getParam('region'));
         $stmt->bindParam("date_created", date('Y-m-d H:i:s'));
         $stmt->execute();

         //update tbl_sales

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



function post_rothman_sale($request){
    $sql = "insert into tbl_rothman_sale (`customer_feedback`,`customer_brand`,`is_aware`,`traffic_source`,`is_customer`,`purchase_source`,`product_description`,`customer_reaction`,`tried_variant`,`area`,`region`,`sales_photo`,`engagement_photo`)
values (:customer_feedback,:customer_brand,:is_aware,:traffic_source,:is_customer,:purchase_source,:product_description,:customer_reaction,:tried_variant,:area,:region,:sales_photo,:engagement_photo)";

    $img_path = "";

    if ($_FILES['pic']['name']) {
        $rand = rand();
        $img_path = $rand . $_FILES['pic']['name'];
        move_uploaded_file($_FILES["pic"]["tmp_name"], "public/".$img_path);
    }


    $img_path_engage = "";

    if ($_FILES['pic_engage']['name']) {
        $rand = rand();
        $img_path_engage = $rand . $_FILES['pic_engage']['name'];
        move_uploaded_file($_FILES["pic_engage"]["tmp_name"], "public/".$img_path_engage);
    }


    try {

        $db = DB_Connection();

        //record sale
        $stmt = $db->prepare($sql);
        $stmt->bindParam("customer_brand",  $request->getParam('customer_brand'));
        $stmt->bindParam("is_aware",$request->getParam('is_aware') );
        $stmt->bindParam("traffic_source", $request->getParam('traffic_source'));
        $stmt->bindParam("is_customer",  $request->getParam('is_customer'));
        $stmt->bindParam("purchase_source",  $request->getParam('purchase_source'));
        $stmt->bindParam("customer_reaction",  $request->getParam('reaction'));
        $stmt->bindParam("area",  $request->getParam('area'));
        $stmt->bindParam("product_description",  $request->getParam('product_description'));
        $stmt->bindParam("region",  $request->getParam('region'));
        $stmt->bindParam("tried_variant",  $request->getParam('variant'));
        $stmt->bindParam("sales_photo", $img_path);
        $stmt->bindParam("engagement_photo", $img_path_engage);
        $stmt->bindParam("customer_feedback", $request->getParam('customer_feedback'));

        $stmt->execute();

        $sale_id = $db->lastInsertId();



        //record sku order details
        $skus =  json_decode($request->getParam('skus'), true);
        $sql = "insert into tbl_orders (`sale_id`,`sku_detail`,`quantity`) values (:sale_id,:sku_detail,:quantity)";

        foreach($skus as $key=>$value) {
            $sku_detail_id = $value['sku_detail_id'];
            $quantity = $value['quantity'];

            $stmt = $db->prepare($sql);
            $stmt->bindParam("sale_id", $sale_id);
            $stmt->bindParam("sku_detail", $sku_detail_id);
            $stmt->bindParam("quantity", $quantity);
            $stmt->execute();

        }


        //save sale detials
        $prod_id = $request->getParam('product_id');
        $sql ="insert into tbl_sale_details (`product_id`,`sale_id`,`agent_id`,`lat`,`lng`,`region`,`van_number`,`check_in`,`check_out`,`date_created`) values (:product_id,:sale_id,:agent_id,:lat,:lng,:region,:van_number,:check_in,:check_out,:date_created)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("product_id", $prod_id);
        $stmt->bindParam("sale_id", $sale_id);
        $stmt->bindParam("van_number", $request->getParam('van_number'));
        $stmt->bindParam("agent_id", $request->getParam('agent_id'));
        $stmt->bindParam("lat", $request->getParam('lat'));
        $stmt->bindParam("lng", $request->getParam('lng'));
        $stmt->bindParam("check_in", $request->getParam('check_in'));
        $stmt->bindParam("check_out", $request->getParam('check_out'));
        $stmt->bindParam("region", $request->getParam('region'));
        $stmt->bindParam("date_created", date('Y-m-d H:i:s'));
        $stmt->execute();

        //update tbl_sales

        echo '{"success":"true"}';

    } catch (PDOException $e) {
        echo '{"success":"false","error":"' . $e->getMessage() . '""}';
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



    

