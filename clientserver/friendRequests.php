<?php
require_once("login.php");
require_once("Models/friendDataSet.php");
//Will run this script if login is correct otherwise loginError.php loads, Avoids both pages loading on same screen
if(!isset($view->loginError)) {
    $view = new stdClass();
    $view->pageTitle = 'Friend Requests';


    if (isset($_SESSION["userID"])) {
        $userID = $_SESSION["userID"];  //sets userID variable from session info to be sent to methods below
        $friendDataSet = new friendDataSet();
        $view->friendDataSet = $friendDataSet->fetchAllRequests($userID);  //returns all users sent or received friend requests
        if (isset($_POST['accept'])) {
            $friendID = $_POST['accept'];//Sets variable from field in form
            $friendDataSet->acceptRequest($userID, $friendID);  //accept friend method
            require('Views/template/header.phtml');
            echo '<h3>You are now friends</h3>';
            echo '<p><a class="nav-link" href="friendRequests.php">Return to friend requests</a></p>';
            require('Views/template/footer.phtml');
        } else if (isset($_POST['reject'])) {
            $friendID = $_POST['reject'];//Set variables from field in form
            $friendDataSet->removeFriendStatus($userID, $friendID); // method to reject friend request
            require('Views/template/header.phtml');
            echo '<h3>Friend request rejected</h3>';
            echo '<p><a class="nav-link" href="friendRequests.php">Return to friend requests</a></p>';
            require('Views/template/footer.phtml');
        } else if (isset($_POST['cancel'])) {
            $friendID = $_POST['cancel'];//Sets variable from field in form
            $friendDataSet->removeFriendStatus($userID, $friendID); // method to cancel friend request
            require('Views/template/header.phtml');
            echo '<h3>Friend request cancelled</h3>';
            echo '<p><a class="nav-link" href="friendRequests.php">Return to friend requests</a></p>';
            require('Views/template/footer.phtml');

        } else { require_once('Views/friendRequests.phtml'); }
    } else {
    require_once('Views/friendRequests.phtml');}
}