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
        <span class="text-blue ml-2 mr-2" style="font-size: 13px; font-family: 'Audiowide', sans-serif;">KALENDER EVENT</span>
        <input type="text" class="form-control font-size-sm" id="txt_search_list" onkeyup="searchListData()" placeholder="Ketik pencarian..." title="Type in a data">
    </div>
    <hr>
    <div class="input-group px-2 mt-2 align-items-center">
        <span class="text-blue ml-2 mr-2" style="font-size: 13px; font-family: 'Audiowide', sans-serif;">TAHUN</span>
        <input type="number" id="year_calendar" class="text-blue text-center mb-0 form-control" style="font-size: 13px; font-family: 'Audiowide', sans-serif;" value="<?= date('Y'); ?>">
        <button type="button" class="btn bg-blue text-white btn-rounded ml-2" onclick="getSearchDataCalendar()"><i class="fas fa-search"></i> Cari</button>
    </div>
    <hr class="mb-0 pb-0">
</div>	
<div id="div_calendar" class="px-2 pb-0 pt-3 bg-white" style="margin-top: 195px">
    <img src='<?= base_url() ?>assets/images/ICON/loading_wide_blue.gif' style='width: 100%;'>
</div>

<script src="<?= base_url(); ?>assets/js/menu/calendar_event.js?v=1"></script>