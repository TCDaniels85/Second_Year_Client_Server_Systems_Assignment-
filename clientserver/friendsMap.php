<?php
require_once("login.php");
require_once('Models/friendDataSet.php');
require_once('Models/userDataSet.php');

//php helper script to send query to the database and echo json encoded data to the Request class

$token="";

//assigns token
if(isset($_SESSION["queryToken"])){
    $token = $_SESSION["queryToken"];
}

if (isset($_GET["token"]) && $_GET["type"] == "userDetails") { //checks token is set checks type to ensure correct db query is executed
    if (!isset($_GET["token"]) || $_GET["token"] != $token) { //inner if statement checks the token is valid
        //Does nothing
    } else {
        $userDataSet = new userDataSet();
        $result = $userDataSet->returnUser($_GET["q"]); //returns user details

        echo json_encode($result);
    }
} else if (isset($_GET["token"]) && $_GET["type"] == "loggedIn"){

    if (!isset($_GET["token"]) || $_GET["token"] != $token) {
        //Does nothing
    } else {
        $friendDataSet = new friendDataSet();
        $userID = $_SESSION["userID"]; //sets userID variable for use in methods
        $results = $friendDataSet->fetchAllAccepted($_GET["q"]); //returns array of user friends to view variable

        echo json_encode($results);
    }
} else if (isset($_GET["token"]) && $_GET["type"] == "locationUpdate"){
    if (!isset($_GET["token"]) || $_GET["token"] != $token) {
        //Does nothing
    } else {
        $userDataSet = new userDataSet();
        $result = $userDataSet->updateLocation($_GET["s"],$_GET["q"], $_GET["r"]); //updates location in database


    }
}


