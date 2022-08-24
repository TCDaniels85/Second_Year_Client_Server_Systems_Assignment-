<?php
session_start();
require_once('Models/UserDataSet.php');


$view = new stdClass();

if (isset($_POST["loginbtn"])) {
    //sets username and password variables for use in methods to check password
    $username = $_POST["username"];
    $password = $_POST["password"];

    $userDataSet = new UserDataSet();

    //if statement send username and password to password check function, returns true if there is a match then sets Session
    if ($userDataSet->passwordCheck($username, $password)) {
        setcookie('username', $username); //sets cookie so username can be displayed in relevant text box after log out
        //Sets session data for user, fields set can be used in other methods
        $_SESSION["login"] = $userDataSet->returnName($username);
        $_SESSION["userID"] = $userDataSet->returnUserID($username);
        $_SESSION["username"] = $username;


        //$userDataSet->hashPasswords();  // Used once to hash existing mocked records in database
    }
    else
    {
        require('loginError.php');
        //sets this variable which can be used by other php pages to decide if to display (avoids login error and current page both displaying)
        $view->loginError= 'Error';
    }
}

if (isset($_POST["logoutbtn"]))
{
//logs user out (when button pressed), unsets session info
    unset($_SESSION["login"]);
    unset($_SESSION["userID"]);
    unset($_SESSION["username"]);
    //session_destroy();
}
