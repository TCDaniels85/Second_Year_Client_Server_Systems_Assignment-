<?php
require_once('Models/Database.php');
require_once('Models/friendData.php');
class friendDataSet
{
    protected $_dbHandle, $_dbInstance;

    public function __construct() {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    /**
     * Returns all logged-in user's received friend requests
     * @param $user1 String logged in user's userID
     * @return array list of friends who have sent requests to logged-in user
     */
    public function fetchAllRequests($user1) {
        //Returns all records where logged-in user is user2 and the status of the relationship is 1(friend request)
        $sqlQuery = 'SELECT * FROM user_list, friend_status 
                    WHERE user_list.userid=friend_status.user1 
                    AND user2="' . $user1 . '" 
                    AND status="1" 
                    OR user_list.userid=friend_status.user2 
                    AND user1="' . $user1 . '" 
                    AND status="1";';

        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement, sql query

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new friendData($row);
        }
        return $dataSet;
    }

    /**
     * Fetches the records of all other users in friend_status database where the logged-in user is in the opposing column
     *eg all excepted friend requests that the user has either sent or received
     * @param $user1 String logged in user
     * @return array set of result records
     */
    public function fetchAllAccepted($user1) {
        $sqlQuery = 'SELECT * FROM user_list, friend_status WHERE user_list.userid=friend_status.user2 AND user1="' . $user1 . '" AND status="2" OR user_list.userid=friend_status.user1 AND user2="' . $user1 . '" AND status="2";';

        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement, sql query

        $dataSet = [];
        while ($row = $statement->fetch()) {
            //$dataSet[] = new userData($row);
            $dataSet[] = new friendData($row);
        }
        return $dataSet;
    }
    public function fetchAllAccepted2($user1) {
        $sqlQuery = 'SELECT * FROM user_list, friend_status WHERE user_list.userid=friend_status.user2 AND user1="' . $user1 . '" AND status="2" OR user_list.userid=friend_status.user1 AND user2="' . $user1 . '" AND status="2";';

        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement, sql query

        $dataSet = [];
        while ($row = $statement->fetch()) {
            //$dataSet[] = new userData($row);
            $dataSet[] = new friendData($row);
        }
        return $dataSet;
    }

    /**
     * Accept friend request, set's status column in friend_status to 2 (friends)
     * @param $userID String id of user logged in
     * @param $friendID String id of friend requesting friendship
     */
    public function acceptRequest($userID, $friendID){
        $sqlQuery = 'UPDATE friend_status SET status="2" WHERE user1="' . $friendID . '" AND user2="' . $userID . '";';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement, sql query
    }

    /**
     * Removes user friendship status from the friend_status database, also removes friend requests
     * @param $userID String user deleting the friendship
     * @param $friendID String user being deleted
     */
    public function removeFriendStatus($userID, $friendID){
        //Query deletes row where $userID and $friendID are in either column to end friendship
        $sqlQuery = 'DELETE FROM friend_status WHERE user1="' . $userID . '" AND user2="' . $friendID . '" OR user1="' . $friendID . '" AND user2="' . $userID . '";';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement, sql query

    }

    /**
     * Adds a friend request to the friend_status database
     * $user1 sends request, $user 2 receives request
     * @param $user1 String user sending request
     * @param $user2 String user receiving request
     */
    public function sendRequest($user1, $user2){
        $sqlQuery = 'INSERT INTO friend_status (user1, user2, status) VALUES ("' . $user1 . '", "' . $user2 . '", "1")';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); //execute , sql query
    }

    /**
     * Searches user database, user a left join to join the friend_status database to friendships,
     * returns all users who are not currently friends with logged-in users.
     * @param $searchTerm String term from input box that the user wishes to search
     * @param $field String field from user_list that the user wishes to search in
     * @param $order String order by ascending or descending alphabetical order
     * @param $userID int user id number
     * @return array set of user records to be returned
     */
    public function findFriends($userID, $searchTerm, $field, $order){
       //SQL query to return all users who are not currently friends with logged in user
        $sqlQuery = 'SELECT * FROM
(SELECT * FROM user_list where user_list.' . $field . '
LIKE :searchTerm) a inner JOIN (Select user1, MAX(id) as id, Max(user2) as user2, max(status) as status
from friend_status
where user1!="' . $userID. '" and user2!="' . $userID . '"
group by user1
union
select   user2 , Max(id) as id, MAX(user1) as user1, max(status) as status
from friend_status
where user1!="' . $userID . '" and user2!="' . $userID . '"
and user2 not in (select user1
from friend_status
where user1!="' . $userID . '" and user2!="' . $userID . '"
group by user1
)
group by user2) b
on a.userid = b.user1
union
SELECT *
FROM user_list
LEFT JOIN friend_status
ON user_list.userid=friend_status.user2
OR user_list.userid=friend_status.user1 where friend_status.user1 is null
AND user_list.' . $field . '
LIKE :searchTerm
ORDER BY ' . $order . ';';

        $searchTerm = $searchTerm . '%'; //used to add the % symbol to the bound parameter
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        //bind parameters to protect from SQL injection, only the user input fields are required to be bound
        $statement->bindParam(':searchTerm', $searchTerm);
        $statement->execute(); // execute the PDO statement, sql query

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new friendData($row);
        }
        return $dataSet;
    }

}