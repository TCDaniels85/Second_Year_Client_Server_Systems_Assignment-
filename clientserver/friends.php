<?php
require_once("login.php");
require_once("Models/friendDataSet.php");
//Will run this script if login is correct otherwise loginError.php loads, Avoids both pages loading on same screen
if(!isset($view->loginError)) {
    $view = new stdClass();
    $view->pageTitle = 'Friendships';
    //checks if user is logged in
    if (isset($_SESSION["userID"])) {
        $friendDataSet = new friendDataSet();
        $userID = $_SESSION["userID"]; //sets userID variable for use in methods
        $view->friendDataSet = $friendDataSet->fetchAllAccepted($userID); //returns array of user friends to view variable
        //runs if 'unfriend' button pressed
        if (isset($_POST['unfriend'])) {
            $friendID = $_POST['unfriend']; //Sets variable from field in form
            $friendDataSet->removeFriendStatus($userID, $friendID); //Removes selected user from friends list
            //Message displaying that request is cancelled
            require('Views/template/header.phtml');
            echo '<h3>You are no longer friends</h3>';
            echo '<p><a class="nav-link" href="friends.php">Return to friends</a></p>';
            require('Views/template/footer.phtml');
        } else {
            require_once('Views/friends.phtml'); //displays if cancel not clicked
        }
    } else {

    require_once('Views/friends.phtml');  // displays if user not logged in
    }
}

