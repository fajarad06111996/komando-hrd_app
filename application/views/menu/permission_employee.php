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

<!-- load modal -->
<?php $this->load->view('_modal/modalPermissionEmployee'); ?>

<div class="fixed-top w-100 bg-white pt-2" style="z-index: 1000 !important; top: 60px;">
    <div class="input-group px-2 align-items-center">
        <span class="text-blue ml-2 mr-2" style="font-size: 13px; font-family: 'Audiowide', sans-serif;">KARYAWAN IZIN KERJA </span>
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
        <!-- <button type="button" class="btn bg-blue text-white" onclick="getAddPermission()" style="font-size: 13px;"><i class="fas fa-plus"></i></button> -->
    </div>
    <hr class="mb-0 pb-0">
</div>	

<!-- untuk tampil detail ijin -->
<div id="div_permission" class="px-2 pb-0 pt-3 bg-white" style="margin-top: 195px">
    <img src='<?= base_url() ?>assets/images/ICON/loading_wide_blue.gif' style='width: 100%;'>
</div>

<script src="<?= base_url(); ?>assets/js/menu/permission_employee.js?v=1<?= time() ?>"></script>