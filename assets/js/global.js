// var BASE_URL = 'https://apphrd.ekomando.com/';
// var BASE_URL = 'http://localhost/apphrd/';
var BASE_URL = 'http://localhost/apphrd/';

function getUrl(url) {
    return BASE_URL.concat(url);
}

function getFolderCompany() {
    return 'ekomando_hrd';
}

function formatCurr(number) {
    const formatter = Intl.NumberFormat('en-US');
    return formatter.format(number);
}

// var x = setInterval(function() { 
            //     updateValidOrderTrucking(); 
            //     updateConfirmOrderTrucking();
            // }, 1000);
function validasiEmail(email_id) {
    var atps = email_id.indexOf("@");
    var dots = email_id.lastIndexOf(".");
    if (atps < 1 || dots < atps + 2 || dots + 2 >= email_id.length) { return false; } 
    else { return true; }
}
            
function getNumberPage(numberPage, getUrl, panelId, keyword) {
    waiting();
    $.ajax({
        url: getUrl + numberPage,
        type: "post",
        data: {
            'txt_search' : keyword
        },
        success: function (response) {
            var json = $.parseJSON(response);
            document.getElementById(panelId).innerHTML = json;
            Swal.close();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            errorConnectionMessage();
        }
    });
}
            
function showImage(titleImage, urlImage) {
    Swal.fire({
        title: titleImage,
        text: "",
        html: "<img src='" + urlImage + "' style='width:90%;'>",
        allowOutsideClick: false
    })
}

async function uploadFirebaseBase(sourceFile, fileName) {
    // Your Firebase configuration
    var firebaseConfig = {
        apiKey: "AIzaSyCoR8XQr6YwMLTyPq8ABc5FppBTXkc60BE",
        // authDomain: "first-discovery-401904.firebaseapp.com",
        projectId: "first-discovery-401904",
        storageBucket: "first-discovery-401904.appspot.com",
        messagingSenderId: "213831062450",
        appId: "1:213831062450:web:289159c02cf68fd4378aab",
        measurementId: "G-DWM1NGGK9F"
    };
    
    if (!firebase.apps.length) {
        firebase.initializeApp(firebaseConfig);
    } else {
        firebase.app(); // if already initialized, use that one
    }

    var result = {};

    // Base64 string of the image
    var base64Image = sourceFile; //"data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD/4QA...";

    // Convert the base64 to a Blob object
    var byteCharacters = atob(base64Image.split(',')[1]);
    var byteNumbers = new Array(byteCharacters.length);
    for (var i = 0; i < byteCharacters.length; i++) {
        byteNumbers[i] = byteCharacters.charCodeAt(i);
    }
    var byteArray = new Uint8Array(byteNumbers);
    var blob = new Blob([byteArray], { type: 'image/jpeg' });

    // Get a reference to the storage service
    var storage = firebase.storage();

    // Create a storage reference
    var storageRef = storage.ref();

    // Upload the blob to Firebase Storage
    var uploadTask = storageRef.child(fileName).put(blob);

    // Listen for state changes, errors, and completion of the upload.
    return new Promise(function(resolve, reject) {
        uploadTask.on('state_changed',
            function(snapshot){
                // Observe state change events such as progress, pause, and resume
            }, 
            function(error) {
                // Handle unsuccessful uploads
                // console.error('Error uploading image:', error);
                switch (error.code) {
                    case 'storage/unauthorized':
                        console.log(error);
                        console.log('storage/unauthorized');
                        // User doesn't have permission to access the object
                        result = {
                            status: false,
                            data: 'storage/unauthorized',
                            error: error
                        }
                    break;
                    case 'storage/canceled':
                        console.log(error);
                        console.log('storage/canceled');
                        // User canceled the upload
                        result = {
                            status: false,
                            data: 'storage/canceled',
                            error: error
                        }
                    break;
                    case 'storage/unknown':
                        console.log(error);
                        console.log('storage/unknown');
                        // Unknown error occurred, inspect error.serverResponse
                        result = {
                            status: false,
                            data: 'storage/unknown',
                            error: error
                        }
                    break;
                    default:
                        console.log(error);
                        console.log('storage/other');
                        // Unknown error occurred, inspect error.serverResponse
                        result = {
                            status: false,
                            data: 'storage/other',
                            error: error
                        }
                    break;
                }
                reject(result);
            }, 
            function() {
                // Handle successful uploads on complete
                uploadTask.snapshot.ref.getDownloadURL().then(function(downloadURL) {
                    // console.log('Image uploaded successfully. Download URL:', downloadURL);
                    result = {
                        status: true,
                        data: downloadURL,
                        error: false
                    }
                    resolve(result);
                });
            }
        );
    });
}

