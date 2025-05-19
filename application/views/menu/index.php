<!doctype html>
<html>

<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">

    <title>KOMANDO GROUP</title>

    <meta name="description" content="KGL Mitra Warehouse">
    <meta name="author" content="pixelcave">
    <meta name="robots" content="noindex, nofollow">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Icons -->
    <link rel="shortcut icon" href="<?= $company['url_logo'] ?>">
    <link rel="icon" type="image/png" sizes="192x192" href="<?= $company['url_logo'] ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= $company['url_logo'] ?>">
    <!-- END Icons -->

    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/adminlte.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/timeline.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/js/plugins/raty-js/jquery.raty.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Audiowide">

    <!-- Stylesheets -->
    <style>
        /* Default styles */
        body {
            background-color: white;
            color: black;
        }

        /* Dark mode styles */
        @media (prefers-color-scheme: dark) {
            body {
                background-color: black;
                color: white;
            }
        }

        .notification {
            background-color: #e9242b;
            color: white;
            text-decoration: none;
            padding: 15px 26px;
            position: relative;
            display: inline-block;
            border-radius: 2px;
        }

        .notification:hover {
            background: #c0252b;
        }

        .notification .badge {
            position: absolute;
            top: -10px;
            right: -10px;
            padding: 5px 10px;
            border-radius: 50%;
            background-color: orange;
            color: white;
        }

        .box_rounded {
            background-color: #FFF;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 10px 0 rgba(0, 0, 0, 0);
        }

        .box_rounded_top {
            background-color: #FFF;
            padding: 10px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 10px 0 rgba(0, 0, 0, 0);
        }

        .box_rounded_bottom {
            background-color: #FFF;
            padding: 10px;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 10px 0 rgba(0, 0, 0, 0);
        }

        .box_rounded_left {
            background-color: #FFF;
            padding: 10px;
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 10px 0 rgba(0, 0, 0, 0);
        }

        .box_rounded_right {
            background-color: #FFF;
            padding: 10px;
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 10px 0 rgba(0, 0, 0, 0);
        }

        .bg-image {
            /* The image used */
            background-image: url("<?= base_url(); ?>assets/images/BG/bg_home.png");

            /* Full height */
            height: 100%;

            /* Center and scale the image nicely */
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .bg-green {
            background-color: green !important;
        }

        .bg-blue {
            background-color: #151B54 !important;
        }

        .bg-red {
            background-color: #760000 !important;
        }

        .bg-gold {
            background-color: #C5B358 !important;
        }

        .text-gold {
            color: #d4af37 !important;
        }

        .text-red {
            color: #760000 !important;
        }

        .text-blue {
            color: #151B54 !important;
        }

        .text-grey {
            color: rgba(0, 0, 0, .15) !important;
        }

        .border-red {
            border-width: 1px;
            border-style: solid;
            border-color: #d8272d;
        }

        .border-blue {
            border-width: 1px;
            border-style: solid;
            border-color: #151B54;
        }

        .border-v-white {
            border-top-width: 1px;
            border-bottom-width: 1px;
            border-left-width: 0px;
            border-right-width: 0px;
            border-style: solid;
            border-color: white;
        }

        .border-right {
            border-top-width: 0px;
            border-bottom-width: 0px;
            border-left-width: 0px;
            border-right-width: 1px;
            border-style: solid;
            border-color: #CCC;
        }

        .line-rounded-top {
            border-top-left-radius: 50%;
            border-top-right-radius: 50%;
        }

        .header-home {
            height: 180px;
            border-bottom-left-radius: 30%;
            border-bottom-right-radius: 30%;
        }

        .pagination {
            display: inline-block;
        }

        .pagination a {
            color: black;
            float: left;
            padding: 8px 16px;
            text-decoration: none;
        }

        .pagination a.active {
            background-color: grey;
            color: white;
            border-radius: 5px;
        }

        .pagination a:hover:not(.active) {
            background-color: #ddd;
            border-radius: 5px;
        }

        .nav-main-item a:hover {
            background-color: red !important;
        }

        .bootstrap-select .btn {
            background-color: #FFF;
            border-style: solid;
            border-width: 1px;
            border-color: #E8E8E8;
            border-left-width: 3px;
            border-left-color: #00DDDD;
            color: #707070;
            font-weight: 400;
            font-size: 14px;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        .pointer-hand {
            cursor: pointer;
        }

        /* Sembunyikan semua konten kalau dari layar besar */
        @media only screen and (min-width: 768px) {
            body::before {
                content: "Website ini hanya untuk perangkat mobile.";
                display: block;
                text-align: center;
                padding: 30px;
                font-size: 18px;
                color: red;
            }

            body>* {
                display: none !important;
            }
        }
    </style>
    <link rel="stylesheet" href="<?= base_url(); ?>assets/js/plugins/sweetalert2/sweetalert2.min.css">
    <!-- Fonts and OneUI framework -->
    <link rel="stylesheet" id="css-main" href="<?= base_url(); ?>assets/css/oneui.css">

    <link rel="stylesheet" href="<?= base_url(); ?>assets/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
    <!-- END Stylesheets -->

    <!-- Bootstrap JS Code -->
    <script src="<?= base_url(); ?>assets/js/oneui.core.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/oneui.app.min.js"></script>

    <!-- Plugin JS Code -->
    <script src="<?= base_url(); ?>assets/js/plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/plugins/jquery-validation/additional-methods.js"></script>
    <script src="<?= base_url(); ?>assets/js/plugins/jquery.maskedinput/jquery.maskedinput.min.js"></script>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/bootstrap-select.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="<?= base_url(); ?>assets/js/bootstrap-select.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/cleave.js@1.5.3/dist/cleave.min.js"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script src="<?= base_url(); ?>assets/js/firebase.js"></script>
    <script src="<?= base_url(); ?>assets/js/global.js?v=2<?= time(); ?>"></script>
</head>

<body>

    <!-- The Modal Office List -->
    <div class="modal" id="modalOfficeList">
        <div class="modal-dialog modal-md">
            <div class="modal-content p-2">

                <!-- Modal Header -->
                <div class="modal-header border border-left-0 border-top-0 border-right-0">
                    <h4 class="modal-title">Wilayah Operasi</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body text-left border border-left-0 border-top-0 border-right-0">
                    <label class="mb-3">Silahkan pilih akses Wilayah Operasi : </label>
                    <div id="listAccessOffice"></div>
                </div>

                <!-- Modal footer -->
                <div class="p-3 text-center">
                    <button type="button" class="btn btn-warning" data-dismiss="modal"><i class="fas fa-arrow-alt-circle-left text-sm mr-2"></i> BATAL</button>
                </div>

            </div>
        </div>
    </div>

    <!-- The Modal Change Password -->
    <div class="modal" id="modalChangePassword">
        <div class="modal-dialog modal-lg">
            <div class="modal-content p-2">

                <!-- Modal Header -->
                <div class="modal-header border border-left-0 border-top-0 border-right-0">
                    <h4 class="modal-title">Ubah Sandi</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body text-left border-left-0 border-top-0 border-right-0">
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <span class="text-dark pl-1 font-size-sm"><b>Sandi Lama</b> <b class="text-danger">*</b></span><br>
                            <div class="input-group mb-3 mt-2">
                                <div class="input-group-prepend">
                                    <i class="fas fa-code input-group-text p-2 font-size-sm" style="width: 40px"></i>
                                </div>
                                <input type="password" id="old_password" class="form-control font-size-sm" placeholder="Ketik sandi lama">
                            </div>
                            <span class="text-dark pl-1 font-size-sm"><b>Sandi Baru</b> <b class="text-danger">*</b></span><br>
                            <div class="input-group mb-3 mt-2">
                                <div class="input-group-prepend">
                                    <i class="fas fa-key input-group-text p-2 font-size-sm" style="width: 40px"></i>
                                </div>
                                <input type="password" id="new_password" class="form-control font-size-sm" placeholder="Ketik sandi baru">
                            </div>
                            <span class="text-dark pl-1 font-size-sm"><b>Konfirmasi Sandi</b> <b class="text-danger">*</b></span><br>
                            <div class="input-group mb-3 mt-2">
                                <div class="input-group-prepend">
                                    <i class="fas fa-key input-group-text p-2 font-size-sm" style="width: 40px"></i>
                                </div>
                                <input type="password" id="confirm_password" class="form-control font-size-sm" placeholder="Ketik konfirmasi sandi">
                            </div>
                        </div>

                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <button class="btn btn-danger font-size-sm" type="button" data-dismiss="modal"><i class="fas fa-times"></i> BATAL</button>
                            <button type="button" class="btn btn-success font-size-sm" onclick="submitChangePassword()"><i class="fas fa-check"></i> SUBMIT</button>
                        </div>
                    </div>
                    <hr>
                </div>

            </div>
        </div>
    </div>

    <div id="page-container" class="sidebar-o page-header-fixed side-trans-enabled sidebar-mini" style="background-color: #FFF;">

        <!-- Header -->
        <header id="page-header" class="bg-blue">
            <!-- Header Content -->
            <div class="content-header">
                <!-- Left Section -->
                <div class="d-flex align-items-center">

                    <!-- Toggle Sidebar -->
                    <button type="button" class="btn btn-sm mr-2 d-lg-none" data-toggle="layout" data-action="sidebar_toggle">
                        <i class="fa fa-fw fa-bars text-white"></i>
                    </button>
                    <!-- END Toggle Sidebar -->


                    <!-- Toggle Mini Sidebar -->
                    <button type="button" class="btn btn-sm mr-2 d-none d-lg-inline-block" data-toggle="layout" data-action="sidebar_mini_toggle">
                        <i class="fa fa-fw fa-bars text-white"></i>
                    </button>
                    <!-- END Toggle Mini Sidebar -->

                </div>
                <!-- END Left Section -->

                <!-- Center Section -->
                <div class="d-flex align-items-center">

                    <label class="text-white mb-0" style="font-size: 30px; font-family: 'Audiowide', sans-serif;">ABSENSI</label>

                </div>
                <!-- END Center Section -->

                <!-- load view menu sidebar -->
                <?php 
                // untuk get data apakah user atasan atau bukan, jika atasan maka tampil menu ijin kerja staff
                $idUser             = $this->secure->decrypt_string($this->session->userdata('employee_id')); // get id karyawan
                $data['getEmpPermission']      = $this->ModelGlobal->getOrganizationEmp($idUser)->num_rows();
                $this->load->view('menu/menu', $data); ?>

                <aside id="side-overlay" class="font-size-sm">
                    <!-- Side Header -->
                    <div class="content-header border-bottom">
                        <img src="<?= base_url(); ?>assets/images/LOGO/logo_komando.png" width="100px">

                        <!-- Close Side Overlay -->
                        <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                        <a class="ml-auto btn btn-sm btn-dual" href="javascript:void(0)" data-toggle="layout" data-action="side_overlay_close">
                            <i class="fa fa-fw fa-times text-danger"></i>
                        </a>
                        <!-- END Close Side Overlay -->
                    </div>
                    <!-- END Side Header -->

                    <!-- Side Content -->
                    <div class="content-side">
                        <!-- Side Overlay Tabs -->
                        <div class="block block-transparent pull-x pull-t">
                            <div class="block-content tab-content overflow-hidden">
                                <!-- Overview Tab -->
                                <div class="tab-pane pull-x fade fade-left show active" id="so-overview" role="tabpanel">
                                    <!-- Activity -->
                                    <div class="block">
                                        <div class="block-header block-header-default text-center">
                                            <h3 class="block-title">PROFIL KARYAWAN</h3>
                                            <!--<div class="block-options">-->
                                            <!--    <a href="#" class="btn-block-option" data-toggle="modal" data-target="#modal-editprofile">-->
                                            <!--        <i class="fa fa-user-edit"></i>-->
                                            <!--    </a>-->
                                            <!--    <a href="#" class="btn-block-option" data-toggle="modal" data-target="#modal-setting">-->
                                            <!--        <i class="si si-settings"></i>-->
                                            <!--    </a>-->
                                            <!--</div>-->
                                        </div>

                                        <div class="block-content">
                                            <div class="row mb-2">
                                                <div class="col-12 text-center">
                                                    <img src="<?= base_url(); ?>assets/images/ICON/user_icon.png" width="80px" height="80px" class="mb-3">

                                                    <div class="form-horizontal text-left text-sm">
                                                        <div class="form-row mb-1 align-items-center bg-secondary">
                                                            <label class="control-label col-sm-4 mb-0 bg-secondary pl-2 pt-1"><i class="fas fa-address-card"></i> NIP</label>
                                                            <div class="col-sm-8 bg-secondary p-1">
                                                                <input type="text" class="form-control box_pointed font-size-sm" value="<?= $this->secure->decrypt_string($this->session->userdata('employee_code')) ?>" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="form-row mb-1 align-items-center bg-secondary">
                                                            <label class="control-label col-sm-4 mb-0 bg-secondary pl-2 pt-1"><i class="fas fa-user"></i> Nama</label>
                                                            <div class="col-sm-8 bg-secondary p-1">
                                                                <input type="text" class="form-control box_pointed font-size-sm" value="<?= $this->secure->decrypt_string($this->session->userdata('user_name')) ?>" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="form-row mb-1 align-items-center bg-secondary">
                                                            <label class="control-label col-sm-4 mb-0 bg-secondary pl-2 pt-1"><i class="fas fa-home"></i> Alamat</label>
                                                            <div class="col-sm-8 bg-secondary p-1">
                                                                <textarea class="box_pointed form-control font-size-sm" rows="4" placeholder="Alamat" readonly><?= $this->secure->decrypt_string($this->session->userdata('user_address')) ?></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="form-row mb-1 align-items-center bg-secondary">
                                                            <label class="control-label col-sm-4 mb-0 bg-secondary pl-2 pt-1"><i class="fas fa-building"></i> Perusahaan</label>
                                                            <div class="col-sm-8 bg-secondary p-1">
                                                                <textarea class="box_pointed form-control font-size-sm" rows="4" placeholder="Alamat" readonly><?= $this->secure->decrypt_string($this->session->userdata('company_name')) ?>&#13;<?= $this->secure->decrypt_string($this->session->userdata('office_address')) ?></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="form-row mb-1 align-items-center bg-secondary">
                                                            <label class="control-label col-sm-4 mb-0 bg-secondary pl-2 pt-1"><i class="fas fa-envelope"></i> Email</label>
                                                            <div class="col-sm-8 bg-secondary p-1">
                                                                <input type="text" class="form-control box_pointed font-size-sm" placeholder="Email ID" value="<?= $this->secure->decrypt_string($this->session->userdata('user_email')) ?>" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="form-row mb-1 align-items-center bg-secondary">
                                                            <label class="control-label col-sm-4 mb-0 bg-secondary pl-2 pt-1"><i class="fas fa-mobile-alt"></i> Nomor HP</label>
                                                            <div class="col-sm-8 bg-secondary p-1">
                                                                <input type="text" class="form-control box_pointed font-size-sm" placeholder="Nomor HP" value="<?= $this->secure->decrypt_string($this->session->userdata('user_phone')) ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button class="btn btn-block btn-primary btn-sm" onclick="getChangePassword()"><i class="si si-key"></i> UBAH SANDI</button>
                                            <button class="btn btn-block btn-danger btn-sm" onclick="log_out()"><i class="si si-power"></i> KELUAR</button>
                                        </div>

                                    </div>

                                    <!-- END Activity -->
                                </div>
                            </div>
                            <!-- END Side Content -->
                        </div>
                    </div>
                </aside>
                <!-- END Side Overlay -->

                <!-- Right Section -->
                <div class="d-flex align-items-center">


                    <!-- Notifications Dropdown -->
                    <div class="dropdown d-inline-block ml-2">
                        <a href="" class="img-link mr-1 update-notif" id="page-header-notifications-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="si si-bell" style="color: white"></i>
                            <span class="badge badge-primary badge-pill" id="count-notif"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0 border-0 font-size-sm" aria-labelledby="page-header-notifications-dropdown" id="list-notif">

                        </div>
                    </div>
                    <!-- END Notifications Dropdown -->

                    <button type="button" class="btn btn-sm ml-2 text-white" data-toggle="layout" data-action="side_overlay_toggle">
                        <i class="fa fa-fw fas fa-user fa-flip-horizontal"></i>
                    </button>

                </div>
                <!-- END Right Section -->
            </div>
            <!-- END Header Content -->

        </header>
        <!-- END Header -->

        <!-- Main Container -->
        <!-- <main id="main-container" style="background-color: #CCC;">
                <div class="content pt-2"> -->
        <!-- Page Content -->
        <?php $this->load->view($contentMenu); ?>
        <!-- END Page Content -->
        <!-- </div>
            </main> -->
        <!-- END Main Container -->

        <!-- Footer -->
        <!-- <footer class="footer py-4">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-12 text-center">Copyright © Mokhammad Programming</div>
                    </div>
                </div>
            </footer> -->

    </div>
    <!-- END Page Container -->

</body>

</html>