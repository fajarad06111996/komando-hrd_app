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

<div class="fixed-top w-100 bg-white pt-2" style="z-index: 1000 !important; top: 60px;">
    <div class="input-group px-2 align-items-center">
        <span class="text-blue ml-2 mr-2" style="font-size: 13px; font-family: 'Audiowide', sans-serif;">RIWAYAT ABSENSI</span>
        <input type="text" class="form-control font-size-sm" id="txt_search_list" onkeyup="searchListData()" placeholder="Ketik pencarian..." title="Type in a data">
    </div>
    <hr>

    <div class="input-group px-2 mt-2 align-items-center">
        <select class="selectpicker form-control mr-2 font-size-sm" data-live-search="true" id="month_attendance" onchange="getSelectDataAttendance(this.value)">
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
        <input type="text" id="year_attendance" class="text-blue mb-0 form-control" style="font-size: 13px; font-family: 'Audiowide', sans-serif;" value="<?= date('Y'); ?>" readonly>
    </div>
    <hr class="mb-0 pb-0">
</div>	
<div id="div_attendance" class="px-2 pb-0 pt-3 bg-white" style="margin-top: 195px">
    <img src='<?= base_url() ?>assets/images/ICON/loading_wide_blue.gif' style='width: 100%;'>
</div>

<script src="<?= base_url(); ?>assets/js/menu/history_attendance.js?v=1"></script>