async function uploadFirebase(sourceFile, fileName) {
    var firebaseConfig = {
        apiKey: "AIzaSyCoR8XQr6YwMLTyPq8ABc5FppBTXkc60BE",
        // authDomain: "first-discovery-401904.firebaseapp.com",
        projectId: "first-discovery-401904",
        storageBucket: "first-discovery-401904.appspot.com",
        messagingSenderId: "213831062450",
        appId: "1:213831062450:web:289159c02cf68fd4378aab",
        measurementId: "G-DWM1NGGK9F"
    };

    // Your web app's Firebase configuration
    // For Firebase JS SDK v7.20.0 and later, measurementId is optional
    // Initialize Firebase
    if (!firebase.apps.length) {
        firebase.initializeApp(firebaseConfig);
    } else {
        firebase.app(); // if already initialized, use that one
    }

    var result = {};

    var storageRef 	= firebase.storage().ref(fileName);
    var task 		= storageRef.put(sourceFile);

    // Listen for state changes, errors, and completion of the upload.
    return new Promise(function(resolve, reject) {
        task.on(
            firebase.storage.TaskEvent.STATE_CHANGED,
            snapshot => {
                var progress = (snapshot.bytesTransferred / snapshot.totalBytes) * 100;
                switch (snapshot.state) {
                    case firebase.storage.TaskState.PAUSED: // or 'paused'
                    console.log('Upload is paused');
                    result = {
                        status: 'paused',
                        data: '',
                        error: false
                    }
                    break;
                    case firebase.storage.TaskState.RUNNING: // or 'running'
                    // console.log('Upload is running');
                    // result = {
                    //     status: 'running',
                    //     data: '',
                    //     error: false
                    // }
                    break;
                }
            },
            error => { 
                switch (error.code) {
                    case 'storage/unauthorized':
                        console.log(error);
                        console.log('storage/unauthorized');
                        // User doesn't have permission to access the object
                        result = {
                            status: false,
                            data: 'storage/unauthorized',
                            error: error
                        }
                    break;
                    case 'storage/canceled':
                        console.log(error);
                        console.log('storage/canceled');
                        // User canceled the upload
                        result = {
                            status: false,
                            data: 'storage/canceled',
                            error: error
                        }
                    break;
                    case 'storage/unknown':
                        console.log(error);
                        console.log('storage/unknown');
                        // Unknown error occurred, inspect error.serverResponse
                        result = {
                            status: false,
                            data: 'storage/unknown',
                            error: error
                        }
                    break;
                    default:
                        console.log(error);
                        console.log('storage/other');
                        // Unknown error occurred, inspect error.serverResponse
                        result = {
                            status: false,
                            data: 'storage/other',
                            error: error
                        }
                    break;
                }
                reject(result);
                // return result;
            },
            () => {
                task.snapshot.ref
                .getDownloadURL()
                .then(downloadURL => {
                    result = {
                        status: true,
                        data: downloadURL,
                        error: false
                    }
                    resolve(result);
                    // return result;
                    
                });
            }
        );
    });
}

