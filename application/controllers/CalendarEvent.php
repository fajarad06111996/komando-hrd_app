<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CalendarEvent extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in(); // dari helpers/check_session_helper
        $this->load->library('secure');
        $this->load->model('ModelGenId');
        $this->load->model('ModelGlobal');
        $this->load->model('ModelCalendarEvent');
    }

    public function index()
    {
        $data['company'] = $this->ModelGlobal->getCompany();
        $data['contentMenu'] = "menu/calendar_event";
        $this->load->view('menu/index', $data);
    }

    public function getDataCalendar()
    {
        $year_calendar              = filter_var(trim($this->input->post('year_calendar')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        
        if(!$year_calendar) { echo json_encode(['status' => false, 'message' => 'Field year_calendar is required']); die(); }
        
        if(!preg_match('/^[0-9]*$/', $year_calendar)) { echo json_encode(['status' => false, 'message' => 'Field year_calendar is invalid']); die(); }

        $result = $this->ModelCalendarEvent->getDataCalendar($year_calendar);
        if(!$result) { echo json_encode(['status' => false, 'message' => 'Data kalender tidak ditemukan!']); die(); }

        $dateFormatter  = new IntlDateFormatter('id_ID', IntlDateFormatter::FULL, IntlDateFormatter::NONE);
        $data           = "";
        $month          = "";
        foreach ($result as $row) {
            $today      = new DateTime($row['event_date']);
            $full_month = $this->ModelGlobal->getMonthID($row['event_month']);

            if($month !== $row['event_month']) {
                $data = $data . '<div class="listdata">
                    <div class="row px-3 align-items-center bg-blue">
                        <div class="media-body">
                            <label class="mb-0 text-sm text-white">' . $full_month . ' ' . $year_calendar . '</label>
                        </div>
                    </div>
                </div>'; 

                $month = $row['event_month'];
            }
            
            $data = $data . '<div class="listdata">
                    <div class="row px-3 align-items-center bg-white">
                        <div class="media-body">
                            <h5 class="mb-1 mt-3">
                                <small>
                                    <i>
                                        ' . $dateFormatter->format($today) .'
                                    </i>
                                </small>
                            </h5>
                            <p class="text-sm mb-0">
                                <label class="mb-0 text-sm text-red">' . $row['event_name'] . '</label>
                            </p>
                        </div>
                    </div>
                    <hr class="mb-0">
                </div>';  

        }

        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }
}
?>
