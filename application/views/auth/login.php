<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Audiowide">

<form id="frmLogin" method="POST" enctype="multipart/form-data">
    <div class="text-center bg-white p-5">
        <div class="text-center mb-0"><img src="<?= base_url(); ?>assets/images/LOGO/logo_komando.png" class="w-100"></div>
        <label class="text-blue mt-2 mb-0" style="font-size: 30px; font-family: 'Audiowide', sans-serif;">ABSENSI</label><br>
        <label class="text-red mb-3">LOGIN</label>
        <input type="text" class="form-control form-control-lg font-size-sm text-center" id="user_id" name="user_id" placeholder="USER ID">
		<button type="submit" class="btn bg-blue w-100 mt-4 text-white">LANJUT <i class="fas fa-angle-double-right"></i></button>
    </div>
</form>

<form id="frmLoginPassword" method="POST" enctype="multipart/form-data" style="display: none;">
    <div class="text-center bg-white p-5">
        <div class="text-center mb-0"><img src="<?= base_url(); ?>assets/images/LOGO/logo_komando.png" class="w-100"></div>
        <label class="text-blue mt-2 mb-0" style="font-size: 30px; font-family: 'Audiowide', sans-serif;">ABSENSI</label><br>
        <label class="text-red mb-3">KETIK PASSWORD</label>
        <input type="hidden" id="token_apps_login" name="token_apps_login">
        <input type="text" class="form-control form-control-lg font-size-sm text-center" id="user_id_login" name="user_id_login" placeholder="USER ID" readonly>
        <input type="password" class="form-control form-control-lg font-size-sm text-center mt-2" id="password_login" name="password_login" placeholder="PASSWORD">
		<button type="submit" class="btn bg-blue w-100 mt-4 text-white">LANJUT <i class="fas fa-angle-double-right"></i></button>
    </div>
</form>

<form id="frmCreatePassword" method="POST" enctype="multipart/form-data" style="display: none;">
    <div class="box_shadow text-center bg-white p-5 border-bold-double-blue">
        <div class="text-center mb-0"><img src="<?= base_url(); ?>assets/images/LOGO/logo_komando.png" class="w-100"></div>
        <label class="text-blue mt-2 mb-0" style="font-size: 30px; font-family: 'Audiowide', sans-serif;">ABSENSIA</label><br>
        <label class="text-red mb-3">BUAT PASSWORD BARU</label>
        <input type="hidden" id="token_apps_newpass" name="token_apps_newpass">
        <input type="text" class="form-control form-control-lg font-size-sm text-center" id="create_password_user_id" name="create_password_user_id" placeholder="USER ID" readonly>
        <input type="password" class="form-control form-control-lg font-size-sm text-center mt-2" id="create_password_new" name="create_password_new" placeholder="KETIK PASSWORD BARU">
        <input type="password" class="form-control form-control-lg font-size-sm text-center mt-2" id="create_password_confirm" name="create_password_confirm" placeholder="KONFIRMASI PASSWORD">
		<button type="submit" class="btn bg-blue w-100 mt-4 text-white">LANJUT <i class="fas fa-angle-double-right"></i></button>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function(){
        $('#frmLogin').submit(function(event){ 
            event.preventDefault(); 
            if(document.getElementById("user_id").value == '') { warningMessage('Please enter user id first!'); }
            else { 
                waiting();
                document.getElementById("token_apps_newpass").value = '';
                document.getElementById("token_apps_login").value = '';
                $.ajax({
                    url: "<?= base_url('Auth/login') ?>",
                    type: "post",
                    data:new FormData(this),
                    processData:false,
                    contentType:false,
                    cache:false,
                    success: function (response) {
                        console.log(response);
                        var json = $.parseJSON(response);
                        if(json.status === false) { warningMessage(json.message); }
                        else { 
                            // console.log(json.data);
                            document.getElementById("frmLogin").style.display = "none";
                            if(json.data.status_password == '0') { 
                                document.getElementById("create_password_user_id").value = json.data.user_id;
                                document.getElementById("token_apps_newpass").value = $('input[name=token_apps]').val();
                                document.getElementById("frmCreatePassword").style.display = "block"; 
                            }
                            else if(json.data.status_password == '1') { 
                                document.getElementById("user_id_login").value = json.data.user_id;
                                document.getElementById("token_apps_login").value = $('input[name=token_apps]').val();
                                document.getElementById("frmLoginPassword").style.display = "block"; 
                            }
                            Swal.close();
                        }
                    },
                    error: function(error) {
                        console.log(error);
                        errorConnectionMessage();
                    }
                });
                
            }
        });
        
        $('#frmLoginPassword').submit(function(event){ 
            event.preventDefault(); 
            if(document.getElementById("user_id_login").value == '') { warningMessage('Please enter user id first!'); }
            else if(document.getElementById("password_login").value == '') { warningMessage('Please enter password first!'); }
            else { 
                waiting();
                $.ajax({
                    url: "<?= base_url('Auth/loginPassword') ?>",
                    type: "post",
                    data:new FormData(this),
                    processData:false,
                    contentType:false,
                    cache:false,
                    success: function (response) {
                        console.log(response);
                        var json = $.parseJSON(response);
                        if(json.status === false) { warningMessage(json.message); }
                        else { successMessageCallBack("Yeayy", json.message, "<?= base_url() ?>"); }
                    },
                    error: function(error) {
                        console.log(error);
                        errorConnectionMessage();
                    }
                });
                
            }
        });
        
        $('#frmCreatePassword').submit(function(event){ 
            event.preventDefault(); 
            if(document.getElementById("create_password_user_id").value == '') { warningMessage('Please enter user id first!'); }
            else if(document.getElementById("create_password_new").value == '') { warningMessage('Please enter new password first!'); }
            else if(document.getElementById("create_password_confirm").value == '') { warningMessage('Please enter confirm password first!'); }
            else if(document.getElementById("create_password_new").value !== document.getElementById("create_password_confirm").value) { warningMessage('Confirm Password does not match!'); }
            else { 
                waiting();
                $.ajax({
                    url: "<?= base_url('Auth/createNewPassword') ?>",
                    type: "post",
                    data:new FormData(this),
                    processData:false,
                    contentType:false,
                    cache:false,
                    success: function (response) {
                        console.log(response);
                        var json = $.parseJSON(response);
                        if(json.status === false) { warningMessage(json.message); }
                        else { successMessageCallBack("Yeayy", json.message, "<?= base_url() ?>"); }
                    },
                    error: function(error) {
                        console.log(error);
                        errorConnectionMessage();
                    }
                });
                
            }
        });
    });
</script>