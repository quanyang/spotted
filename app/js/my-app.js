// Initialize your app
var myApp = new Framework7();

// Export selectors engine
var $$ = Dom7;

// Add view
var mainView = myApp.addView('.view-main', {
    // Because we use fixed-through navbar we can enable dynamic navbar
    dynamicNavbar: true
});

/* Form variables*/

var photoURL = "";
var longitude = 0;
var latitude = 0;

$(document).ready(function() {


    function sendFoundPetImage() {
        var formData = new FormData($('#lost-form')[0]);
        var url = "api/photo";
        // the script where you handle the form input.
        $('.loader-overlay').show();
        $.ajax({
            type: "POST",
            url: url,
            data: formData, // serializes the form's elements.
            xhr: function() { // Custom XMLHttpRequest
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) { // Check if upload property exists
                }
                return myXhr;
            },
            success: function(data) {
                // Should redirect to job page
                $('#lost-form')[0].reset();
                photoURL = data['photoURL'];
                mainView.router.loadPage('lost.html');
                $('.loader-overlay').hide();
            },
            error: function(data) {
                console.log(data);
            },
            cache: false,
            contentType: false,
            processData: false
        }, 'json');
    }

    $('#lost-image').click(function() {
        $('#lost-form')[0].reset();
    });
    $('#lost-image').change(function() {
        sendFoundPetImage();
    });


    function sendStrayImage() {
        var formData = new FormData($('#stray-form')[0]);
        var url = "api/photo";
        // the script where you handle the form input.
        $('.loader-overlay').show();
        $.ajax({
            type: "POST",
            url: url,
            data: formData, // serializes the form's elements.
            xhr: function() { // Custom XMLHttpRequest
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) { // Check if upload property exists
                }
                return myXhr;
            },
            success: function(data) {
                // Should redirect to job page
                $('.loader-overlay').hide();
                $('#stray-form')[0].reset();
                photoURL = data['photoURL'];
                mainView.router.loadPage('spotted.html');
            },
            error: function(data) {
                console.log(data);
            },
            cache: false,
            contentType: false,
            processData: false
        }, 'json');
    }

    $('#stray-image').click(function() {
        $('#stray-form')[0].reset();
    });
    $('#stray-image').change(function() {
        sendStrayImage();
    });
});

// Callbacks to run specific code for specific pages, for example for About page:
myApp.onPageInit('about', function(page) {
    // run createContentPage func after link was clicked
    $$('.create-page').on('click', function() {
        createContentPage();
    });
});

function lostPageSubmit() {

    var formData = new FormData($('#lost-details-form')[0]);
    formData.append("longitude", longitude);
    formData.append("latitude", latitude);
    var buffer = this.photoURL.split('/');
    formData.append("image", buffer[buffer.length-1]);

    var url = "api/report/lost";
        // the script where you handle the form input.
        $.ajax({
            type: "POST",
            url: url,
            data: formData, // serializes the form's elements.
            xhr: function() { // Custom XMLHttpRequest
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) { // Check if upload property exists
                }
                return myXhr;
            },
            success: function(data) {
                // Should redirect to job page
                myApp.alert('Your request has been sent!', "", function() {
                    location.href="http://128.199.90.231/index.html";
                });
            },
            error: function(data) {
                console.log(data);
            },
            cache: false,
            contentType: false,
            processData: false
        }, 'json');
}

myApp.onPageInit('lost', function(page) {
    $('#image-holder').attr('src',photoURL);
    initMap();
    getLocation();
    $$('#lost-details-form').submit( function(ev) {
        ev.preventDefault();
        lostPageSubmit();
        return false;
    });
});

function strayPageSubmit() {

    var formData = new FormData($('#stray-details-form-1')[0]);

    formData.append("longitude", longitude);
    formData.append("latitude", latitude);
    var buffer = this.photoURL.split('/');
    formData.append("image", buffer[buffer.length-1]);

    formData.append("fullName", $('#stray-details-form-2 [name="fullName"]').val());
    formData.append("email", $('#stray-details-form-2 [name="email"]').val());
    formData.append("number", $('#stray-details-form-2 [name="number"]').val());

    var url = "api/report/stray";
        // the script where you handle the form input.
        $.ajax({
            type: "POST",
            url: url,
            data: formData, // serializes the form's elements.
            xhr: function() { // Custom XMLHttpRequest
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) { // Check if upload property exists
                }
                return myXhr;
            },
            success: function(data) {
                // Should redirect to job page
                 myApp.alert('Your report has been sent!',"", function () {
                        location.href="http://128.199.90.231/index.html";
                    });
            },
            error: function(data) {
                console.log(data);
            },
            cache: false,
            contentType: false,
            processData: false
        }, 'json');
}

