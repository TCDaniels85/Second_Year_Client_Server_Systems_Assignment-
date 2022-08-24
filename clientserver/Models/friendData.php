<?php

/**
 * class to assign data from the friend_Status database to a friendData object, extends userData class so
 * request details can be displayed
 */
class friendData extends UserData
{

    public $_id, $_user1, $_user2, $_status;

    public function __construct($dbRow) {
        parent::__construct($dbRow);     //constructor for super class, adds fields to user class
        $this->_id = $dbRow['id'];
        $this->_user1 = $dbRow['user1'];
        $this->_user2 = $dbRow['user2'];
        $this->_status = $dbRow['status'];


    }

    /**
     * Returns friendStatus ID
     * @return mixed
     */
    public function getID() {
        return $this->_id;
    }

    /**
     * returns userID from user1 column
     * @return mixed
     */
    public function getUser1() {
        return $this->_user1;
    }

    /**
     * Returns userID from user2 column
     * @return mixed
     */
    public function getUser2() {
        return $this->_user2;
    }

    /**
     * Returns friend status
     * @return mixed
     */
    public function getStatus() {
        return $this->_status;
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
            'id'=>$this->_id,
            'user1'=>$this->_user1,
            'user2'=>$this->_user2,
            'status'=>$this->_status,
            ];


    }


}