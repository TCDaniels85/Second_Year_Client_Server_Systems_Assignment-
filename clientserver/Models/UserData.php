<?php

/**
 * Class to assign data from the user_list database to a userData object
 */
class UserData implements jsonSerializable
{
    
    protected $_userID, $_firstName, $_lastName,$_username, $_password, $_email, $_gender, $_longitude, $_latitude, $_picture;
    
    public function __construct($dbRow) {
        $this->_userID = $dbRow['userid'];
        $this->_firstName = $dbRow['first_name'];
        $this->_lastName = $dbRow['last_name'];
        $this->_username = $dbRow['username'];
        $this->_password = $dbRow['password'];
        $this->_email = $dbRow['email'];
        $this->_gender = $dbRow['gender'];
        $this->_longitude = $dbRow['longitude'];
        $this->_latitude = $dbRow['latitude'];
        $this->_picture = $dbRow['picture'];

    }

    /**
     * returns userID from database
     * @return mixed
     */
    public function getUserID() {
        return $this->_userID;
    }

    /**
     * returns user first name
     * @return mixed
     */
    public function getFirstName() {
       return $this->_firstName;
    }

    /**
     * returns user last name
     * @return mixed
     */
    public function getLastName() {
       return $this->_lastName;
    }

    /**
     * returns username
     * @return mixed
     */
    public function getUsername() {
        return $this->_username;
    }

    /**
     * returns user password
     * @return mixed
     */
    public function getPassword() {
       return $this->_password;
    }

    /**
     * Returns user email
     * @return mixed
     */
    public function getEmail() {
       return $this->_email;
    }

    /**
     * returns user gender
     * @return mixed
     */
    public function getGender() {
        return $this->_gender;
    }

    /**
     * returns user's longitude
     * @return mixed
     */
    public function getLongitude() {
        return $this->_longitude;
    }

    /**
     * returns user's latitude
     * @return mixed
     */
    public function getLatitude() {
        return $this->_latitude;
    }

    /**
     * returns users picture
     * @return mixed
     */
    public function getPicture() {
        return $this->_picture;
    }

    /**
     * Ensures correct json data is returned from the class
     * @return array
     */
    public function jsonSerialize()
    {

        return[
        'userID'=>$this->_userID,
        'firstName'=>$this->_firstName,
        'lastName'=>$this->_lastName,
        'username'=>$this->_username,
        'password'=>$this->_password,
        'email'=>$this->_email,
        'gender'=>$this->_gender,
        'longitude'=>$this->_longitude,
        'latitude'=>$this->_latitude,
        'picture'=>$this->_picture,
        ];

    }
}


