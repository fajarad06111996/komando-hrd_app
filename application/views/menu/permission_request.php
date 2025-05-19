<style>
    /* Target the label element within .bootstrap-select */
    .bootstrap-select .dropdown-toggle .filter-option {
        font-family: 'Audiowide', sans-serif; /* Replace 'YourFont' with your desired font */
        font-size: 13px;
    }

    .text {
        font-family: 'Audiowide', sans-serif; /* Replace 'YourFont' with your desired font */
        font-size: 13px;
    }
</style>

<!-- The Modal List Data -->
<div class="modal" id="modalListData" style="z-index: 3232 !important">
    <div class="modal-dialog modal-lg">
        <div class="modal-content p-2">
        
            <!-- Modal Header -->
            <div class="modal-header border border-left-0 border-top-0 border-right-0">
                <!-- <h4 class="modal-title"><b id="lbl_list_title"></b></h4> -->
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
        
            <!-- Modal body -->
            <div class="modal-body text-left border border-left-0 border-top-0 border-right-0">
                <label class="mb-2">Silahkan pilih <b id="lbl_list_label"></b> : </label><br>
                <input type="text" class="form-control font-size-sm mb-2" id="txt_search_list_select" onkeyup="searchListData()" placeholder="Ketik pencarian.." title="Type in a data">
                <div id="listDataSelect" style="height: calc(100vh - 126px); overflow-y: scroll;"></div>
            </div>
        
            <!-- Modal footer -->
            <div class="p-3 text-center">
                <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fas fa-arrow-alt-circle-left text-sm mr-2"></i> BATAL</button>
            </div>
        
        </div>
    </div>
</div>

<!-- The Modal Entry Permission -->
<div class="modal" id="modalEntryPermission" style="overflow-y: scroll;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content p-2">
            <form id="frmEntry" method="POST" enctype="multipart/form-data">

            <!-- Modal Header -->
            <div class="modal-header border border-left-0 border-top-0 border-right-0">
                <h4 class="modal-title">FORM IZIN TIDAK MASUK KERJA</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body border border-left-0 border-top-0 border-right-0">
                <input type="hidden" id="entry_type" name="entry_type">
                <input type="hidden" id="idx" name="idx">
                <div class="form-row mb-1 align-items-center bg-secondary">
                    <i class="control-label col-sm-4 mb-0 bg-secondary font-size-sm pl-2 pt-1">Nomor Izin</i>
                    <div class="col-sm-8 input-group bg-secondary p-1">
                        <input type="text" id="permission_no" class="form-control box_pointed font-size-sm" placeholder="AUTO" readonly>
                    </div>
                </div>
                <div class="form-row mb-1 align-items-center bg-secondary">
                    <i class="control-label col-sm-4 mb-0 bg-secondary font-size-sm pl-2 pt-1">Dari Tanggal (Wajib diisi)</i>
                    <div class="col-sm-8 input-group bg-secondary p-1">
                        <input type="text" id="from_date" name="from_date" class="form-control datepicker box_pointed font-size-sm bg-white" style="z-index: 1 !important;" placeholder="Pilih periode tanggal awal" readonly>
                    </div>
                </div>
                <div class="form-row mb-1 align-items-center bg-secondary">
                    <i class="control-label col-sm-4 mb-0 bg-secondary font-size-sm pl-2 pt-1">Sampai Tanggal (Wajib diisi)</i>
                    <div class="col-sm-8 input-group bg-secondary p-1">
                        <input type="text" id="to_date" name="to_date" class="form-control datepicker box_pointed font-size-sm bg-white" style="z-index: 1 !important;" placeholder="Pilih periode tanggal akhir" readonly>
                    </div>
                </div>
                <div class="form-row mb-1 align-items-center bg-secondary">
                    <i class="control-label col-sm-4 mb-0 bg-secondary font-size-sm pl-2 pt-1">Alasan (Wajib diisi)</i>
                    <div class="col-sm-8 input-group bg-secondary p-1">
                        <input type="hidden" id="permission_type" name="permission_type">
                        <input type="text" id="permission_type_name" name="permission_type_name" class="form-control box_pointed font-size-sm bg-white" placeholder="Pilih alasan tidak masuk kerja" readonly>
                        <button type="button" class="btn btn-light font-size-sm" onclick="getListData('permission_type', 'permission_type_name', 'Alasan Tidak Masuk Kerja')"><i class="fas fa-bars"></i></button>
                    </div>
                </div>
                <div class="form-row mb-1 align-items-center bg-secondary">
                    <i class="control-label col-sm-4 mb-0 bg-secondary font-size-sm pl-2 pt-1">Keterangan</i>
                    <div class="col-sm-8 bg-secondary p-1">          
                        <textarea id="remarks" name="remarks" class="box_pointed form-control font-size-sm" rows="2" placeholder="Ketik keterangan"></textarea>
                    </div>
                </div>
                <div class="form-row mb-1 align-items-center bg-secondary">
                    <i class="control-label col-sm-4 mb-0 bg-secondary font-size-sm pl-2 pt-1">Lampiran Dokumen</i>
                    <div class="col-sm-8 input-group bg-secondary px-1">
                        <div class="custom-file">
                            <input type="hidden" id="url_attachment" name="url_attachment">
                            <input type="hidden" id="filename_attachment" name="filename_attachment">
                            <input type="file" class="custom-file-input font-size-sm" id="customFile" name="filename">
                            <label id="lbl_attachment" class="custom-file-label font-size-sm text-primary" for="customFile">Pilih lampiran dokumen</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="p-3 text-center">
                <button type="submit" class="btn btn-success btn-rounded w-100 mb-2"><i class="fas fa-check"></i> Submit</button>
                <button type="button" class="btn btn-danger btn-rounded w-100 mb-2" data-dismiss="modal"><i class="fas fa-reply"></i> Tutup</button>
            </div>

            </form>
        </div>
    </div>
