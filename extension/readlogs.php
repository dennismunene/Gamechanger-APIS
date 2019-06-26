<?php

/*
Our "config.inc.php" file connects to database every time we include or require
it within a php script.  Since we want this script to add a new user to our db,
we will be talking with our database, and therefore,
let's require the connection to happen:
*/
require("config.inc.php");

$agentid=$_GET['email'];
//initial query
$query = "Select * FROM tbl_app_agentLocation where agent = '".$agentid."'";

//execute query
try {
    $stmt   = $db->prepare($query);
    $result = $stmt->execute($query_params);
}
catch (PDOException $ex) {
    $response["success"] = 0;
    $response["message"] = "Database Error!";
    die(json_encode($response));
}

// Finally, we can retrieve all of the found rows into an array using fetchAll 
$rows = $stmt->fetchAll();


if ($rows) {
    $response["success"] = 1;
    $response["message"] = "Post Available!";
    $response["posts"]   = array();
    


 foreach ($rows as $row) {
        $post             = array();
	
        //this line is new:
        $post["id"]  = $row["id"];
        $post["agent"] = $row["agent"];
        $post["date"]    = $row["date"];
        $post["time_in"]  = $row["time_in"];
        $post["client_name"]  = $row["client_name"];
        $post["purpose_of_visit"]  = $row["purpose_of_visit"];
        $post["time_out"]  = $row["time_out"];
        $post["comment"]  = $row["comment"];
        
        
        //update our repsonse JSON data
        array_push($response["posts"], $post);
    }
 
    // echoing JSON response
    echo json_encode($response);
    
    
} else {
    $response["success"] = 0;
    $response["message"] = "No Post Available!";
    die(json_encode($response));
}

?>