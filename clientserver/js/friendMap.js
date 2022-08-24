var lat = 0.0;// coordinates to center map
var lon = 0.0;
var zoom = 2; //set map initial zoom
var mapMarkers = [];
var fromProjection = new OpenLayers.Projection("EPSG:4326"); //Transform from WGS 1984
var toProjection = new OpenLayers.Projection("EPSG:900913"); // mercator projection



var position = new OpenLayers.LonLat(lon, lat).transform(fromProjection, toProjection); //sets position to initial coordinates
//creates new layer instances
var markers = new OpenLayers.Layer.Markers("Markers");
var vectorLayer = new OpenLayers.Layer.Vector("Overlay");
//Add a selector control to the vectorLayer with popup functions
var controls = {
    selector: new OpenLayers.Control.SelectFeature(vectorLayer, { onSelect: createPopup, onUnselect: destroyPopup })
};

//only able to create map if logged in
if(loggedIn == true){
    createMap();
    retrieveFriends(); //retrieves friend details and adds markers

    }


/**
 * function to create the map and add all the layers and controls
 */
function createMap(){
    map = new OpenLayers.Map("friend-map");
    var mapnik = new OpenLayers.Layer.OSM();
    map.addLayer(mapnik);
    map.setCenter(position, zoom);
    map.addLayer(markers);
    map.addLayer(vectorLayer);
    map.addControl(controls['selector']);
    controls['selector'].activate();
}

/**
 * function to add all the markers to the marker layer
 */
function addMarkers(){
    vectorLayer.destroyFeatures(); //removes any markers on the vector layer, used when refreshed by timer

    mapMarkers.forEach(createMarker);// loops through mapMarkers array to create a marker for each friend
    /**
     * Creates a marker current friend object by creating a feature and adding it to the vector layer
     * @param array of the users accepted friends
     */
    function createMarker(friend){
        //adds a description and image returned from the ajax call for the friend object
        var feature = new OpenLayers.Feature.Vector(
            new OpenLayers.Geometry.Point( friend.longitude, friend.latitude).transform(fromProjection, toProjection),
            {description: '<h6>' + friend.firstName + ' ' + friend.lastName  + '</h6>' + '<img width=\'50\' height=\'50\' src="' + friend.picture + '"  ><p>Also known by the username: ' + friend.username + '.</p>'} ,
            {externalGraphic: '/images/mymarker.png', graphicHeight: 30, graphicWidth: 30, graphicXOffset:-12, graphicYOffset:-25  }
        );
        vectorLayer.addFeatures(feature);
    }
    //Adds the user marker to the map after making the ajax call to retrieve details
    retrieveUserDetails();
}


/**
 * creates a new http request object and passes the response to the findFriendsMap function
 * passes the userID (from the logged in users friend data) to the method in the Request class
 */
function retrieveFriends(){
    var request = new Request();
    request.searchFriends(userID, findFriendsMap);
}

/**
 * Uses the reponse from the ajax call in the Requst class to create an array
 * object containing friend details using the JSONparse method.
 * @param response from the httprequest class
 */
function findFriendsMap(response){
    if (this.response != "no suggestions") {
        mapMarkers = JSON.parse(response);
        addMarkers();
    }
}

/**
 * Creates the popup feature on the map, when marker is clicked, user details display
 * @param feature
 */
function createPopup(feature) {
    feature.popup = new OpenLayers.Popup.FramedCloud("pop",
        feature.geometry.getBounds().getCenterLonLat(),
        null,
        '<div class="markerContent">'+feature.attributes.description+'</div>',
        null,
        true,
        function() { controls['selector'].unselectAll(); }
    );
    //feature.popup.closeOnMove = true;
    map.addPopup(feature.popup);
}

/**
 * Removes popup from screen
 * @param feature
 */
function destroyPopup(feature) {
    feature.popup.destroy();
    feature.popup = null;
}

/**
 * creates a new http request object and passes the response to the userDetails function
 * passes the username from the logged in user to the method in the Request class
 */
function retrieveUserDetails(){
    var request = new Request();
    request.getUserDetails(username, userDetails);

}

function userDetails(response){
    if (this.response != "no suggestions") {
          user = JSON.parse(response);

          //retrieves user location information and adds marker
        var userLocation = new UserPosition();
        userLocation.getLocation().then((position) => {  //promise ensures marker is added once the user location data is received
            var feature = new OpenLayers.Feature.Vector(
                new OpenLayers.Geometry.Point(position.coords.longitude,position.coords.latitude).transform(fromProjection, toProjection),
                {description: '<h6>' + user.firstName + ' ' + user.lastName + '</h6><p>This is your location</p>'} ,
                {externalGraphic: user.picture, graphicHeight: 30, graphicWidth: 30, graphicXOffset:-12, graphicYOffset:-25  }
            );
            vectorLayer.addFeatures(feature);
        });
    }
}

/**
 * Updates the user location, retrieves user location from UserPostion class and sends this to the
 * http request class, returns error alert if something goes wrong
 */
function updateUserLocation(){

    var userLocation = new UserPosition();
    userLocation.getLocation().then((position) => {
        var request = new Request();
        request.updateDatabaseLocation(username, position.coords.longitude, position.coords.latitude);

    }, (error) => {alert("error: " + error.code);});
}

//sets time intervals for user location and map markers to be updated
setInterval(updateUserLocation, 10000);
setInterval(retrieveFriends, 10000);






