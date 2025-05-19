<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->library('secure');
        $this->load->model('ModelGenId');
        $this->load->model('ModelGlobal');
        $this->load->model('ModelHome');
    }

    public function index()
    {
        $data['company'] = $this->ModelGlobal->getCompany();
        $data['contentMenu'] = "menu/home";
        $this->load->view('menu/index', $data);
    }

    public function getDataEmployee()
    {
        $result = $this->ModelHome->getDataEmployee();
        if(!$result) { echo json_encode(['status' => false, 'message' => 'Data karyawan tidak ditemukan!']); die(); }

        $result_shift = $this->ModelHome->getDataShiftEmployee($result['office_shift']);
        if(!$result_shift) { echo json_encode(['status' => false, 'message' => 'Data karyawan tidak ditemukan!']); die(); }

        $today = new DateTime();
        $dateFormatter = new IntlDateFormatter('id_ID', IntlDateFormatter::FULL, IntlDateFormatter::NONE);

        $shift_name         = $result_shift['shift_name'];

        if($result['check_in'] == null) { 
            $check_in       = '-- : --'; 
            $btn_attendance = '<button type="button" class="btn btn-sm btn-success btn-rounded mb-3 ml-2 px-3 text-sm pointer-hand" onclick="getAttendanceIn()">ABSEN DATANG</button>';
            $btn_permission = ''; 
        }
        else { 
            // Create DateTime objects for the two dates
            $date1 = new DateTime(date('Y-m-d') . ' ' . $result_shift['shift_check_in']);
            $date2 = new DateTime($result['check_in']);

            // Calculate the difference between the two dates
            $difference = $date1->diff($date2);
            $minutes = ($date2->getTimestamp() - $date1->getTimestamp()) / 60;

            if($minutes > 0) { 
                if($difference->h > 0) { $late_hour = $difference->h . ' Jam '; }
                else { $late_hour = ''; }

                $late_time = ' <b class="text-danger">(Telat ' . $late_hour . $difference->i . ' Menit)</b>'; 
            }
            else { $late_time = ''; }

            $check_in = $result['check_in'] . $late_time; 
            if($result['check_out'] == null) { 
                $btn_attendance = '<button type="button" class="btn btn-sm btn-danger btn-rounded mb-3 ml-2 px-3 text-sm pointer-hand" onclick="getAttendanceOut(`' . $this->secure->encrypt_string($result['attendance_idx']) . '`)">ABSEN PULANG</button>';
                $btn_permission = '<button type="button" class="btn btn-sm btn-primary btn-rounded mb-3 ml-2 px-3 text-sm pointer-hand" onclick="getAttendanceOutPermission(`' . $this->secure->encrypt_string($result['attendance_idx']) . '`)">IJIN PULANG</button>'; 
            }
            else { 
                $btn_attendance = ''; 
                $btn_permission = '';
            }
        }

        if($result['check_out'] == null) { $check_out = '-- : --'; }
        else { $check_out = $result['check_out'] . ''; }

        if($result_shift['shift_check_out'] == null) { $shift_check_out    = '-- : --'; }
        else { $shift_check_out    = $result_shift['shift_check_out']; }

        if($result_shift['shift_check_in'] == null) { 
            $shift_check_in     = '-- : --';
            $work_time          = '<b class="text-red">LIBUR</b>';
            $btn_permission     = '';
            $btn_attendance     = '';
        }
        else { 
            $shift_check_in     = $result_shift['shift_check_in']; 
            $work_time          = $shift_name . ' (' . $shift_check_in . ' s/d ' . $shift_check_out . ')';

            // Create DateTime objects for the two dates
            $date1 = new DateTime(date('Y-m-d H:i:s'));
            $date2 = new DateTime(date('Y-m-d') . ' ' . $result_shift['shift_check_out']);

            // Calculate the difference between the two dates
            $minutes = ($date2->getTimestamp() - $date1->getTimestamp()) / 60;

            if($result['check_in'] == null) { 
                $btn_permission = ''; 
            }
            else { 
                if($minutes > 0) { 
                    if($result['check_out'] == null) { 
                        $btn_permission = '<button type="button" class="btn btn-sm btn-primary btn-rounded mb-3 ml-2 px-3 text-sm pointer-hand" onclick="getAttendanceOutPermission(`' . $this->secure->encrypt_string($result['attendance_idx']) . '`)">IJIN PULANG</button>'; 
                    }
                    else { 
                        $btn_permission = '';
                    }
                }
                else { 
                    $btn_permission = '';
                }
            }
        }
        
        if($result['status_permission'] == 0) { $status_permission = ''; }
        else { $status_permission = '<br><b class="text-danger">(Ijin Pulang Awal Karena ' . $result['remarks'] . ')</b>'; }

        if($result['photo'] == NULL || $result['photo'] == '') { $url_photo = base_url() . 'assets/images/ICON/user_icon.png'; }
        else { $url_photo = $result['photo']; }

        if($result['status_overtime'] == 1) { $status_overtime = '<hr><label class="mb-0 text-sm text-success">LEMBUR :<br>' . $result['reason_overtime'] . '</label><hr>'; }
        else { $status_overtime = ''; }

        $data = '<div class="media">
            <img src="' . $url_photo . '" class="mr-3 mt-1 rounded-circle" style="width:60px;"><br>
            <div class="media-body">
                <h5 class="mb-1">
                    ' . $result['employee_name'] . '<br>
                    <small>
                        <i>
                            ' . $dateFormatter->format($today) .'
                        </i>
                    </small>
                </h5>
                <p class="text-sm mb-2">
                    Jam Kerja :<br>
                    <label class="mb-0 text-sm">' . $work_time . '</label><br>
                    Datang :<br><label class="mb-0 text-sm">' . $check_in . '</label><br>
                    Pulang :<br><label class="mb-0 text-sm">' . $check_out . $status_permission . '</label>
                </p>
            </div>
        </div>
        ' . $status_overtime . '
        <div class="row">
            <div class="col text-center">
            ' . $btn_permission . $btn_attendance . '
            </div>
        </div>';

        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data, 'url_photo_user' => $url_photo, 'radius_user' => $result['attendance_radius']]); die();
    }

    public function getDataOffice()
    {
        $result = $this->ModelHome->getDataOffice();
        if(!$result) { echo json_encode(['status' => false, 'message' => 'Data kantor tidak ditemukan!']); die(); }

        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $result]); die();
    }

    public function getDataEmployeeCheckIn()
    {
        $postal_code                = filter_var(trim($this->input->post('postal_code')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        
        if(!$postal_code) { echo json_encode(['status' => false, 'message' => 'Field postal_code is required']); die(); }
        
        if(!preg_match('/^[0-9]*$/', $postal_code)) { echo json_encode(['status' => false, 'message' => 'Field postal_code is invalid']); die(); }
        
        $getLocation = $this->ModelGlobal->getLocationByPostalCode($postal_code);
        if (!$getLocation) { echo json_encode(['status' => false, 'message' => 'Lokasi tidak ditemukan!']); die(); }
        
        $result = $this->ModelHome->getDataEmployee();
        if(!$result) { echo json_encode(['status' => false, 'message' => 'Data karyawan tidak ditemukan!']); die(); }

        $result_shift = $this->ModelHome->getDataShiftEmployee($result['office_shift']);
        if(!$result_shift) { echo json_encode(['status' => false, 'message' => 'Data karyawan tidak ditemukan!']); die(); }

        $today = new DateTime();
        $dateFormatter = new IntlDateFormatter('id_ID', IntlDateFormatter::FULL, IntlDateFormatter::NONE);

        if($result_shift['shift_check_in'] == null) { $shift_check_in     = '-- : --';}
        else { $shift_check_in     = $result_shift['shift_check_in']; }

        if($result_shift['shift_check_out'] == null) { $shift_check_out    = '-- : --'; }
        else { $shift_check_out    = $result_shift['shift_check_out']; }

        $shift_name         = $result_shift['shift_name'];

        $data = '<h5 class="mb-1">
            ' . $result['employee_name'] . '<br>
            <small>
                <i>
                    ' . $dateFormatter->format($today) . '
                </i>
            </small>
        </h5>
        <p class="text-sm mb-2">
            <input type="hidden" id="url_photo_check_in">
            <label class="mb-0 text-sm">' . $shift_name . ' (' . $shift_check_in . ' s/d ' . $shift_check_out . ')</label><br>
            Jam Datang : <label class="mb-0 text-sm">' . date('H:i') . '</label><br>
            <label class="mb-0 text-sm">' . $getLocation['district_name'] . ', ' . $getLocation['city_name'] . '</label>
        </p>';
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }

    public function getDataEmployeeCheckOut()
    {
        $postal_code                = filter_var(trim($this->input->post('postal_code')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        
        if(!$postal_code) { echo json_encode(['status' => false, 'message' => 'Field postal_code is required']); die(); }
        
        if(!preg_match('/^[0-9]*$/', $postal_code)) { echo json_encode(['status' => false, 'message' => 'Field postal_code is invalid']); die(); }
        
        $getLocation = $this->ModelGlobal->getLocationByPostalCode($postal_code);
        if (!$getLocation) { echo json_encode(['status' => false, 'message' => 'Lokasi tidak ditemukan!']); die(); }

        $result = $this->ModelHome->getDataEmployee();
        if(!$result) { echo json_encode(['status' => false, 'message' => 'Data karyawan tidak ditemukan!']); die(); }

        $result_shift = $this->ModelHome->getDataShiftEmployee($result['office_shift']);
        if(!$result_shift) { echo json_encode(['status' => false, 'message' => 'Data karyawan tidak ditemukan!']); die(); }

        $today = new DateTime();
        $dateFormatter = new IntlDateFormatter('id_ID', IntlDateFormatter::FULL, IntlDateFormatter::NONE);

        if($result_shift['shift_check_in'] == null) { $shift_check_in     = '-- : --';}
        else { $shift_check_in     = $result_shift['shift_check_in']; }

        if($result_shift['shift_check_out'] == null) { $shift_check_out    = '-- : --'; }
        else { $shift_check_out    = $result_shift['shift_check_out']; }

        if($result['status_overtime'] == 0) { $btn_overtime = '<button type="button" class="btn btn-primary btn-rounded w-100 mb-2" onclick="getAttendanceOvertime()"><i class="fas fa-clock"></i> Lanjut Lembur</button>'; }
        else { $btn_overtime = ''; }

        $shift_name         = $result_shift['shift_name'];

        $data = '<h5 class="mb-1">
            ' . $result['employee_name'] . '<br>
            <small>
                <i>
                    ' . $dateFormatter->format($today) . '
                </i>
            </small>
        </h5>
        <p class="text-sm mb-2">
            <input type="hidden" id="url_photo_check_out">
            <label class="mb-0 text-sm">' . $shift_name . ' (' . $shift_check_in . ' s/d ' . $shift_check_out . ')</label><br>
            Jam Pulang : <label class="mb-0 text-sm">' . date('H:i') . '</label><br>
            <label class="mb-0 text-sm">' . $getLocation['district_name'] . ', ' . $getLocation['city_name'] . '</label>
        </p>'
        . $btn_overtime;
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }

    public function getDataEmployeeCheckOutPermission()
    {
        $postal_code                = filter_var(trim($this->input->post('postal_code')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        
        if(!$postal_code) { echo json_encode(['status' => false, 'message' => 'Field postal_code is required']); die(); }
        
        if(!preg_match('/^[0-9]*$/', $postal_code)) { echo json_encode(['status' => false, 'message' => 'Field postal_code is invalid']); die(); }
        
        $getLocation = $this->ModelGlobal->getLocationByPostalCode($postal_code);
        if (!$getLocation) { echo json_encode(['status' => false, 'message' => 'Lokasi tidak ditemukan!']); die(); }

        $result = $this->ModelHome->getDataEmployee();
        if(!$result) { echo json_encode(['status' => false, 'message' => 'Data karyawan tidak ditemukan!']); die(); }

        $result_shift = $this->ModelHome->getDataShiftEmployee($result['office_shift']);
        if(!$result_shift) { echo json_encode(['status' => false, 'message' => 'Data karyawan tidak ditemukan!']); die(); }

        $today = new DateTime();
        $dateFormatter = new IntlDateFormatter('id_ID', IntlDateFormatter::FULL, IntlDateFormatter::NONE);

        if($result_shift['shift_check_in'] == null) { $shift_check_in     = '-- : --';}
        else { $shift_check_in     = $result_shift['shift_check_in']; }

        if($result_shift['shift_check_out'] == null) { $shift_check_out    = '-- : --'; }
        else { $shift_check_out    = $result_shift['shift_check_out']; }

        $shift_name         = $result_shift['shift_name'];

        $data = '<h5 class="mb-1">
            ' . $result['employee_name'] . '<br>
            <small>
                <i>
                    ' . $dateFormatter->format($today) . '
                </i>
            </small>
        </h5>
        <p class="text-sm mb-2">
            <input type="hidden" id="url_photo_check_out_permission">
            <label class="mb-0 text-sm">' . $shift_name . ' (' . $shift_check_in . ' s/d ' . $shift_check_out . ')</label><br>
            Jam Pulang : <label class="mb-0 text-sm">' . date('H:i') . '</label><br>
            <label class="mb-0 text-sm">' . $getLocation['district_name'] . ', ' . $getLocation['city_name'] . '</label><br>
            Alasan&nbsp;<b class="text-red">*</b> : <br>
            <textarea id="reason_permission" class="form-control font-size-sm w-100" placeholder="Ketik alasan ijin pulang awal"></textarea>
        </p>';
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }

    public function submitCheckIn()
    {
        $user_idx                   = $this->session->userdata('user_idx');
        $office_idx                 = $this->session->userdata('office_idx');
        $employee_id                = $this->session->userdata('employee_id');
        
        $attendance_date            = date("Y-m-d");
        $url_photo_check_in         = filter_var(trim($this->input->post('url_photo_check_in')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $filename_photo_check_in    = filter_var(trim($this->input->post('filename_photo_check_in')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $latitude_user              = filter_var(trim($this->input->post('latitude_user')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $longitude_user             = filter_var(trim($this->input->post('longitude_user')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $distance                   = filter_var(trim($this->input->post('distance')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        
        $now                        = date("Y-m-d H:i:s");

        if(!$user_idx) { echo json_encode(['status' => false, 'message' => 'Field user_idx is required']); die(); }
        if(!$office_idx) { echo json_encode(['status' => false, 'message' => 'Field office_idx is required']); die(); }
        if(!$employee_id) { echo json_encode(['status' => false, 'message' => 'Field employee_id is required']); die(); }
        
        if(!$url_photo_check_in) { echo json_encode(['status' => false, 'message' => 'Field url_photo_check_in is required']); die(); }
        if(!$filename_photo_check_in) { echo json_encode(['status' => false, 'message' => 'Field filename_photo_check_in is required']); die(); }
        if(!$latitude_user) { echo json_encode(['status' => false, 'message' => 'Field latitude_user is required']); die(); }
        if(!$longitude_user) { echo json_encode(['status' => false, 'message' => 'Field longitude_user is required']); die(); }
        if(!$distance) { echo json_encode(['status' => false, 'message' => 'Field distance is required']); die(); }
        
        $user_idx                   = $this->secure->decrypt_string($user_idx);
        $office_idx                 = $this->secure->decrypt_string($office_idx);
        $employee_id                = $this->secure->decrypt_string($employee_id);

        if(!preg_match('/^[0-9]*$/', $user_idx)) { echo json_encode(['status' => false, 'message' => 'Field user_idx is invalid']); die(); }
        if(!preg_match('/^[0-9]*$/', $office_idx)) { echo json_encode(['status' => false, 'message' => 'Field office_idx is invalid']); die(); }
        
        $point_center_check_in      = $latitude_user . "," . $longitude_user;

        $checkShitEmployee = $this->ModelHome->checkShitEmployee($employee_id);

        $shift_idx                  = $checkShitEmployee['shift_idx'];
        $target_in                  = $checkShitEmployee['target_in'];
        $target_out                 = $checkShitEmployee['target_out'];
        $work_time                  = $checkShitEmployee['work_time'];
        $radius                     = $checkShitEmployee['radius'];

        if($radius < $distance) { echo json_encode(['status' => false, 'message' => 'Lokasi anda belum berada di sekitar kantor!']); die(); }

        $attendance_no              = str_pad($employee_id, 4, "0", STR_PAD_LEFT) . time();

        // Create a DateTime object for the current date and time
        $date = new DateTime($attendance_date . " " . $target_in);

        // Add time using the modify() method
        $date->modify('+' . $work_time . ' hour'); // Adds ... hour

        // Output the modified date and time
        $attendance_date_out        = $date->format('Y-m-d'); // Output: 2024-06-01 11:30:15

        $insert_attendance_employee = [
            'attendance_no'             => $attendance_no,
            'attendance_date'           => $attendance_date,
            'employee_id'               => $employee_id,
            'shift_idx'                 => $shift_idx,
            'target_in'                 => $attendance_date . " " . $target_in,
            'check_in'                  => $now,
            'target_out'                => $attendance_date_out . " " . $target_out,
            'point_center_check_in'     => $point_center_check_in,
            'url_photo_check_in'        => $url_photo_check_in,
            'filename_photo_check_in'   => $filename_photo_check_in,
            'office_idx'                => $office_idx,
            'status'                    => 1,
            'created_on'                => $now,
            'created_by'                => $user_idx
        ];
                        
        $result = $this->ModelHome->insertCheckIn($insert_attendance_employee);
        $msg_fail      = 'Absen datang gagal!';
        $msg_success    = 'Absen datang berhasil';

        if($result == 0) { echo json_encode(['status' => false, 'message' => $msg_fail]); die(); }
            
        echo json_encode(['status' => true, 'message' => $msg_success]); die();
        
    }

    public function submitCheckOut()
    {
        $user_idx                   = $this->session->userdata('user_idx');
        $office_idx                 = $this->session->userdata('office_idx');
        $employee_id                = $this->session->userdata('employee_id');
        
        $attendance_idx             = filter_var(trim($this->input->post('attendance_idx')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $url_photo_check_out        = filter_var(trim($this->input->post('url_photo_check_out')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $filename_photo_check_out   = filter_var(trim($this->input->post('filename_photo_check_out')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $latitude_user              = filter_var(trim($this->input->post('latitude_user')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $longitude_user             = filter_var(trim($this->input->post('longitude_user')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $distance                   = filter_var(trim($this->input->post('distance')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

        $now                        = date("Y-m-d H:i:s");

        if(!$user_idx) { echo json_encode(['status' => false, 'message' => 'Field user_idx is required']); die(); }
        if(!$office_idx) { echo json_encode(['status' => false, 'message' => 'Field office_idx is required']); die(); }
        if(!$employee_id) { echo json_encode(['status' => false, 'message' => 'Field employee_id is required']); die(); }
        
        if(!$attendance_idx) { echo json_encode(['status' => false, 'message' => 'Field attendance_idx is required']); die(); }
        if(!$url_photo_check_out) { echo json_encode(['status' => false, 'message' => 'Field url_photo_check_out is required']); die(); }
        if(!$filename_photo_check_out) { echo json_encode(['status' => false, 'message' => 'Field filename_photo_check_out is required']); die(); }
        if(!$latitude_user) { echo json_encode(['status' => false, 'message' => 'Field latitude_user is required']); die(); }
        if(!$longitude_user) { echo json_encode(['status' => false, 'message' => 'Field longitude_user is required']); die(); }
        if(!$distance) { echo json_encode(['status' => false, 'message' => 'Field distance is required']); die(); }
        
        $user_idx                   = $this->secure->decrypt_string($user_idx);
        $office_idx                 = $this->secure->decrypt_string($office_idx);
        $employee_id                = $this->secure->decrypt_string($employee_id);
        $attendance_idx             = $this->secure->decrypt_string($attendance_idx);

        if(!preg_match('/^[0-9]*$/', $user_idx)) { echo json_encode(['status' => false, 'message' => 'Field user_idx is invalid']); die(); }
        if(!preg_match('/^[0-9]*$/', $office_idx)) { echo json_encode(['status' => false, 'message' => 'Field office_idx is invalid']); die(); }
        if(!preg_match('/^[0-9]*$/', $employee_id)) { echo json_encode(['status' => false, 'message' => 'Field employee_id is invalid']); die(); }
        if(!preg_match('/^[0-9]*$/', $attendance_idx)) { echo json_encode(['status' => false, 'message' => 'Field attendance_idx is invalid']); die(); }
        
        $point_center_check_out     = $latitude_user . "," . $longitude_user;

        $checkAttendanceEmployee    = $this->ModelHome->getAttendanceEmployee($attendance_idx);

        $radius                     = $checkAttendanceEmployee['radius'];

        if($radius < $distance) { echo json_encode(['status' => false, 'message' => 'Lokasi anda belum berada di sekitar kantor!']); die(); }

        // Create DateTime objects for the two dates
        $date1 = new DateTime($checkAttendanceEmployee['target_out']);
        $date2 = new DateTime($now);

        // Calculate the difference between the two dates
        $difference = $date1->diff($date2);
        $minutes = ($date2->getTimestamp() - $date1->getTimestamp()) / 60;

        if($minutes < 0) { echo json_encode(['status' => false, 'message' => 'Belum waktunya pulang']); die(); }

        $update_attendance_employee = [
            'check_out'                 => $now,
            'point_center_check_out'    => $point_center_check_out,
            'url_photo_check_out'       => $url_photo_check_out,
            'filename_photo_check_out'  => $filename_photo_check_out,
            'status'                    => 2,
            'modified_on'               => $now,
            'modified_by'               => $user_idx
        ];
                        
        $result = $this->ModelHome->updateCheckOut($update_attendance_employee, $attendance_idx);
        $msg_fail      = 'Absen pulang gagal!';
        $msg_success    = 'Absen pulang berhasil';

        if($result == 0) { echo json_encode(['status' => false, 'message' => $msg_fail]); die(); }
            
        echo json_encode(['status' => true, 'message' => $msg_success]); die();
        
    }

    public function submitCheckOutPermission()
    {
        $user_idx                   = $this->session->userdata('user_idx');
        $office_idx                 = $this->session->userdata('office_idx');
        $employee_id                = $this->session->userdata('employee_id');
        
        $attendance_idx             = filter_var(trim($this->input->post('attendance_idx')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $url_photo_check_out        = filter_var(trim($this->input->post('url_photo_check_out')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $filename_photo_check_out   = filter_var(trim($this->input->post('filename_photo_check_out')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $latitude_user              = filter_var(trim($this->input->post('latitude_user')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $longitude_user             = filter_var(trim($this->input->post('longitude_user')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $distance                   = filter_var(trim($this->input->post('distance')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $remarks                    = filter_var(trim($this->input->post('remarks')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

        $now                        = date("Y-m-d H:i:s");

        if(!$user_idx) { echo json_encode(['status' => false, 'message' => 'Field user_idx is required']); die(); }
        if(!$office_idx) { echo json_encode(['status' => false, 'message' => 'Field office_idx is required']); die(); }
        if(!$employee_id) { echo json_encode(['status' => false, 'message' => 'Field employee_id is required']); die(); }
        
        if(!$attendance_idx) { echo json_encode(['status' => false, 'message' => 'Field attendance_idx is required']); die(); }
        if(!$url_photo_check_out) { echo json_encode(['status' => false, 'message' => 'Field url_photo_check_out is required']); die(); }
        if(!$filename_photo_check_out) { echo json_encode(['status' => false, 'message' => 'Field filename_photo_check_out is required']); die(); }
        if(!$latitude_user) { echo json_encode(['status' => false, 'message' => 'Field latitude_user is required']); die(); }
        if(!$longitude_user) { echo json_encode(['status' => false, 'message' => 'Field longitude_user is required']); die(); }
        if(!$distance) { echo json_encode(['status' => false, 'message' => 'Field distance is required']); die(); }
        if(!$remarks) { echo json_encode(['status' => false, 'message' => 'Field remarks is required']); die(); }
        
        $user_idx                   = $this->secure->decrypt_string($user_idx);
        $office_idx                 = $this->secure->decrypt_string($office_idx);
        $employee_id                = $this->secure->decrypt_string($employee_id);
        $attendance_idx             = $this->secure->decrypt_string($attendance_idx);

        if(!preg_match('/^[0-9]*$/', $user_idx)) { echo json_encode(['status' => false, 'message' => 'Field user_idx is invalid']); die(); }
        if(!preg_match('/^[0-9]*$/', $office_idx)) { echo json_encode(['status' => false, 'message' => 'Field office_idx is invalid']); die(); }
        if(!preg_match('/^[0-9]*$/', $employee_id)) { echo json_encode(['status' => false, 'message' => 'Field employee_id is invalid']); die(); }
        if(!preg_match('/^[0-9]*$/', $attendance_idx)) { echo json_encode(['status' => false, 'message' => 'Field attendance_idx is invalid']); die(); }
        
        $point_center_check_out     = $latitude_user . "," . $longitude_user;

        $checkAttendanceEmployee    = $this->ModelHome->getAttendanceEmployee($attendance_idx);

        $radius                     = $checkAttendanceEmployee['radius'];

        if($radius < $distance) { echo json_encode(['status' => false, 'message' => 'Lokasi anda belum berada di sekitar kantor!']); die(); }

        // Create DateTime objects for the two dates
        $date1 = new DateTime($checkAttendanceEmployee['target_out']);
        $date2 = new DateTime($now);

        // Calculate the difference between the two dates
        $difference = $date1->diff($date2);
        $minutes = ($date2->getTimestamp() - $date1->getTimestamp()) / 60;

        $update_attendance_employee = [
            'check_out'                 => $now,
            'point_center_check_out'    => $point_center_check_out,
            'url_photo_check_out'       => $url_photo_check_out,
            'filename_photo_check_out'  => $filename_photo_check_out,
            'remarks'                   => $remarks,
            'status_permission'         => 1,
            'status'                    => 2,
            'modified_on'               => $now,
            'modified_by'               => $user_idx
        ];
                        
        $result = $this->ModelHome->updateCheckOut($update_attendance_employee, $attendance_idx);
        $msg_fail      = 'Ijin pulang awal gagal!';
        $msg_success    = 'Ijin pulang awal berhasil';

        if($result == 0) { echo json_encode(['status' => false, 'message' => $msg_fail]); die(); }
            
        echo json_encode(['status' => true, 'message' => $msg_success]); die();
        
    }

    public function submitOvertime()
    {
        $user_idx                   = $this->session->userdata('user_idx');
        $office_idx                 = $this->session->userdata('office_idx');
        $employee_id                = $this->session->userdata('employee_id');
        
        $attendance_idx             = filter_var(trim($this->input->post('attendance_idx')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $reason_overtime            = filter_var(trim($this->input->post('reason_overtime')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

        $now                        = date("Y-m-d H:i:s");

        if(!$user_idx) { echo json_encode(['status' => false, 'message' => 'Field user_idx is required']); die(); }
        if(!$office_idx) { echo json_encode(['status' => false, 'message' => 'Field office_idx is required']); die(); }
        if(!$employee_id) { echo json_encode(['status' => false, 'message' => 'Field employee_id is required']); die(); }
        
        if(!$attendance_idx) { echo json_encode(['status' => false, 'message' => 'Field attendance_idx is required']); die(); }
        if(!$reason_overtime) { echo json_encode(['status' => false, 'message' => 'Field reason_overtime is required']); die(); }
        
        $user_idx                   = $this->secure->decrypt_string($user_idx);
        $office_idx                 = $this->secure->decrypt_string($office_idx);
        $employee_id                = $this->secure->decrypt_string($employee_id);
        $attendance_idx             = $this->secure->decrypt_string($attendance_idx);

        if(!preg_match('/^[0-9]*$/', $user_idx)) { echo json_encode(['status' => false, 'message' => 'Field user_idx is invalid']); die(); }
        if(!preg_match('/^[0-9]*$/', $office_idx)) { echo json_encode(['status' => false, 'message' => 'Field office_idx is invalid']); die(); }
        if(!preg_match('/^[0-9]*$/', $employee_id)) { echo json_encode(['status' => false, 'message' => 'Field employee_id is invalid']); die(); }
        if(!preg_match('/^[0-9]*$/', $attendance_idx)) { echo json_encode(['status' => false, 'message' => 'Field attendance_idx is invalid']); die(); }
        
        $update_attendance_employee = [
            'reason_overtime'           => $reason_overtime,
            'status_overtime'           => 1,
            'modified_on'               => $now,
            'modified_by'               => $user_idx
        ];
                        
        $result = $this->ModelHome->updateCheckOut($update_attendance_employee, $attendance_idx);
        $msg_fail      = 'Lanjut lembur gagal!';
        $msg_success    = 'Lanjut lembur berhasil';

        if($result == 0) { echo json_encode(['status' => false, 'message' => $msg_fail]); die(); }
            
        echo json_encode(['status' => true, 'message' => $msg_success]); die();
        
    }
}
?>
