<?php
defined('BASEPATH') or exit('No direct script access allowed');

class HistoryAttendance extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->library('secure');
        $this->load->model('ModelGenId');
        $this->load->model('ModelGlobal');
        $this->load->model('ModelHistoryAttendance');
    }

    public function index()
    {
        $data['company'] = $this->ModelGlobal->getCompany();
        $data['contentMenu'] = "menu/history_attendance";
        $this->load->view('menu/index', $data);
    }

    public function getDataAttendance()
    {
        $month_attendance           = filter_var(trim($this->input->post('month_attendance')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $year_attendance            = filter_var(trim($this->input->post('year_attendance')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        
        if(!$month_attendance) { echo json_encode(['status' => false, 'message' => 'Field month_attendance is required']); die(); }
        if(!$year_attendance) { echo json_encode(['status' => false, 'message' => 'Field year_attendance is required']); die(); }
        
        if(!preg_match('/^[0-9]*$/', $month_attendance)) { echo json_encode(['status' => false, 'message' => 'Field month_attendance is invalid']); die(); }
        if(!preg_match('/^[0-9]*$/', $year_attendance)) { echo json_encode(['status' => false, 'message' => 'Field year_attendance is invalid']); die(); }

        $result = $this->ModelHistoryAttendance->getDataAttendance($month_attendance, $year_attendance);
        if(!$result) { echo json_encode(['status' => false, 'message' => 'Data absensi tidak ditemukan!']); die(); }

        // $today = new DateTime(); var_dump($today); die(); echo json_encode(['status' => false, 'message' => $today]); die();
        $dateFormatter  = new IntlDateFormatter('id_ID', IntlDateFormatter::FULL, IntlDateFormatter::NONE);
        $detailDateFormatter  = new IntlDateFormatter('id_ID', IntlDateFormatter::FULL, IntlDateFormatter::NONE, 'Asia/Jakarta', IntlDateFormatter::GREGORIAN, 'd MMMM yyyy');
        $data           = "";
        foreach ($result as $row) {
            $today = new DateTime($row['attendance_date']);
            $date_check_in = new DateTime($row['date_check_in']);
            if($row['date_check_out'] == NULL || $row['date_check_out'] == '') {
                $check_out = '-- : --';
            }
            else {
                $date_check_out = new DateTime($row['date_check_out']);
                $check_out = $detailDateFormatter->format($date_check_out) . ' ' . $row['time_check_out'];
            }

            if($row['url_photo_employee'] == NULL || $row['url_photo_employee'] == '') {
                $url_photo_employee = base_url() . 'assets/images/ICON/user_icon.png';
            }
            else {
                $url_photo_employee = $row['url_photo_employee'];
            }

            // Create DateTime objects for the two dates
            $date1 = new DateTime($row['target_in']);
            $date2 = new DateTime($row['check_in']);

            // Calculate the difference between the two dates
            $difference = $date1->diff($date2);
            $minutes = ($date2->getTimestamp() - $date1->getTimestamp()) / 60;

            if($minutes > 0) { 
                if($difference->h > 0) { $late_hour = $difference->h . ' Jam '; }
                else { $late_hour = ''; }

                $late_time = '<b class="text-danger">Telat ' . $late_hour . $difference->i . ' Menit</b>'; 
            }
            else { $late_time = '<b class="text-success">Tepat Waktu</b>'; }
            
            $data = $data . '<div class="listdata">
                    <div class="row px-3 align-items-center bg-white">
                        <img src="' . $url_photo_employee . '" alt="John Doe" class="mr-3 mt-1 rounded-circle" style="width:60px; height: 60px;">
                        <div class="media-body">
                            <h5 class="mb-1">
                                <small>
                                    <i>
                                        ' . $dateFormatter->format($today) .'
                                    </i>
                                </small>
                            </h5>
                            <p class="text-sm mb-2">
                                Jam Kerja : <label class="mb-0 text-sm">' . $row['shift_name'] . ' (' . $row['time_target_in'] . ' s/d ' . $row['time_target_out'] . ')</label><br>
                                Datang : <label class="mb-0 text-sm">' . $detailDateFormatter->format($date_check_in) . ' ' . $row['time_check_in'] . '</label><br>
                                Pulang : <label class="mb-0 text-sm">' . $check_out . '</label><br>
                                Status : <label class="mb-0 text-sm">' . $late_time . '</label>
                            </p>
                        </div>
                    </div>
                    <hr>
                </div>';  

        }

        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }
}
?>
