$(document).ready(async function(){
	    
    waiting(); // dari load file global.js

    await getDataEmployee(); // menunggu data employee dimuat
    await getDataOffice(); // menunggu data office dimuat
    await getLocation(); // menunggu data location dimuat
    await loadGoogleMapsAPI() // menunggu data google maps API dimuat
        .then(function() {
            initMap();
        })
        .catch(function(error) {
            console.error('Error loading Google Maps API:', error);
        });

    Swal.close();

});

function initMap() {

    // Set coordinats **************************************
    var officeLatLng = { lat: parseFloat(document.getElementById('latitude_office').value), lng: parseFloat(document.getElementById('longitude_office').value) };
    var userLatLng = { lat: parseFloat(document.getElementById('latitude_user').value), lng: parseFloat(document.getElementById('longitude_user').value) };
    
    // Set maps **************************************
    var map_canvas = new google.maps.Map(document.getElementById("map_canvas"), {
        zoom: 11,
        zoomControl: false,
        mapTypeControl: false,
        scaleControl: false,
        streetViewControl: false,
        fullscreenControl: false,
        mapId: "DEMO_MAP_ID"
    });

    // Set marker office **************************************
    const priceTagOffice = document.createElement("div");
    priceTagOffice.className = "price-tag-office text-center";
    priceTagOffice.innerHTML = '<i class="fas fa-building text-sm mt-1"></i><br><label class="mb-0" style="font-size: 10px">KANTOR</label>';
      
    new google.maps.marker.AdvancedMarkerElement({
        map: map_canvas,
        position: officeLatLng,
        content: priceTagOffice,
        title: "Lokasi Kantor"
    });

    // Set marker user **************************************
    const priceTagUser = document.createElement("div");
    priceTagUser.className = "price-tag-user text-center";
    priceTagUser.innerHTML = '<img src="' + document.getElementById('url_photo_user').value + '" class="rounded-circle" style="width:30px;"><br><label class="mb-0" style="font-size: 10px">ANDA</label>';

    new google.maps.marker.AdvancedMarkerElement({
        map: map_canvas,
        position: userLatLng,
        content: priceTagUser,
        title: "Lokasi Anda"
    });

    // Set circle radius office **************************************
    new google.maps.Circle({
        strokeColor: "#FF0000",
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: "#FF0000",
        fillOpacity: 0.35,
        map: map_canvas,
        center: officeLatLng,
        radius: parseFloat(document.getElementById('radius_user').value), // in meters
    });

    // Set center maps between coodinats office and user **************************************
    var markers = [
        officeLatLng,
        userLatLng
    ];

    if (markers.length === 0) {
        var defaultLatLng = {lat: 0, lng: 0};
    }
    else {
        var sumLat = 0;
        var sumLng = 0;

        markers.forEach(function(marker) {
            sumLat += marker.lat;
            sumLng += marker.lng;
        });

        var avgLat = sumLat / markers.length;
        var avgLng = sumLng / markers.length;

        var defaultLatLng = {lat: avgLat, lng: avgLng};
    }

    // Define the line coordinates
    var lineCoordinates = [
        officeLatLng,
        userLatLng
      ];

    // Create the polyline and set its path
    var line = new google.maps.Polyline({
        path: lineCoordinates,
        geodesic: true,
        strokeColor: 'green',
        strokeOpacity: 1.0,
        strokeWeight: 2
    });

    // Set the map to display the line
    line.setMap(map_canvas);

    var distance = getDistance(officeLatLng.lat, officeLatLng.lng, userLatLng.lat, userLatLng.lng);

    // console.log('Distance between the points (in kilometers): ' + distance);
    document.getElementById('distance').value = Number(distance) * 1000;
    
    var geocoder = new google.maps.Geocoder();
        
    geocoder.geocode({'location': userLatLng}, function(results, status) {
        if (status === 'OK') {
            for (var a = 0; a < results.length; a++) {
                for (var i = 0; i < results[a].address_components.length; i++) {
                        for (var j = 0; j < results[a].address_components[i].types.length; j++) {
                            if (results[a].address_components[i].types[j] == "postal_code") {
                                var postal_code = results[a].address_components[i].long_name;
                                document.getElementById("postal_code_user").value = postal_code;
                            }
                        }
                    }
                }
                if (!results[0]) { console.log('No results found'); }
            } 
        else { console.log('Geocoder failed due to: ' + status); }
    });
    
    map_canvas.setCenter(defaultLatLng);
}

