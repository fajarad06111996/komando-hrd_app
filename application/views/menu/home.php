
<style>
    .price-tag-office {
        background-color: #151B54;
        border-radius: 8px;
        color: #FFFFFF;
        font-size: 14px;
        padding: 5px 5px;
        position: relative;
    }

    .price-tag-office::after {
        content: "";
        position: absolute;
        left: 50%;
        top: 100%;
        transform: translate(-50%, 0);
        width: 0;
        height: 0;
        border-left: 8px solid transparent;
        border-right: 8px solid transparent;
        border-top: 8px solid #151B54;
    }

    .price-tag-user {
        background-color: #760000;
        border-radius: 8px;
        color: #FFFFFF;
        font-size: 14px;
        padding: 5px 5px;
        position: relative;
    }

    .price-tag-user::after {
        content: "";
        position: absolute;
        left: 50%;
        top: 100%;
        transform: translate(-50%, 0);
        width: 0;
        height: 0;
        border-left: 8px solid transparent;
        border-right: 8px solid transparent;
        border-top: 8px solid #760000;
    }
    
</style>

<!-- The Modal Photo Attendance -->
<div class="modal" id="modalPhotoAttendance" style="z-index: 3232 !important">
    <div class="modal-dialog modal-xl">
        <div class="modal-content p-2">

            <!-- Modal Header -->
            <div class="modal-header border border-left-0 border-top-0 border-right-0 text-center">
                <h4 id="title_photo_attendance" class="modal-title"></h4>
                <input type="hidden" id="type_photo_attendance">
                <button type="button" class="close" data-dismiss="modal" onclick="stopCamera()">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body text-center border border-left-0 border-top-0 border-right-0">
                <video id="video" width="100%" height="280" class="mt-1 img-circle"  autoplay></video>
                <canvas id="canvas" width="280" height="280" style="display:none;"></canvas><br>
                <button type="button" class="btn btn-primary btn-rounded my-3" onclick="capturePhoto()"><i class="fas fa-camera"></i> Ambil Foto</button>

            </div>

            <!-- Modal footer -->
            <div class="p-3 text-center">
                <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="stopCamera()"><i class="fas fa-reply"></i> Tutup</button>
            </div>

        </div>
    </div>
</div>

<!-- The Modal Check In -->
<div class="modal" id="modalCheckIn" style="overflow-y: scroll;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content p-2">

            <!-- Modal Header -->
            <div class="modal-header border border-left-0 border-top-0 border-right-0">
                <h4 class="modal-title">ABSEN DATANG</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body text-center border border-left-0 border-top-0 border-right-0">
                <img id="img_photo_check_in" src="<?= base_url(); ?>assets/images/ICON/user_icon.png" height="280" width="280" class="mt-1 img-circle"><br>
                <button type="button" class="btn btn-primary btn-rounded my-3" onclick="getPhotoAttendance('1', 'FOTO ABSEN DATANG')"><i class="fas fa-camera"></i> Foto Absensi</button>
                <input type="hidden" id="url_photo_check_in">
                <div id="data_check_in">
                    
                </div>
            </div>

            <!-- Modal footer -->
            <div class="p-3 text-center">
                <button type="button" class="btn btn-success btn-rounded w-100 mb-2" onclick="submitCheckIn()"><i class="fas fa-check"></i> Submit</button>
                <button type="button" class="btn btn-danger btn-rounded w-100 mb-2" data-dismiss="modal"><i class="fas fa-reply"></i> Tutup</button>
            </div>

        </div>
    </div>
</div>

<!-- The Modal Check Out -->
<div class="modal" id="modalCheckOut" style="overflow-y: scroll;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content p-2">

            <!-- Modal Header -->
            <div class="modal-header border border-left-0 border-top-0 border-right-0">
                <h4 id="title_attendance" class="modal-title">ABSEN PULANG</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body text-center border border-left-0 border-top-0 border-right-0">
                <img id="img_photo_check_out" src="<?= base_url(); ?>assets/images/ICON/user_icon.png" width="280" height="280" class="mt-1 img-circle"><br>
                <button type="button" class="btn btn-primary btn-rounded my-3" onclick="getPhotoAttendance('2', 'FOTO ABSEN PULANG')"><i class="fas fa-camera"></i> Foto Absensi</button>
                <input type="hidden" id="attendance_idx">
                <input type="hidden" id="url_photo_check_out">
                <div id="data_check_out">
                    
                </div>
            </div>

            <!-- Modal footer -->
            <div class="p-3 text-center">
                <button type="button" class="btn btn-success btn-rounded w-100 mb-2" onclick="submitCheckOut()"><i class="fas fa-check"></i> Pulang</button>
                <button type="button" class="btn btn-danger btn-rounded w-100 mb-2" data-dismiss="modal"><i class="fas fa-reply"></i> Tutup</button>
            </div>

        </div>
    </div>
