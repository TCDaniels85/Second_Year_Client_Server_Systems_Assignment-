<?php

require_once('Models/Database.php');
require_once('Models/UserData.php');

class UserDataSet
{
    protected $_dbHandle, $_dbInstance;

    public function __construct() {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    /**
     * Returns all users currently present in the database
     * @return array user records as database rows
     */
    public function fetchAllUsers() {
        //Query to return all users from database
        $sqlQuery = 'SELECT * FROM user_list';

        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement, sql query
        //loop to create userData object using fields from user_list database
        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new userData($row);
        }
        return $dataSet;
    }

    /**
     *
     * Adds user to database, parameters are fields sent from the controller register.php, retrieved from
     * form input from user.
     *
     * @param $firstName String user first name
     * @param $lastName String user last name
     * @param $username String username
     * @param $email String user email
     * @param $password String user password(hashed)
     * @param $gender String gender
     * @param $picture String filepath for uploaded profile picture
     * @param $longitude float longitude
     * @param $latitude float longitude
     */
    public function addUser($firstName, $lastName, $username, $email, $password, $gender, $picture, $longitude, $latitude){
        //sql to add record
        $sqlQuery = 'INSERT INTO user_list (first_name, last_name, username, password, email, gender, longitude, latitude, picture) VALUES (?,?,?,?,?,?,?,?,?);';

        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        //bind parameters to protect from SQL injection
        $statement->bindParam(1, $firstName);
        $statement->bindParam(2, $lastName);
        $statement->bindParam(3, $username);
        $statement->bindParam(4, $password);
        $statement->bindParam(5, $email);
        $statement->bindParam(6, $gender);
        $statement->bindParam(7, $longitude);
        $statement->bindParam(8, $latitude);
        $statement->bindParam(9, $picture);

        $statement->execute(); // execute the PDO statement

    }

    /**
     * Checks username against all usernames in the user_list database, checks if username is unique
     * @param $username String username that is being registered
     * @return bool false if the username already exists
     */
    public function checkUsername($username){
        $sqlQuery = 'SELECT username FROM user_list WHERE username= :username;';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindParam(':username', $username);
        $statement->execute(); // execute the PDO statement
        //set variable check as password value from matching username, null if no match
        $containsUsername = $statement->fetch();
        if(!empty($containsUsername)){
            return false;
        } else {
            return true;
        }
    }

    /**
     * checks username and password, returns false if both these fields do not match any record from the
     * database.
     * @param $username username entered by user
     * @param $password password entered by user
     * @return boolean
     */
    public function passwordCheck($username, $password){
        $sqlQuery = 'SELECT password FROM user_list WHERE username= :username;';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindParam(':username', $username);
        $statement->execute(); // execute SQL statement
        //set variable check as password value from matching username, null if no match
        $check = $statement->fetch();

        // Checks that this has a value, if there is no value this means no matching username
        if(!empty($check)) {
            //Verifies password entered against hashed password in database
            if (password_verify($password, $check[0])) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Receives username from login.php controller, retrieves user's first name from database
     * @param $username String logged in username
     * @return String user's first name
     */
    public function returnName($username){
        //query to return database row which contains logged-in user's username
        $sqlQuery = 'SELECT * FROM user_list WHERE username= :username;';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindParam(':username', $username);
        $statement->execute(); //execute sql query
        //assign record details to userData object, username is unique so this should be one record
        $userdata = new userData($statement->fetch());
        return $userdata->getFirstName();
    }

    /**
     * Receives username from login.php controller, retrieves userid from database
     * @param $username String logged in username
     * @return String user's ID
     */
    public function returnUserID($username){
        //query to return database row which contains logged-in user's username
        $sqlQuery = 'SELECT * FROM user_list WHERE username= :username ;';

        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindParam(':username', $username);
        $statement->execute();
        //assign record details to userData object, username is unique so this should be one record
        $userData = new userData($statement->fetch());
        return $userData->getUserID();
    }

    /**
     * Searches users in the database based on a user input text field, orders records according to user selection
     * @param $searchTerm String search term defined by user input
     * @param $field String field to search by
     * @param $order String order selected field by ascending or descending alphabetical order
     * @return array of user records
     */
    public function searchUsers($searchTerm, $field, $order){

        $sqlQuery = 'SELECT * FROM user_list WHERE ' . $field . ' LIKE :searchTerm ORDER BY ' . $order . ';';

        $searchTerm = $searchTerm . '%'; //used to add the % symbol to the bound parameter
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        //bind parameters to protect from SQL injection, only the user input fields are required to be bound
        $statement->bindParam(':searchTerm', $searchTerm);
        $statement->execute(); // execute the PDO statement, sql query

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new userData($row);
        }
        return $dataSet;
    }

    /**
     * returns a record based upon unique username value
     * @param username string
     * @return array of user records
     */
    public function returnUser($username){
        //query to return database row which contains logged-in user's username
        $sqlQuery = 'SELECT * FROM user_list WHERE username= :username;';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindParam(':username', $username);
        $statement->execute(); //execute sql query
        //assign record details to userData object, username is unique so this should be one record
        $userdata = new userData($statement->fetch());
        return $userdata;
    }

    /**
     * updates users location details
     * @param $username
     * @param $lon longitude
     * @param $lat latitude
     */
    public function updateLocation($username, $lon, $lat){
        $sqlQuery = 'UPDATE user_list SET longitude="' . $lon . '", latitude="' . $lat . '" WHERE username= :username;';

        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->bindParam(':username', $username);
        $statement->execute();
    }



    /**
     * Function used to hash all passwords in database (as they were just plain text) to match new records
     * that will have hashed passwords when added. Loops through records updating passwords one by one
     * This was only used once (bound to the login.php successful login to initiate method)
     */
    public function hashPasswords(){
        $index = "1";
        while($index < "1001") {
            $sqlQuery = 'SELECT * FROM user_list WHERE userid = "' . $index . '"; '; // Retrieves user record at id number equal to the current $index

            $statement = $this->_dbHandle->prepare($sqlQuery);
            $statement->execute();

            $userData = new userData($statement->fetch());
            $password = $userData->getPassword();  //retrieves password
            $newPassword = password_hash($password, PASSWORD_DEFAULT); //hashes password

            $sqlQuery2 = 'UPDATE user_list SET password="' . $newPassword . '" where userid = "' . $index . '";'; // updates the user record's password field with hashed version
            $statement = $this->_dbHandle->prepare($sqlQuery2);
            $statement->execute();
            $index++; //increment index by 1
        }
    }

}