<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

        <title>KOMANDO GROUP</title>

        <meta name="description" content="TMS">
        <meta name="author" content="pixelcave">
        <meta name="robots" content="noindex, nofollow">


        <!-- Icons -->
        <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
        <link rel="shortcut icon" href="<?= $company['url_logo'] ?>">
        <link rel="icon" type="image/png" sizes="192x192" href="<?= $company['url_logo'] ?>">
        <link rel="apple-touch-icon" sizes="180x180" href="<?= $company['url_logo'] ?>">
        <!-- END Icons -->

        <!-- Stylesheets -->
        <link rel="stylesheet" href="<?= base_url(); ?>assets/js/plugins/sweetalert2/sweetalert2.min.css">
        <!-- Fonts and OneUI framework -->
        <link rel="stylesheet" id="css-main" href="<?= base_url(); ?>assets/css/oneui.min.css">
        <link rel="stylesheet" id="css-main" href="<?= base_url(); ?>assets/css/toastr.min.css">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
        <!-- END Stylesheets -->
		<style>
			.box_shadow {
                margin:10px;
                padding:10px; 
                border-radius:10px; 
                box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
		    }

            .bg-image {
                /* The image used */
                background-image: url("<?= base_url(); ?>assets/images/BG/bg_truck_front.jpg");

                /* Full height */
                height: 100%;

                /* Center and scale the image nicely */
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
            }
            
            .text-lg {
                font-size: 25px;
		    }
		    
		    .text-blue {
                color: blue;
		    }
		    
		    .text-red {
                color: #CC0000;
		    }
		    
		    .bg-blue {
                background-color: blue;
		    }

            .border-bold-double-blue {
                border-width:5px;
                border-style: double;
                border-color: #151B54;
            }
		</style>
        <script src="<?= base_url(); ?>assets/js/oneui.core.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/oneui.app.min.js"></script>

        <!-- Page JS Plugins -->
        <script src="<?= base_url(); ?>assets/js/plugins/jquery-validation/jquery.validate.min.js"></script>

        <!-- Page JS Code -->
        <script src="<?= base_url(); ?>assets/js/pages/op_auth_signup.min.js"></script>
        <script src="<?= base_url(); ?>assets/js/plugins/sweetalert2/sweetalert2.min.js"></script>
    </head>
    <body class="p-0">
        <!-- name : token_apps -->
        <div id="div_tknapps" style="display: none;"><input type="hidden" name="token_apps" value="112233"></div>
        <div id="page-container">

            <!-- Main Container -->
            <main id="main-container">

                <!-- Page Content -->
                <div class="bg-image">

                    <div class="row justify-content-center mt-5">
                        <div class="col-md-4 text-center">
                        </div>
                        <div class="col-md-4 text-center">
                            <?php $this->load->view('auth/'.$contentAuth) ?>
                        </div>
                        <div class="col-md-4 text-center">
                        </div>
                    </div>
                    <script type="text/javascript">

                        $(document).ready(function(){
                            // console.log( "ready!" );
                        });
            
                        function waiting() {
                            Swal.fire({
                                imageUrl: '<?= base_url() ?>assets/images/ICON/loading_blue.gif',
                                imageHeight: 100,
                                title: 'Please wait ...',
                                text: '',
                                showConfirmButton: false,
                                allowOutsideClick: false
                            })
                        }
                        
                        function successMessageCallBack(title, message, callBack) {
                            Swal.fire({
                                imageUrl: '<?= base_url() ?>assets/images/ICON/success_icon.gif',
                                imageHeight: 100,
                                title: title,
                                text: message,
                                allowOutsideClick: false
                            }).then(function() {
                                waiting();
                                window.location.replace(callBack); 
                            });
                        }
                            
                        function warningMessage(message) {
                            Swal.fire({
                                imageUrl: '<?= base_url() ?>assets/images/ICON/warning_icon.png',
                                imageHeight: 150,
                                title: 'Warning',
                                text: message,
                                allowOutsideClick: false
                            })
                        }
                            
                        function errorConnectionMessage() {
                            Swal.fire({
                                imageUrl: '<?= base_url() ?>assets/images/ICON/times_icon.png',
                                imageHeight: 100,
                                title: 'Error',
                                text: 'Internet Connection Error, please try again',
                                allowOutsideClick: false
                            })
                        }
            
                    </script>

                </div>
                <!-- END Page Content -->

            </main>
            <!-- END Main Container -->

        </div>
        <!-- END Page Container -->
        
    </body>
</html>