</div>

<div class="fixed-top w-100 bg-white pt-2" style="z-index: 1000 !important; top: 60px;">
    <div class="input-group px-2 align-items-center">
        <span class="text-blue ml-2 mr-2" style="font-size: 13px; font-family: 'Audiowide', sans-serif;">IZIN TIDAK MASUK KERJA</span>
        <input type="text" class="form-control font-size-sm" id="txt_search_list" onkeyup="searchListData()" placeholder="Ketik pencarian..." title="Type in a data">
    </div>
    <hr>
    <div class="input-group px-2 mt-2 align-items-center">
        <select class="selectpicker form-control mr-2 font-size-sm" data-live-search="true" id="month_period" onchange="getSelectDataPermission(this.value)">
            <option value="01">JANUARI</option>
            <option value="02">FEBRUARI</option>
            <option value="03">MARET</option>
            <option value="04">APRIL</option>
            <option value="05">MEI</option>
            <option value="06">JUNI</option>
            <option value="07">JULI</option>
            <option value="08">AGUSTUS</option>
            <option value="09">SEPTEMBER</option>
            <option value="10">OKTOBER</option>
            <option value="11">NOVEMBER</option>
            <option value="12">DESEMBER</option>
        </select>
        <input type="text" id="year_period" class="text-blue mb-0 mr-2 form-control" style="font-size: 13px; font-family: 'Audiowide', sans-serif;" value="<?= date('Y'); ?>" readonly>
        <button type="button" class="btn bg-blue text-white" onclick="getAddPermission()" style="font-size: 13px;"><i class="fas fa-plus"></i></button>
    </div>
    <hr class="mb-0 pb-0">
</div>	

<!-- untuk tampil detail ijin -->
<div id="div_permission" class="px-2 pb-0 pt-3 bg-white" style="margin-top: 195px">
    <img src='<?= base_url() ?>assets/images/ICON/loading_wide_blue.gif' style='width: 100%;'>
</div>

<script src="<?= base_url(); ?>assets/js/menu/permission_request.js?v=1<?= time() ?>"></script>