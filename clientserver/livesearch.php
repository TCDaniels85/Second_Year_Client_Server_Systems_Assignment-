<?php
require_once('Models/UserDataSet.php');
//php helper script to send query to the database and echo json encoded data to the Request class

session_start();
$token="";
$type = "";

//tests if token matches the token set in session via header to some level of security
if(isset($_SESSION["queryToken"])){
    $token = $_SESSION["queryToken"];
}
if(isset($_GET["type"])){
    $type = $_GET["type"];
}

if(!isset($_GET["token"]) || $_GET["token"] != $token){
    //Does nothing
} else {

    if($type == "loggedin") {  //conducts query on database for logged-in users, these have more filters
        $userDataSet = new UserDataSet();
        $userDataSet = $userDataSet->searchUsers($_GET["q"], $_GET["b"], $_GET["a"]);

        echo json_encode($userDataSet);
    } else { //non logged-in query
        $userDataSet = new UserDataSet();
        $userDataSet = $userDataSet->searchUsers($_GET["q"], 'first_name', ($_GET["a"]));

        echo json_encode($userDataSet);
    }

}

