/**
 * Class to construct and retrieve the user's position using get location
 * @constructor
 */
function UserPosition() {

    /**
     * function to return user location. uses a promise to ensure location is
     * retrieved before executing success callback. callback method is in friendMap class
     * @returns Promise success or error callback
     */
    UserPosition.prototype.getLocation = function() {
        if (!navigator.geolocation) {
            status.textContent = 'Geolocation is not supported by your browser';
        } else {
            return new Promise((success, error) => {
                navigator.geolocation.getCurrentPosition(success, error);
            });
        }

    }
}