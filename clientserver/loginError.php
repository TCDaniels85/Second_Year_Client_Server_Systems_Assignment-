<?php

require_once("login.php");
//Simple page to inform user there was an error logging in, sets page title
$view = new stdClass();
$view->pageTitle = 'Error Logging in';



require_once('Views/loginError.phtml');