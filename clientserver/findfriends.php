<?php
require_once("login.php");
require_once("Models/friendDataSet.php");
//Will run this script if login is correct otherwise loginError.php loads, Avoids both pages loading on same screen
if(!isset($view->loginError)) {
    $view = new stdClass();
    $view->pageTitle = 'Find more friends';

    if (isset($_POST['submit'])) {
        //Sets variables from fields in form
        $searchTerm = $_POST['search'];
        $order = $_POST['order'];
        $userID = $_SESSION["userID"];


        if (isset($_SESSION["login"])) {
            $field = $_POST['options']; //sets field variable if user logged in
            $friendDataSet = new friendDataSet();
            //Search users who are either not friends or have no requests pending
            $view->friendDataSet = $friendDataSet->findFriends($userID, $searchTerm, $field, $order);
        }
    }
    $friendDataSet = new friendDataSet();
    if (isset($_POST['request'])) {
        //Sets variables from fields in form
        $friendID = $_POST['request'];
        $userID = $_SESSION['userID']; // userid from session info

        $friendDataSet->sendRequest($userID, $friendID);  //send friend request
        require('Views/template/header.phtml');
        echo '<h3>Friend request sent</h3>';
        echo '<p><a class="nav-link" href="findfriends.php">Return to find friends</a></p>';
        require('Views/template/footer.phtml');

    } else {
    require_once('Views/findfriends.phtml'); }
}