async function deleteFromFirebase(destinationFile) {
    var firebaseConfig = {
        apiKey: "AIzaSyCoR8XQr6YwMLTyPq8ABc5FppBTXkc60BE",
        // authDomain: "first-discovery-401904.firebaseapp.com",
        projectId: "first-discovery-401904",
        storageBucket: "first-discovery-401904.appspot.com",
        messagingSenderId: "213831062450",
        appId: "1:213831062450:web:289159c02cf68fd4378aab",
        measurementId: "G-DWM1NGGK9F"
    };

    // Your web app's Firebase configuration
    // For Firebase JS SDK v7.20.0 and later, measurementId is optional
    // Initialize Firebase
    if (!firebase.apps.length) {
        firebase.initializeApp(firebaseConfig);
    } else {
        firebase.app(); // if already initialized, use that one
    }

    var storageRef 	= firebase.storage().ref();
    var fileRef     = storageRef.child(destinationFile);
    // Delete the file
    fileRef.delete().then(function() {
        // File deleted successfully
        console.log('File deleted successfully');
    }).catch(function(error) {
        // Uh-oh, an error occurred!
        console.error('Error deleting file:', error);
    });
}
            
function getChangePassword() {
    waiting();
    $('#modalChangePassword').modal({backdrop: 'static', keyboard: false});
    $('#modalChangePassword').modal('show');
    Swal.close();
}
            
function submitChangePassword() {
    waiting();
    if(document.getElementById('old_password').value == '') {
        warningMessage('Mohon isi sandi lama dulu!');
    }
    else if(document.getElementById('new_password').value == '') {
        warningMessage('Mohon isi sandi baru dulu!');
    }
    else if(document.getElementById('confirm_password').value == '') {
        warningMessage('Mohon isi konfirmasi sandi dulu!');
    }
    else if(document.getElementById('new_password').value !== document.getElementById('confirm_password').value) {
        warningMessage('Konfirmasi sandi baru tidak sesuai!');
    }
    else {
        $.ajax({
            url: getUrl('Auth/submitChangePassword'),
            type: "post",
            data: {
                'old_password' : document.getElementById('old_password').value,
                'new_password' : document.getElementById('new_password').value,
                'confirm_password' : document.getElementById('confirm_password').value,
            },
            success: function (response) {
                var json = $.parseJSON(response);
                if(json.status === false) {
                    warningMessage(json.message);
                }
                else {
                    successMessageCallBack('Yeayy', json.message, getUrl('Home'));
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                errorConnectionMessage();
            }
        });
    }
}
            
function backNumber(textNumber) {
                // if (textNumber.match(/^(?!0\.00)\d{1,3}(,\d{3})*(\.\d\d)?$/gm)) { return textNumber.replace(',', ''); }
                // else { 
                //     var resultReplace = textNumber.replace('.', '');
                //     return resultReplace.replace(',', ''); 
                // }
                
    return textNumber.replace(/,/g, ''); 
}
        
function waiting() {
    Swal.fire({
        imageUrl: getUrl('assets/images/ICON/loading_blue.gif'),
        imageHeight: 100,
        title: 'Mohon tunggu...',
        text: '',
        showConfirmButton: false,
        allowOutsideClick: false
    })
}
                        
function successMessageCallBack(title, message, callBack) {
    Swal.fire({
        imageUrl: getUrl('assets/images/ICON/success_icon.gif'),
        imageHeight: 100,
        title: title,
        text: message,
        allowOutsideClick: false
    }).then(function() {
        waiting();
        window.location.replace(callBack); 
    });
}

function successMessageCloseModal(title, message, idModal) {
    Swal.fire({
        imageUrl: getUrl('assets/images/ICON/success_icon.gif'),
        imageHeight: 100,
        title: title,
        text: message,
        allowOutsideClick: false
    }).then(function() {
        $('#' + idModal).modal('hide');
    });
}
                            
function warningMessage(message) {
    Swal.fire({
        imageUrl: getUrl('assets/images/ICON/warning_icon.png'),
        imageHeight: 150,
        title: 'Maaf',
        text: message,
        allowOutsideClick: false
    })
}
                            
function errorConnectionMessage() {
    Swal.fire({
        imageUrl: getUrl('assets/images/ICON/times_icon.png'),
        imageHeight: 100,
        title: 'Sistem Error',
        text: 'Mohon hubungi tim IT',
        allowOutsideClick: false
    })
}
            
function log_out() { 
    waiting();
    window.location.replace(getUrl('Auth/logout'));
}