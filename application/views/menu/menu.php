<style>
    @media (min-width: 992px) {
        .sidebar-mini.sidebar-o #sidebar:hover {
            padding: 5px;
            width: 280px;
        }
    }
</style>
<nav id="sidebar" aria-label="Main Navigation" class="bg-blue">
    <!-- Side Header -->
    
    <!-- END Side Header -->

    <!-- Side Navigation -->
    <div class="content-side content-side-full pt-0 mt-0" style="width: 100%; z-index: 1">
        <ul class="nav-main">
								
            <li class="nav-main-heading px-0">
                <div class="text-center pt-3 bg-white">
                    <img src="<?= base_url(); ?>assets/images/LOGO/logo_komando.png" style="width: 70%"><br><br>
                </div>

                <div class="text-right pr-3 pt-3">
                    <a class="d-lg-none text-white align-item-center" data-toggle="layout" data-action="sidebar_close" href="javascript:void(0)">
                        <i class="fas fa-angle-double-left"></i> <b>Kembali</b>
                    </a>
                </div>
            </li>

            <li class="nav-main-heading">Lokasi Kerja</li>
                                
            <li class="nav-main-item border-v-white">
                <a class="nav-main-link pl-3" href="javascript:void(0)">
                    <span class="nav-main-link-name text-white">
                        <?= $this->secure->decrypt_string($this->session->userdata('company_name')) ?><br>
                        <?= $this->secure->decrypt_string($this->session->userdata('office_address')) ?>
                    </span>
                </a>
            </li>
                                
            <li class="nav-main-heading">Menu</li>
                                
            <li class="nav-main-item border-v-white">
                <a class="nav-main-link pl-3" href="<?= strtolower(base_url('Home')) ?>">
                    <i class="nav-main-link-icon fas fa-home text-white"></i>
                    <span class="nav-main-link-name text-white">Home</span>
                </a>
            </li>
            <li class="nav-main-item border-v-white">
                <a class="nav-main-link pl-3" href="<?= strtolower(base_url('HistoryAttendance')) ?>">
                    <i class="nav-main-link-icon fas fa-paste text-white"></i>
                    <span class="nav-main-link-name text-white">Riwayat Absensi</span>
                </a>
            </li>
            <li class="nav-main-item border-v-white">
                <a class="nav-main-link pl-3" href="<?= strtolower(base_url('CalendarEvent')) ?>">
                    <i class="nav-main-link-icon fas fa-calendar-alt text-white"></i>
                    <span class="nav-main-link-name text-white">Kalender Event</span>
                </a>
            </li>
            <li class="nav-main-item border-v-white">
                <a class="nav-main-link pl-3" href="<?= strtolower(base_url('PermissionRequest')) ?>">
                    <i class="nav-main-link-icon fas fa-bed text-white"></i>
                    <span class="nav-main-link-name text-white">Izin Tidak Masuk Kerja</span>
                </a>
            </li>
            <!-- menu khusus untuk selain staff biasa, untuk atasan -->
            <?php if($getEmpPermission != 0 || !empty($getEmpPermission)){ ?> 
                <li class="nav-main-item border-v-white">
                <a class="nav-main-link pl-3" href="<?= strtolower(base_url('PermissionEmployee') )?>">
                    <i class="nav-main-link-icon fas fa-users text-white"></i>
                    <span class="nav-main-link-name text-white">Daftar Izin Staff</span>
                </a>
            </li>    
            <?php } ?>
        </ul>
    </div>
</nav>