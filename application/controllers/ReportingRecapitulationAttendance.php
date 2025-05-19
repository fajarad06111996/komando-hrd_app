<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ReportingRecapitulationAttendance extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->library('secure');
        $this->load->model('ModelGlobal');
        $this->load->model('ModelReportingRecapitulationAttendance');
        if($this->session->userdata('menu_reporting_recapitulation_attendance') == 0) { redirect(base_url()); }
    }

    public function index()
    {
        $data['company'] = $this->ModelGlobal->getCompany();
        $data['contentMenu'] = "menu/reporting_recapitulation_attendance";
        $this->load->view('menu/index', $data);
    }
    
    public function listData()
    {
        $user_name              = $this->session->userdata('user_name');
        
        $from_date              = filter_var(trim($this->input->post('from_date')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $to_date                = filter_var(trim($this->input->post('to_date')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $load_type              = filter_var(trim($this->input->post('load_type')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $load_type_name         = filter_var(trim($this->input->post('load_type_name')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        
        $now                    = date("Y-m-d H:i:s");

        if(!$user_name) { echo json_encode(['status' => false, 'message' => 'Field user_name is required']); die(); }
        if(!$from_date) { echo json_encode(['status' => false, 'message' => 'Field from_date is required']); die(); }
        if(!$to_date) { echo json_encode(['status' => false, 'message' => 'Field to_date is required']); die(); }
        if(!$load_type) { echo json_encode(['status' => false, 'message' => 'Field load_type is required']); die(); }
        if(!$load_type_name) { echo json_encode(['status' => false, 'message' => 'Field load_type_name is required']); die(); }
        
        $user_name              = $this->secure->decrypt_string($user_name);
        $load_type              = $this->secure->decrypt_string($load_type);
        $company                = $this->ModelGlobal->getCompany();

        $from_date              = date('Y-m-d', strtotime($from_date));
        $to_date                = date('Y-m-d', strtotime($to_date));
        
        $result = $this->ModelReportingRecapitulationAttendance->listData($load_type);
        if(!$result->result_array()) {
            $output_report = '<label class="text-center my-2">DATA NOT FOUND</label>';
        }
        else {

            $date1          = date_create($from_date);
            $date2          = date_create($to_date);
            $diff           = date_diff($date1,$date2);
            $total_days     = $diff->format("%a");

            $col_header     = "" ;

            $date           = $from_date;
            for($a = 0; $a <= $total_days; $a++) {
                if($a == 0) {
                    $col_header_date    = date('d-m-Y', strtotime($from_date));
                }
                else {
                    $date               = date_create($date);
                    date_add($date,date_interval_create_from_date_string("1 days"));
                    $col_header_date    = date_format($date,"d-m-Y");
                    $date               = date_format($date,"Y-m-d");
                }
                
                $col_header = $col_header . '<th class="text-right p-1" style="vertical-align: middle;">' . $col_header_date . '</th>';
            }

            $output_report  = '     
            <div class="row mb-3">    
                <div class="col-sm-12 text-center">
                    <img src="' . $company['url_logo'] . '" width="200px"><br>
                    <label class="text-gold mt-2">' . $company['company_name'] . '</label><br>
                    <h3 class="mb-0 text-red">REKAPITULASI ABSENSI KENDARAAN</h3>
                </div>
            </div>
            <div class="row mb-3">    
                <div class="col-sm-12 text-center">
                    <label class="mb-0">PERIODE : ' . date('d-m-Y', strtotime($from_date)) . ' s/d ' . date('d-m-Y', strtotime($to_date)) . '</label><br>
                    <label class="mb-0">JENIS MUATAN : ' . $load_type_name . '</label><br>
                </div>
            </div>
            <div class="row">    
                <div class="col-12 table-responsive">
                    <table class="table w-100" style="font-size: 11px">
                        <thead>
                            <tr>
                                <th class="text-center p-1" style="vertical-align: middle;">#</th>
                                <th class="text-left p-1" style="vertical-align: middle;">Nopol</th>
                                <th class="text-center p-1" style="vertical-align: middle;">Tahun Kendaraan</th>
                                <th class="text-center p-1" style="vertical-align: middle;">Kapasitas</th>
                                ' . $col_header . '
                                <th class="text-right p-1" style="vertical-align: middle;">Kal</th>
                                <th class="text-right p-1" style="vertical-align: middle;">Total ON</th>
                                <th class="text-right p-1" style="vertical-align: middle;">Total OFF</th>
                                <th class="text-right p-1" style="vertical-align: middle;">KPI</th>
                            </tr>
                        </thead>
                        <tbody>';
                
            $i              = 0;
            foreach ($result->result_array() as $row) {
                
                $i              = $i + 1;

                if($i == 1) {
                    $output_report = $output_report . '<tr>
                        <td class="text-left p-1" colspan="8"><b>DEPOT ' . $row['depot_name'] . '</b></td>
                    </tr>';
                }
                else {
                    if($row['depot_name'] <> $depot_name) {
                        $output_report = $output_report . '<tr>
                            <td class="text-left p-1" colspan="8"><b>DEPOT ' . $row['depot_name'] . '</b></td>
                        </tr>';
                    }
                }

                $depot_name     = $row['depot_name'];
                $col_detail     = '';
                $total_on       = 0;
                $total_off      = 0;

                $date           = $from_date;
                for($a = 0; $a <= $total_days; $a++) {
                    if($a == 0) {
                        $col_detail_date    = date('Y-m-d', strtotime($from_date));
                    }
                    else {
                        $date               = date_create($date);
                        date_add($date,date_interval_create_from_date_string("1 days"));
                        $col_detail_date    = date_format($date,"Y-m-d");
                        $date               = date_format($date,"Y-m-d");
                    }

                    $result_attendance = $this->ModelReportingRecapitulationAttendance->getData($col_detail_date, $row['idx']);
                    if(!$result_attendance) {
                        $total_off      = $total_off + 1;
                        $col_detail = $col_detail . '<td class="text-center p-1 bg-danger text-white" style="vertical-align: middle;">!</td>';
                    }
                    else {
                        if($result_attendance['attendance_flag'] == 0) { 
                            $total_off      = $total_off + 1;
                            $status_color   = "danger"; 
                        }
                        else { 
                            $total_on       = $total_on + 1;
                            $status_color   = "primary"; 
                        }

                        $col_detail = $col_detail . '<td class="text-center p-1 text-' . $status_color . '" style="vertical-align: middle;">' . $result_attendance['attendance_code'] . '</td>';
                    }

                    
                }

                $kpi        = ($total_on / ($total_on + $total_off)) * 100;
                $output_report = $output_report . '<tr>
                        <td class="text-center p-1">' . $i . '.</td>
                        <td class="text-left p-1">' . $row['vehicle_no'] . '</td>
                        <td class="text-center p-1">' . $row['vehicle_year'] . '</td>
                        <td class="text-center p-1">' . $row['weight_capacity'] . '</td>
                        ' . $col_detail . '
                        <td class="text-right p-1">' . number_format($total_days + 1) . '</td>
                        <td class="text-right p-1">' . number_format($total_on) . '</td>
                        <td class="text-right p-1">' . number_format($total_off) . '</td>
                        <td class="text-right p-1">' . number_format($kpi) . ' %</td>
                      </tr>';   

            }

            $output_report = $output_report . '
                        </tbody>
                    </table>
                </div>
            </div>';
        }
            
        $output_html = $output_report;
        echo json_encode(['status' => true, 'message' => 'Data is available', 'output_html' => $output_html]); die();
    }
}
?>
