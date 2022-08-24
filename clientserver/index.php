<?php
require_once("login.php");
//Will run this script if login is correct otherwise loginError.php loads, Avoids both pages loading on same screen
if(!isset($view->loginError)){
    require_once('Models/UserDataSet.php');

    $view = new stdClass();
    $view->pageTitle = 'Welcome to Friend Finder';

    $userDataSet = new UserDataSet(); //creates new userDataSet object
    $view->userDataSet = $userDataSet->fetchAllUsers();  // Returns array of all users in database to be used in view

require_once('Views/index.phtml');}