function getDistance(lat1, lon1, lat2, lon2) {
    const earthRadius = 6371; // Radius of the Earth in kilometers
    const dLat = toRadians(lat2 - lat1);
    const dLon = toRadians(lon2 - lon1);
    
    const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(toRadians(lat1)) * Math.cos(toRadians(lat2)) *
            Math.sin(dLon / 2) * Math.sin(dLon / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    
    const distance = earthRadius * c; // Distance in kilometers
    return distance;
}

function toRadians(degrees) {
    return degrees * (Math.PI / 180);
}

// Define function to load Google Maps API script asynchronously
function loadGoogleMapsAPI() {
    return new Promise(function(resolve, reject) {
        var script = document.createElement('script');
        script.src = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyCoR8XQr6YwMLTyPq8ABc5FppBTXkc60BE&region=ID&language=id&callback=initMap&loading=async&libraries=marker';
        script.async = true;
        script.defer = true;
        // script.onload = resolve;
        script.onerror = reject;
        document.head.appendChild(script);
    });
}

async function getLocation() {
    if (navigator.geolocation) {
        waiting(); // dari load file global.js
        await navigator.geolocation.getCurrentPosition(showPosition);
        Swal.close();
    } else { 
        warningMessage("Geolocation is not supported.");
    }
}

function showPosition(position) {
    document.getElementById("latitude_user").value = position.coords.latitude;
    document.getElementById("longitude_user").value = position.coords.longitude;
}

async function clickRefresh() {
    await waiting(); // dari load file global.js
    await getDataEmployee();
    await getDataOffice();
    await getLocation();
    await initMap();
    Swal.close();
}

async function getAttendanceIn() {
    await waiting(); // dari load file global.js
    await getDataEmployeeCheckIn();
    document.getElementById('img_photo_check_in').src = getUrl('assets/images/ICON/user_icon.png');
    document.getElementById('url_photo_check_in').value = '';
    await $('#modalCheckIn').modal({backdrop: 'static', keyboard: true});
    await $('#modalCheckIn').modal('show');
    await Swal.close();
}

async function getAttendanceOut(idx) {
    await waiting(); // dari load file global.js
    await getDataEmployeeCheckOut();
    document.getElementById('img_photo_check_out').src = getUrl('assets/images/ICON/user_icon.png');
    document.getElementById('url_photo_check_out').value = '';
    document.getElementById("attendance_idx").value = idx;
    await $('#modalCheckOut').modal({backdrop: 'static', keyboard: true});
    await $('#modalCheckOut').modal('show');
    await Swal.close();
}

async function getAttendanceOutPermission(idx) {
    await waiting(); // dari load file global.js
    await getDataEmployeeCheckOutPermission();
    document.getElementById('img_photo_check_out_permission').src = getUrl('assets/images/ICON/user_icon.png');
    document.getElementById('url_photo_check_out_permission').value = '';
    document.getElementById("permission_attendance_idx").value = idx;
    await $('#modalCheckOutPermission').modal({backdrop: 'static', keyboard: true});
    await $('#modalCheckOutPermission').modal('show');
    await Swal.close();
}

async function getAttendanceOvertime() {
    await waiting(); // dari load file global.js
    await $('#modalOvertime').modal({backdrop: 'static', keyboard: true});
    await $('#modalOvertime').modal('show');
    await Swal.close();
}

async function getDataEmployee() {
    try {
        await $.ajax({
            url: getUrl('Home/getDataEmployee'),
            type: "post",
            success: function (response) {
                // console.log(response);
                var json = $.parseJSON(response);
                if(json.status === true) {
                    document.getElementById('div_employee').innerHTML = json.data;
                    document.getElementById('url_photo_user').value = json.url_photo_user;
                    document.getElementById('radius_user').value = json.radius_user;
                }
                else { warningMessage(json.message); }
            },
            error: function(error) {
                console.log(error);
                errorConnectionMessage();
            }
        });
        
        
    } catch (error) {
        console.log(error);
        errorConnectionMessage();
    }
}

async function getDataOffice() {
    try {
        await $.ajax({
            url: getUrl('Home/getDataOffice'),
            type: "post",
            success: function (response) {
                // console.log(response);
                var json = $.parseJSON(response);
                if(json.status === true) {
                    document.getElementById('latitude_office').value = json.data.point_lat;
                    document.getElementById('longitude_office').value = json.data.point_long;
                }
                else { warningMessage(json.message); }
            },
            error: function(error) {
                console.log(error);
                errorConnectionMessage();
            }
        });
        
        
    } catch (error) {
        console.log(error);
        errorConnectionMessage();
    }
}

async function getDataEmployeeCheckIn() {
    try {
        await $.ajax({
            url: getUrl('Home/getDataEmployeeCheckIn'),
            type: "post",
            data: {
                "postal_code" : document.getElementById('postal_code_user').value
            },
            success: function (response) {
                // console.log(response);
                var json = $.parseJSON(response);
                if(json.status === true) {
                    document.getElementById('data_check_in').innerHTML = json.data;
                }
                else { warningMessage(json.message); }
            },
            error: function(error) {
                console.log(error);
                errorConnectionMessage();
            }
        });
        
        
    } catch (error) {
        console.log(error);
        errorConnectionMessage();
    }
}

async function getDataEmployeeCheckOut() {
    try {
        await $.ajax({
            url: getUrl('Home/getDataEmployeeCheckOut'),
            type: "post",
            data: {
                "postal_code" : document.getElementById('postal_code_user').value
            },
            success: function (response) {
                // console.log(response);
                var json = $.parseJSON(response);
                if(json.status === true) {
                    document.getElementById('data_check_out').innerHTML = json.data;
                }
                else { warningMessage(json.message); }
            },
            error: function(error) {
                console.log(error);
                errorConnectionMessage();
            }
        });
        
        
    } catch (error) {
        console.log(error);
        errorConnectionMessage();
    }
}

async function getDataEmployeeCheckOutPermission() {
    try {
        await $.ajax({
            url: getUrl('Home/getDataEmployeeCheckOutPermission'),
            type: "post",
            data: {
                "postal_code" : document.getElementById('postal_code_user').value
            },
            success: function (response) {
                // console.log(response);
                var json = $.parseJSON(response);
                if(json.status === true) {
                    document.getElementById('data_check_out_permission').innerHTML = json.data;
                }
                else { warningMessage(json.message); }
            },
            error: function(error) {
                console.log(error);
                errorConnectionMessage();
            }
        });
        
        
    } catch (error) {
        console.log(error);
        errorConnectionMessage();
    }
}

async function getPhotoAttendance(id, label) {
    await waiting(); // dari load file global.js
    document.getElementById("title_photo_attendance").innerHTML = label;
    document.getElementById("type_photo_attendance").value = id;
    // Get access to the camera
    await navigator.mediaDevices.getUserMedia({ video: true })
        .then(function(stream) {
            var video = document.getElementById('video');
            video.srcObject = stream;
            video.play();
        })
        .catch(function(err) {
            console.error('Error accessing the camera: ', err);
        });
    await $('#modalPhotoAttendance').modal({backdrop: 'static', keyboard: false});
    await $('#modalPhotoAttendance').modal('show');
    await Swal.close();
}

// Capture photo
async function capturePhoto() {
    var video = document.getElementById('video');
    var canvas = document.getElementById('canvas');
    var context = canvas.getContext('2d');

    // Draw the current frame from the video onto the canvas
    await context.drawImage(video, 0, 0, canvas.width, canvas.height);

    // Convert the canvas image to a data URL
    var imageDataURL = canvas.toDataURL('image/png');

    // You can use imageDataURL as the source of an <img> tag or send it to a server
    if(document.getElementById("type_photo_attendance").value === '1') {
        document.getElementById('img_photo_check_in').src = imageDataURL;
        document.getElementById('url_photo_check_in').value = imageDataURL;
        await stopCamera();
        await $('#modalPhotoAttendance').modal('hide');
    }
    else if(document.getElementById("type_photo_attendance").value === '2') {
        document.getElementById('img_photo_check_out').src = imageDataURL;
        document.getElementById('url_photo_check_out').value = imageDataURL;
        await stopCamera();
        await $('#modalPhotoAttendance').modal('hide');
    }
    else if(document.getElementById("type_photo_attendance").value === '3') {
        document.getElementById('img_photo_check_out_permission').src = imageDataURL;
        document.getElementById('url_photo_check_out_permission').value = imageDataURL;
        await stopCamera();
        await $('#modalPhotoAttendance').modal('hide');
    }
};

async function stopCamera() {
    var videoElement = document.getElementById('video');
    let tracks = videoElement.srcObject.getTracks();
  
    tracks.forEach(function(track) {
        track.stop();
    });

    videoElement.srcObject = null;
}

async function goRefresh() {

    await waiting(); // dari load file global.js
    window.location.replace(getUrl('Home')); 

}

async function submitCheckIn() {
    await clickRefresh();
    if (document.getElementById('url_photo_check_in').value === '') { warningMessage('Mohon foto absen datang dulu'); }
    else {
        await waiting(); // dari load file global.js
        var destinationFile;
        var fileName;
        var uploadImg;
        const d = new Date();
        let time = d.getTime();

        fileName = 'CHECK_IN_' + time + '.svg';

        destinationFile = getFolderCompany() + '/photo_attendance/' + fileName;

        try {
            uploadImg = await uploadFirebaseBase(document.getElementById('url_photo_check_in').value, destinationFile);
            if(uploadImg.status === true) {
                await $.ajax({
                    url: getUrl('Home/submitCheckIn'),
                    type: "post",
                    data: {
                        "url_photo_check_in" : uploadImg.data,
                        "filename_photo_check_in" : fileName,
                        "latitude_user" : document.getElementById('latitude_user').value,
                        "longitude_user" : document.getElementById('longitude_user').value,
                        "distance" : document.getElementById('distance').value
                    },
                    success: async function (response) {
                        // console.log(response);
                        var json = $.parseJSON(response);
                        if(json.status === false) {
                            await deleteFromFirebase(destinationFile);
                            await warningMessage(json.message);
                        }
                        else {
                            await successMessageCallBack('Yeayy', json.message, getUrl('Home'));
                        }
                    },
                    error: async function(error) {
                        console.log(error);
                        await deleteFromFirebase(destinationFile);
                        await errorConnectionMessage();
                    }
                });
            }
            else {
                warningMessage('Unggah foto gagal!');
            }
        } catch (error) {
            console.log(error);
            warningMessage('Unggah Photo gagal!');
        }   
    }
}

async function submitCheckOut() {
    await clickRefresh();
    if (document.getElementById('url_photo_check_out').value === '') { warningMessage('Mohon foto absen pulang dulu'); }
    else {
        await waiting(); // dari load file global.js
        var destinationFile;
        var fileName;
        var uploadImg;
        const d = new Date();
        let time = d.getTime();

        fileName = 'CHECK_OUT_' + time + '.svg';

        destinationFile = getFolderCompany() + '/photo_attendance/' + fileName;

        try {
            uploadImg = await uploadFirebaseBase(document.getElementById('url_photo_check_out').value, destinationFile);
            if(uploadImg.status === true) {
                await $.ajax({
                    url: getUrl('Home/submitCheckOut'),
                    type: "post",
                    data: {
                        "attendance_idx" : document.getElementById('attendance_idx').value,
                        "url_photo_check_out" : uploadImg.data,
                        "filename_photo_check_out" : fileName,
                        "latitude_user" : document.getElementById('latitude_user').value,
                        "longitude_user" : document.getElementById('longitude_user').value,
                        "distance" : document.getElementById('distance').value
                    },
                    success: function (response) {
                        // console.log(response);
                        var json = $.parseJSON(response);
                        if(json.status === false) {
                            warningMessage(json.message);
                        }
                        else {
                            successMessageCallBack('Yeayy', json.message, getUrl('Home'));
                        }
                    },
                    error: function(error) {
                        console.log(error);
                        errorConnectionMessage();
                    }
                });
            }
            else {
                warningMessage('Unggah foto gagal!');
            }
        } catch (error) {
            console.log(error);
            warningMessage('Unggah Photo gagal!');
        }   
    }
}

async function submitOvertime() {
    await waiting(); // dari load file global.js
    if (document.getElementById('reason_overtime').value === '') { warningMessage('Mohon isi alasan lembur dulu'); }
    else {
        await $.ajax({
            url: getUrl('Home/submitOvertime'),
            type: "post",
            data: {
                "attendance_idx" : document.getElementById('attendance_idx').value,
                "reason_overtime" : document.getElementById('reason_overtime').value
            },
            success: function (response) {
                // console.log(response);
                var json = $.parseJSON(response);
                if(json.status === false) {
                    warningMessage(json.message);
                }
                else {
                    successMessageCallBack('Yeayy', json.message, getUrl('Home'));
                }
            },
            error: function(error) {
                console.log(error);
                errorConnectionMessage();
            }
        }); 
    }
}

async function submitCheckOutPermission() {
    await clickRefresh();
    if (document.getElementById('url_photo_check_out_permission').value === '') { warningMessage('Mohon foto absen pulang dulu'); }
    else if (document.getElementById('reason_permission').value === '') { warningMessage('Mohon isi alasan ijin pulang dulu'); }
    else {
        await waiting(); // dari load file global.js
        var destinationFile;
        var fileName;
        var uploadImg;
        const d = new Date();
        let time = d.getTime();

        fileName = 'CHECK_OUT_' + time + '.svg';

        destinationFile = getFolderCompany() + '/photo_attendance/' + fileName;

        try {
            uploadImg = await uploadFirebaseBase(document.getElementById('url_photo_check_out_permission').value, destinationFile);
            if(uploadImg.status === true) {
                await $.ajax({
                    url: getUrl('Home/submitCheckOutPermission'),
                    type: "post",
                    data: {
                        "attendance_idx" : document.getElementById('permission_attendance_idx').value,
                        "url_photo_check_out" : uploadImg.data,
                        "filename_photo_check_out" : fileName,
                        "latitude_user" : document.getElementById('latitude_user').value,
                        "longitude_user" : document.getElementById('longitude_user').value,
                        "distance" : document.getElementById('distance').value,
                        "remarks" : document.getElementById('reason_permission').value
                    },
                    success: function (response) {
                        // console.log(response);
                        var json = $.parseJSON(response);
                        if(json.status === false) {
                            warningMessage(json.message);
                        }
                        else {
                            successMessageCallBack('Yeayy', json.message, getUrl('Home'));
                        }
                    },
                    error: function(error) {
                        console.log(error);
                        errorConnectionMessage();
                    }
                });
            }
            else {
                warningMessage('Unggah foto gagal!');
            }
        } catch (error) {
            console.log(error);
            warningMessage('Unggah Photo gagal!');
        }   
    }
}