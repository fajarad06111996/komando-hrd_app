<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PermissionRequest extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->library('secure');
        $this->load->model('ModelGenId');
        $this->load->model('ModelGlobal');
        $this->load->model('ModelPermissionRequest');
    }

    public function index()
    {
        $data['company'] = $this->ModelGlobal->getCompany();
        $data['contentMenu'] = "menu/permission_request";
        $this->load->view('menu/index', $data);
    }

    public function listDataPermission()
    {
        $month_period           = filter_var(trim($this->input->post('month_period')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $year_period            = filter_var(trim($this->input->post('year_period')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        
        if(!$month_period) { echo json_encode(['status' => false, 'message' => 'Field month_period is required']); die(); }
        if(!$year_period) { echo json_encode(['status' => false, 'message' => 'Field year_period is required']); die(); }
        
        if(!preg_match('/^[0-9]*$/', $month_period)) { echo json_encode(['status' => false, 'message' => 'Field month_period is invalid']); die(); }
        if(!preg_match('/^[0-9]*$/', $year_period)) { echo json_encode(['status' => false, 'message' => 'Field year_period is invalid']); die(); }

        $result = $this->ModelPermissionRequest->listDataPermission($month_period, $year_period);
        if(!$result) { echo json_encode(['status' => false, 'message' => 'Data izin tidak ditemukan!']); die(); }
        
        // tampil data ijin kerja sesuai dengan id karyawan
        $data               = "";
        $formatterDateFull  = new IntlDateFormatter('id_ID', IntlDateFormatter::FULL, IntlDateFormatter::NONE);
        $formatter          = new IntlDateFormatter('id_ID', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
        foreach ($result as $row) {
            $start_date = new DateTime($row['start_date']);
            $end_date   = new DateTime($row['end_date']);

            if($row['status'] == 0) { 
                $status_color   = "text-danger"; 
                $btn_edit       = '';
            }
            else if($row['status'] == 1) { 
                $status_color   = "text-primary"; 
                $btn_edit       = '<button type="button" class="btn btn-primary btn-rounded w-100 mb-2" onclick="getEditPermission(`' . $this->secure->encrypt_string($row['idx']) . '`)"><i class="fas fa-edit"></i> Edit</button>';
            }
            else if($row['status'] == 2) { 
                $status_color   = "text-success"; 
                $btn_edit       = '';
            }
            else if($row['status'] == 99){
                $status_color   = "text-danger";
                $btn_edit       = '';
            }

            if($row['url_attachment'] == null || $row['url_attachment'] == '') { $attachment = '<b>Tidak ada lampiran</b>'; }
            else { $attachment = '<b class="text-info" onclick="getAttachment(`' . $row['url_attachment'] . '`)"><i class="fas fa-cloud-download-alt"></i> Unduh Lampiran</b>'; }
            
            $data = $data . '<div class="listdata">
                    <div class="row px-3 align-items-center bg-white">
                        <div class="media-body">
                            <h5 class="mb-1">
                                <small>
                                    <i>
                                        ' . $formatterDateFull->format($start_date) .'
                                    </i>
                                </small>
                            </h5>
                            <p class="text-sm mb-2 font-keren">
                                <i>Status : <b class="' . $status_color . '">' . $row['status_name'] . '</b></i><br>
                                <i>No Izin : <b>' . $row['permission_no'] . '</b></i><br>
                                <i>Tanggal : <b>' . $formatter->format($start_date) . ' s/d ' . $formatter->format($end_date) . '</b></i><br>
                                <i>Alasan : <b>' . $row['permission_type_name'] . '</b></i><br>
                                <i>Lampiran : ' . $attachment . '</i><br>
                                <i>Keterangan :<br><b>' . $row['remarks'] . '</b></i>
                            </p>
                            ' . $btn_edit . '
                        </div>
                    </div>
                    <hr>
                </div>';  

        }

        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }

    public function getDataEmployeePermission()
    {
        $idx = filter_var(trim($this->input->post('idx')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        
        if(!$idx) { echo json_encode(['status' => false, 'message' => 'Field idx is required']); die(); }
        
        $idx = $this->secure->decrypt_string($idx);

        if(!preg_match('/^[0-9]*$/', $idx)) { echo json_encode(['status' => false, 'message' => 'Field idx is invalid']); die(); }

        $result = $this->ModelPermissionRequest->getDataEmployeePermission($idx);
        if(!$result) { echo json_encode(['status' => false, 'message' => 'Data izin tidak ditemukan!']); die(); }
        
        $data_array = [];
        $data_array['idx']                      = $this->secure->encrypt_string($result['idx']);
        $data_array['permission_no']            = $result['permission_no'];
        $data_array['from_date']                = $result['from_date'];
        $data_array['to_date']                  = $result['to_date'];
        $data_array['permission_type']          = $this->secure->encrypt_string($result['permission_type']);
        $data_array['permission_type_name']     = $result['permission_type_name'];
        $data_array['remarks']                  = $result['remarks'];
        $data_array['url_attachment']           = $result['url_attachment'];
        $data_array['filename_attachment']      = $result['filename_attachment'];
        $data = $data_array;

        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }

    public function listPermissionType()
    {
        $result = $this->ModelPermissionRequest->listPermissionType();
        if (!$result) { $data = ""; }
        else {
                                        
            $i = 0;
            foreach ($result as $row) {
                $data[$i] = [
                    'status'        => $this->secure->encrypt_string($row['status']),
                    'status_name'   => $row['status_name']
                ];
                $i = $i + 1;
            }
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }

    public function submitEntry()
    {
        $user_idx               = $this->session->userdata('user_idx');
        $company_idx            = $this->session->userdata('company_idx');
        $office_idx             = $this->session->userdata('office_idx');
        $employee_id            = $this->session->userdata('employee_id');
        
        $entry_type             = filter_var(trim($this->input->post('entry_type')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $idx                    = filter_var(trim($this->input->post('idx')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $from_date              = filter_var(trim($this->input->post('from_date')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $to_date                = filter_var(trim($this->input->post('to_date')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $permission_type        = filter_var(trim($this->input->post('permission_type')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $remarks                = filter_var(trim($this->input->post('remarks')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $url_attachment         = filter_var(trim($this->input->post('url_attachment')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $filename_attachment    = filter_var(trim($this->input->post('filename_attachment')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        
        $now                    = date("Y-m-d H:i:s");

        if(!$user_idx) { echo json_encode(['status' => false, 'message' => 'Field user_idx is required']); die(); }
        if(!$company_idx) { echo json_encode(['status' => false, 'message' => 'Field company_idx is required']); die(); }
        if(!$office_idx) { echo json_encode(['status' => false, 'message' => 'Field office_idx is required']); die(); }
        if(!$employee_id) { echo json_encode(['status' => false, 'message' => 'Field employee_id is required']); die(); }
        
        if(!$entry_type) { echo json_encode(['status' => false, 'message' => 'Field entry_type is required']); die(); }
        if(!$from_date) { echo json_encode(['status' => false, 'message' => 'Field from_date is required']); die(); }
        if(!$to_date) { echo json_encode(['status' => false, 'message' => 'Field to_date is required']); die(); }
        if(!$permission_type) { echo json_encode(['status' => false, 'message' => 'Field permission_type is required']); die(); }
        
        $user_idx               = $this->secure->decrypt_string($user_idx);
        $company_idx            = $this->secure->decrypt_string($company_idx);
        $office_idx             = $this->secure->decrypt_string($office_idx);
        $employee_id            = $this->secure->decrypt_string($employee_id);
        
        $idx                    = $this->secure->decrypt_string($idx);
        $permission_type        = $this->secure->decrypt_string($permission_type);

        if(!preg_match('/^[0-9]*$/', $user_idx)) { echo json_encode(['status' => false, 'message' => 'Field user_idx is invalid']); die(); }
        if(!preg_match('/^[0-9]*$/', $company_idx)) { echo json_encode(['status' => false, 'message' => 'Field company_idx is invalid']); die(); }
        if(!preg_match('/^[0-9]*$/', $office_idx)) { echo json_encode(['status' => false, 'message' => 'Field office_idx is invalid']); die(); }
        if(!preg_match('/^[0-9]*$/', $employee_id)) { echo json_encode(['status' => false, 'message' => 'Field employee_id is invalid']); die(); }
        
        if(!preg_match('/^[0-9]*$/', $idx)) { echo json_encode(['status' => false, 'message' => 'Field idx is invalid']); die(); }
        if(!preg_match('/^[0-9]*$/', $permission_type)) { echo json_encode(['status' => false, 'message' => 'Field permission_type is invalid']); die(); } 

        if($entry_type == 1) {
            $gen_permission_no      = $this->ModelGenId->genIdYear('IZIN', $user_idx);
            $permission_no          = str_pad($gen_permission_no, 4, "0", STR_PAD_LEFT) . '/IZIN/' . $this->ModelGlobal->getMonthRomawi(date('m', strtotime($from_date))) . '/' . date('Y');

            $insert_permission_employee = [
                'permission_no'         => $permission_no,
                'permission_type'       => $permission_type,
                'start_date'            => date('Y-m-d', strtotime($from_date)),
                'end_date'              => date('Y-m-d', strtotime($to_date)),
                'employee_id'           => $employee_id,
                'url_attachment'        => $url_attachment,
                'filename_attachment'   => $filename_attachment,
                'remarks'               => $remarks,
                'company_idx'           => $company_idx,
                'office_idx'            => $office_idx,
                'status'                => 1,
                'created_on'            => $now,
                'created_by'            => $user_idx
            ];
                        
            $result = $this->ModelPermissionRequest->insertNew($insert_permission_employee);
            if($result == 0) { echo json_encode(['status' => false, 'message' => 'Tambah izin baru gagal!']); die(); }
                
            echo json_encode(['status' => true, 'message' => 'Tambah izin baru berhasil']); die();
        }
        else if($entry_type == 2) {
            $update_permission_employee = [
                'permission_type'       => $permission_type,
                'start_date'            => date('Y-m-d', strtotime($from_date)),
                'end_date'              => date('Y-m-d', strtotime($to_date)),
                'url_attachment'        => $url_attachment,
                'filename_attachment'   => $filename_attachment,
                'remarks'               => $remarks,
                'modified_on'           => $now,
                'modified_by'           => $user_idx
            ];
                        
            $result = $this->ModelPermissionRequest->updateData($update_permission_employee, $idx);
            if($result == 0) { echo json_encode(['status' => false, 'message' => 'Update izin gagal!']); die(); }
                
            echo json_encode(['status' => true, 'message' => 'Update izin berhasil']); die();
        }
        
    }

}
?>