myApp.onPageInit('spotted', function(page) {
    $('#image-holder').attr('src',photoURL);
    initMap();
    getLocation();
});


myApp.onPageInit('spotted-2', function (page) {
    $$('#stray-details-form-2').submit( function(ev) {
        ev.preventDefault();
         myApp.confirm('All information will be sent to relevant rescue groups. Kindly refrain from irrelevant spam.', 'Are you sure?',function () {
                    strayPageSubmit();
                });
        
        return false;
    });
});

// Generate dynamic page
var dynamicPageIndex = 0;

function createContentPage() {
    mainView.router.loadContent(
        '<!-- Top Navbar-->' +
        '<div class="navbar">' +
        '  <div class="navbar-inner">' +
        '    <div class="left"><a href="#" class="back link"><i class="icon icon-back"></i><span>Back</span></a></div>' +
        '    <div class="center sliding">Dynamic Page ' + (++dynamicPageIndex) + '</div>' +
        '  </div>' +
        '</div>' +
        '<div class="pages">' +
        '  <!-- Page, data-page contains page name-->' +
        '  <div data-page="dynamic-pages" class="page">' +
        '    <!-- Scrollable page content-->' +
        '    <div class="page-content">' +
        '      <div class="content-block">' +
        '        <div class="content-block-inner">' +
        '          <p>Here is a dynamic page created on ' + new Date() + ' !</p>' +
        '          <p>Go <a href="#" class="back">back</a> or go to <a href="services.html">Services</a>.</p>' +
        '        </div>' +
        '      </div>' +
        '    </div>' +
        '  </div>' +
        '</div>'
    );
    return;
}

function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else {
        x.innerHTML = "Geolocation is not supported by this browser.";
    }
}

function showPosition(position) {
    var x = document.getElementById("currLoc");

    var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
    var geocoder = geocoder = new google.maps.Geocoder();
    geocoder.geocode({
        'latLng': latlng
    }, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            if (results[1]) {
                x.innerHTML = results[1].formatted_address + " (Current location) ";
            }
        }
    });
}

function showhide() {
    var x = document.getElementById("category").value;
    var div1 = document.getElementById("others");
    var div2 = document.getElementById("category");

    if ((div1.style.display !== "none") && (x == 'Other')) {
        div1.style.display = "none";
    } else if ((div1.style.display == "none") && (x == 'Other')) {
        div1.style.display = "block";
        div2.style.display = "none";
    }
}

function showhideLocation() {
    var x = document.getElementById("myLocation").value;
    var div1 = document.getElementById("newLoc");
    var div2 = document.getElementById("myLocation");
    var div3 = document.getElementById("currLoc");

    if ((div1.style.display !== "none") && (x == 'editLoc')) {
        div1.style.display = "none";
    } else if ((div1.style.display == "none") && (x == 'editLoc')) {
        div1.style.display = "block";
        div2.style.display = "none";
        div3.style.display = "none";
    }
}

function initMap() {

    var map = new google.maps.Map(document.getElementById('map'), {
        center: {
            lat: -34.397,
            lng: 150.644
        },
        zoom: 17
    });
    var infoWindow = new google.maps.InfoWindow({
        map: map
    });

    // Try HTML5 geolocation.
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

            longitude = position.coords.longitude;
            latitude = position.coords.latitude;

            infoWindow.setPosition(pos);
            infoWindow.setContent('Current location');
            map.setCenter(pos);
        }, function() {
            handleLocationError(true, infoWindow, map.getCenter());
        });
    } else {
        // Browser doesn't support Geolocation
        handleLocationError(false, infoWindow, map.getCenter());
    }
}

function handleLocationError(browserHasGeolocation, infoWindow, pos) {
  infoWindow.setPosition(pos);
  infoWindow.setContent(browserHasGeolocation ?
    'Error: The Geolocation service failed.' :
    'Error: Your browser doesn\'t support geolocation.');
}

function onClickFunction(){
         myApp.confirm('All information will be sent to relevant rescue groups. Kindly refrain from irrelevant spam.', 'Are you sure?',function () {
            myApp.alert('Your report has been sent!',"", function () {
                mainView.router.load({ url: 'index.html' });
            });
        });
    }