</div>

<!-- The Modal Check Out Permission -->
<div class="modal" id="modalCheckOutPermission" style="overflow-y: scroll;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content p-2">

            <!-- Modal Header -->
            <div class="modal-header border border-left-0 border-top-0 border-right-0">
                <h4 id="title_attendance" class="modal-title">IJIN PULANG AWAL</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body text-center border border-left-0 border-top-0 border-right-0">
                <img id="img_photo_check_out_permission" src="<?= base_url(); ?>assets/images/ICON/user_icon.png" width="280" height="280" class="mt-1 img-circle"><br>
                <button type="button" class="btn btn-primary btn-rounded my-3" onclick="getPhotoAttendance('3', 'FOTO IJIN PULANG AWAL')"><i class="fas fa-camera"></i> Foto Absensi</button>
                <input type="hidden" id="permission_attendance_idx">
                <input type="hidden" id="url_photo_check_out_permission">
                <div id="data_check_out_permission">
                    
                </div>
            </div>

            <!-- Modal footer -->
            <div class="p-3 text-center">
                <button type="button" class="btn btn-success btn-rounded w-100 mb-2" onclick="submitCheckOutPermission()"><i class="fas fa-check"></i> Submit</button>
                <button type="button" class="btn btn-danger btn-rounded w-100 mb-2" data-dismiss="modal"><i class="fas fa-reply"></i> Tutup</button>
            </div>

        </div>
    </div>
</div>

<!-- The Modal Overtime -->
<div class="modal" id="modalOvertime" style="overflow-y: scroll;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content p-2">

            <!-- Modal Header -->
            <div class="modal-header border border-left-0 border-top-0 border-right-0">
                <h4 id="title_attendance" class="modal-title">FORM LEMBUR</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body text-center border border-left-0 border-top-0 border-right-0">
            Deskripsi&nbsp;<b class="text-red">*</b> : <br>
            <textarea id="reason_overtime" class="form-control font-size-sm w-100" placeholder="Ketik deskripsi lembur"></textarea>
            </div>

            <!-- Modal footer -->
            <div class="p-3 text-center">
                <button type="button" class="btn btn-success btn-rounded w-100 mb-2" onclick="submitOvertime()"><i class="fas fa-check"></i> Submit</button>
                <button type="button" class="btn btn-danger btn-rounded w-100 mb-2" data-dismiss="modal"><i class="fas fa-reply"></i> Tutup</button>
            </div>

        </div>
    </div>
</div>

<!-- untuk load google map -->
<div id="map_canvas" class="w-100 h-100 my-0"></div>

<input type="hidden" id="latitude_office" value="-6.237481">
<input type="hidden" id="longitude_office" value="106.888346">

<input type="hidden" id="latitude_user" value="">
<input type="hidden" id="longitude_user" value="">

<input type="hidden" id="postal_code_user" value="">

<input type="hidden" id="url_photo_user" value="">
<input type="hidden" id="radius_user" value="">

<input type="hidden" id="distance" value="0">
		
<div class="align-items-center fixed-bottom m-3" style="z-index: 999 !important;">
    <div class="w-100 text-right">
        <label class="bg-white mb-1 ml-2 px-2 py-1 text-sm pointer-hand border-blue box_rounded" onclick="clickRefresh()"><i class="fas fa-sync-alt"></i> REFRESH</label>
    </div>
    <div id="div_employee" class="px-3 pb-0 pt-3 bg-white border-blue box_rounded">
        <img src='<?= base_url() ?>assets/images/ICON/loading_wide_blue.gif' style='width: 80%; margin: 10px'>
    </div>
</div>

<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCoR8XQr6YwMLTyPq8ABc5FppBTXkc60BE&region=ID&language=id&callback=initMap&loading=async&libraries=marker" async defer></script> -->
<script src="<?= base_url(); ?>assets/js/menu/home.js?v=10"></script>