<?php

require_once("login.php");

require_once("Models/UserDataSet.php");
//Will run this script if login is correct otherwise loginError.php loads, Avoids both pages loading on same screen
if(!isset($view->loginError)) {
    $view = new stdClass();
    $view->pageTitle = 'User Search';

    if (isset($_POST['submit'])) {
        //Sets variables from fields in form
        $searchTerm = $_POST['search'];
        $order = $_POST['order'];
        if (isset($_SESSION["login"])) {
            $field = $_POST['options']; //allows user to select this field if they are logged in
        } else {
            $field = 'first_name'; // sets this field for users who aren't logged in
        }
        $userDataSet = new UserDataSet();

        $view->userDataSet = $userDataSet->searchUsers($searchTerm, $field, $order); //search all users

    }
        require_once('Views/search.phtml');

}