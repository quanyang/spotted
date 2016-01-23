// Initialize your app
var myApp = new Framework7();

// Export selectors engine
var $$ = Dom7;


// Add view
var mainView = myApp.addView('.view-main', {
    // Because we use fixed-through navbar we can enable dynamic navbar
    dynamicNavbar: true
});

// Callbacks to run specific code for specific pages, for example for About page:
myApp.onPageInit('about', function (page) {
    // run createContentPage func after link was clicked
    console.log("")
    $$('.create-page').on('click', function () {
        createContentPage();
    });
});

myApp.onPageInit('upload', function (page) {
    getLocation();
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
    console.log("in here");
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else { 
        x.innerHTML = "Geolocation is not supported by this browser.";
    }
}

function showPosition(position) {
    var x = document.getElementById("currLoc");
    
            var latlng = new google.maps.LatLng( position.coords.latitude, position.coords.longitude);
            var geocoder = geocoder = new google.maps.Geocoder();
            geocoder.geocode({ 'latLng': latlng }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[1]) {
                        x.innerHTML = results[1].formatted_address + " (Current location) "  ;
                    }
                }
            });
}

function showhide() {
    var x = document.getElementById("mySelect").value;
    var div1 = document.getElementById("newpost");
    var div2 = document.getElementById("mySelect");

    if ((div1.style.display !== "none") && (x == 'Other')) {
        div1.style.display = "none";
    }
    else if ((div1.style.display == "none") && (x == 'Other')){
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
    }
    else if ((div1.style.display == "none") && (x == 'editLoc')){
        div1.style.display = "block";
        div2.style.display = "none";
        div3.style.display = "none";
    }
}

function myFunction(a, b) {
    return a * b;
}
