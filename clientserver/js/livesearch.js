//var newFilter = returnFilter();
document.getElementById("searchInput").value;

document.getElementById("filter").addEventListener('change', function(){
    showHint(document.getElementById("searchInput").value);
}, false);

//returns the member filter only if the user is logged in, this feature is not available if not logged in
if(loggedIn == true){
    document.getElementById("memberfilter").addEventListener('change', function(){
        showHint(document.getElementById("searchInput").value);
    }, false);
}

/**
 * function creates a new Request object, passes the user input string and passes the response to the
 * live search function
 * @param str string input from user
 */
function showHint(str){
    let newFilter = document.getElementById("filter").value; //sets value of filter
    if (str.length==0){
        document.getElementById("searchPopup").innerHTML = "";
        removePopup();
        return;
    }else {
        document.getElementById("searchPopup").innerText = 'Retrieving...';
        var requester = new Request();
        newFilter += " LIMIT 5";  //limits hints to ten records
        //if statement to select ajax call, dependent on if the user is logged in
        if(loggedIn == false) {

            requester.makeRequest(str, newFilter, liveSearch);
        } else if(loggedIn== true){     //checks if user is logged in before trying to set extra filter value, then sends to makeRequest2 in Request class
            let memberFilter = document.getElementById("memberfilter").value; // sets member filter value
            requester.makeRequest2(str, newFilter, memberFilter, liveSearch);
        }
        searchPopup();//starts popup method to contain results
    }
}

/**
 * Uses the reponse from the ajax call in the Requst class to create an array
 * object containing friend details using the JSONparse method.
 * @param response from the httprequest class
 */
function liveSearch(response){
    if (this.response != "no suggestions") {
        var uic = document.getElementById("searchPopup");

        uic.innerHTML = "<span onclick='removePopup()' class='close'>&times;</span><br/>Suggestions: <br/>";
        var people = JSON.parse(response);
        var index = 0;
        people.forEach(function (obj) {
            index++;

            uic.innerHTML += "<div><a class='friendlink' href='#'><img src='" + obj.picture + "' width='30' height='30'>" + obj.firstName + " " + obj.lastName + "</a><br/></div>";
            if (loggedIn == true){ //additional details if user is logged in
                uic.innerHTML += "<div> Username: " + obj.username + " </div>";
            }

        });
        if (index < 5) {
            uic.innerHTML += " <p class='hintText' >Showing " + index + " out of the <br/>" + people.length + " results obtained</p>";
        } else {
            uic.innerHTML += "<p class='hintText'>Showing " + index + " out of the top <br/>" + " 5 results obtained</p>";
        }
    }
}

/**
 * makes the popup visible to the user
 */
function searchPopup() {
    var popup = document.getElementById("searchPopup");
    if(!popup.classList.contains("show")) { //prevents popup from closing each key press
        popup.classList.toggle("show");
    }
}

/**
 * removes popup visibility
 */
function removePopup(){
    // var input = document.getElementById("searchInput");
    // input.stopPropagation();
    var popup = document.getElementById("searchPopup");
    if(popup.classList.contains("show")) {
        popup.classList.toggle("show");
    }
}




