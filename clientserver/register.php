<?php
require_once("login.php");
require_once('Models/UserDataSet.php');
//Will run this script if login is correct otherwise loginError.php loads, Avoids both pages loading on same screen
if(!isset($view->loginError)) {
    $view = new stdClass();
    $view->pageTitle = 'Register';

    if (isset($_POST['submit'])) {
        //Sets variables from fields in form
        $firstName = $_POST['firstname'];
        $lastName = $_POST['lastname'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); //hashes password when setting variable
        $gender = $_POST['gender'];
        $longitude = 23.8951;    // mock longitude & latitude until this is implemented
        $latitude = 48.6437;


        $directory = 'images/'; //Directory to store image
        $fileName = $directory . basename($_FILES['picture']['name']);  //creates file path for image to be saved in
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE); //Creates an instance of finfo for use later

        //if statement to set imageOK variable to tru or false for validation
        if (finfo_file($fileInfo, $_FILES['picture']['tmp_name']) == 'image/jpeg' || finfo_file($fileInfo, $_FILES['picture']['tmp_name']) == 'image/png') {
            //file type checked, ok
            $imageOK = true;
            if (file_exists($fileName)) {
                //Check filename not already stored in images
                $errorMsg = "Sorry, file already exists.";  // sets message to be displayed to user
                $imageOK = false;
            } else if ($_FILES['picture']['size'] > 1000000) {
                //check size of image is below 1MB (size may need adjusting) to prevent huge files being uploaded
                $errorMsg = 'Sorry your file size needs to be less than 1MB';
                $imageOK = false;
            }
        } else {
            $errorMsg = 'Invalid file type, please choose either a jpeg or png image file';
            $imageOK = false;
        }
        $picture = $fileName; // assigns filepath to picture variable to be passed to database

        $userDataSet = new UserDataSet();
        //validation to check details entered are ok
        if ($_POST['password'] == $_POST['password2'] && $userDataSet->checkUsername($username) && $_POST['robotcheck'] == 'orange' && $imageOK) {
            $userDataSet = new UserDataSet();
            //adds user to database
            $userDataSet->addUser($firstName, $lastName, $username, $email, $password, $gender, $picture, $longitude, $latitude);
            move_uploaded_file($_FILES['picture']['tmp_name'], $fileName);  // file uploaded if register goes through ok

            //Message which replaces the register form in the view
            require('Views/template/header.phtml');
            echo '<h3>Thank you for registering with Friend Finder</h3>';
            require('Views/template/footer.phtml');

        } else if (!$userDataSet->checkUsername($username)) {
            $view->message = 'Sorry this username has already been taken, please pick another'; //checks username is unique
            require_once('Views/register.phtml');
        } else if ($_POST['robotcheck'] !== 'orange') {
            $view->message = 'Sorry you failed the robot test'; //sets error message if robot check failed
            require_once('Views/register.phtml');
        } else if ($imageOK == false) {
            $view->message = $errorMsg; //sets error message if image check fails

            require_once('Views/register.phtml');
        }else {
            $view->message = 'Sorry your passwords did not match, please try again'; //sets error message for password check fail
            require_once('Views/register.phtml');
        }
    } else {

        require_once('Views/register.phtml');
    }

}