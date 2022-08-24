/**
 * This class create a new httprequest object to send an ajax call to query the database and send json data back to be
 * used in the other class methods. Requests are sent to either livesearch or friends map php scripts, which then query the database
 *
 */
function Request(){
    /**
     * http request method, creates ajax request then passes a another
     * method as a parameter to use response text for non-logged in users
     * @param str string to pass to get hint for querying database
     * @param method, method to pass the reposnse to
     */
    Request.prototype.makeRequest = function(str, filter, method){
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                method(this.responseText);

            }
        }
        //token used to add a level of security
        xmlhttp.open("GET", "livesearch.php?q=" + str + "&a=" + filter + "&token=" + token, true);
        xmlhttp.send();
    }
    /**
     * function to apply filters and return data for the livesearch, logged-in ussers have more
     * filter options which required another request
     * @param str string to pass to get hint for querying database
     * @param filterB filter to be applied
     * @param filterC filter to be applied
     * @param method method to pass the response to
     */
    Request.prototype.makeRequest2 = function(str, filterB, filterC, method){
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                method(this.responseText);

            }
        }
        xmlhttp.open("GET", "livesearch.php?q=" + str + "&a=" + filterB + "&b=" + filterC +  "&token=" + token + "&type=loggedin", true);
        xmlhttp.send();
    }
    /**
     * method to return the users friend details in json format
     * @param userid user's id from the friend table database
     * @param findFriendsMap returns data to findFriendsMap method
     */
    Request.prototype.searchFriends = function(userid, findFriendsMap){

        var xmlhttp2 = new XMLHttpRequest();
        xmlhttp2.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                findFriendsMap(this.responseText);

            }

        }
        xmlhttp2.open("GET", "friendsMap.php?q=" + userid + "&token=" + token + "&type=loggedIn", true);
        xmlhttp2.send();
    }

    /**
     * method to return the user's details using the unique user id attribute.
     * @param username logged in username
     */
    Request.prototype.getUserDetails  = function(username){
        var userxmlhttp = new XMLHttpRequest();
        userxmlhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                userDetails(this.responseText);
            }
        }
        userxmlhttp.open("GET", "friendsMap.php?q=" + username + "&token=" + token + "&type=userDetails", true);
        userxmlhttp.send();

    }
    /**
     * method to update the user's geolocation data in the database
     *
     * @param username logged in username
     * @param lon longitude
     * @param lat lattitude
     */
    Request.prototype.updateDatabaseLocation = function(username, lon, lat){

        var locationxmlhttp = new XMLHttpRequest();
        locationxmlhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {

            }
        }
        locationxmlhttp.open("GET", "friendsMap.php?q=" + lon + "&r=" + lat + "&s=" + username + "&token=" + token + "&type=locationUpdate", true);
        locationxmlhttp.send();

    }

}