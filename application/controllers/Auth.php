<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('secure');
        $this->load->model('ModelAuth');
        $this->load->model('ModelGenId');
		$this->load->model('ModelGlobal');
    }

    public function index()
    {
        if($this->session->userdata('user_idx')) { redirect('Home'); }
        else {
            $data['company'] = $this->ModelGlobal->getCompany();
            $data['contentAuth'] = 'login';
            $this->load->view('auth/index', $data);
        }
    }

    public function login()
    {
        $user_id    = filter_var(trim($this->input->post('user_id')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        
        if(!$user_id) { echo json_encode(['status' => false, 'message' => 'User ID is required!']); die(); }

        $getUserLogin = $this->ModelAuth->getUserLogin($user_id);
        if(!$getUserLogin) { echo json_encode(['status' => false, 'message' => 'Akun tidak terdaftar!']); die(); }
        
        // jika status tidak sama dengan satu atau tidak aktif
        if($getUserLogin['status'] <> 1) { echo json_encode(['status' => false, 'message' => 'Akun tidak aktif!']); die(); }
                    
        $data_result = [
            'user_id'           => $getUserLogin['user_id'],
            'status_password'   => $getUserLogin['status_password']
        ];
        
        echo json_encode(['status' => true, 'message' => 'Login is successfully', 'data' => $data_result]); die();
    }
    
    public function loginPassword()
    {
        $user_id            = filter_var(trim($this->input->post('user_id_login')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $password           = filter_var(trim($this->input->post('password_login')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $token_apps         = filter_var(trim($this->input->post('token_apps_login')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        
        if(!$user_id) { echo json_encode(['status' => false, 'message' => 'Field user_id is required!']); die(); }
        if(!$password) { echo json_encode(['status' => false, 'message' => 'Field password is required!']); die(); }
        if(!$token_apps) { echo json_encode(['status' => false, 'message' => 'Field token_apps is required!']); die(); }

        $getUserLogin = $this->ModelAuth->getUserLogin($user_id);
        if(!$getUserLogin) { echo json_encode(['status' => false, 'message' => 'Akun tidak terdaftar!']); die(); }
        
        if(password_verify($password, $getUserLogin['password_apps']) === FALSE ) { echo json_encode(['status' => false, 'message' => 'Password salah!']); die(); }
        
        if($getUserLogin['status'] <> 1) { echo json_encode(['status' => false, 'message' => 'Akun tidak aktif!']); die(); }

        if($getUserLogin['token_apps'] <> $token_apps) { echo json_encode(['status' => false, 'message' => 'ID perangkat tidak sesuai!']); die(); }
                    
        $data_session = [
            'user_idx'          => $this->secure->encrypt_string($getUserLogin['idx']),
            'user_id'           => $this->secure->encrypt_string($getUserLogin['user_id']),
            'user_name'         => $this->secure->encrypt_string($getUserLogin['user_name']),
            'office_idx'        => $this->secure->encrypt_string($getUserLogin['office_id']),
            'office_name'       => $this->secure->encrypt_string($getUserLogin['office_name']),
            'company_idx'       => $this->secure->encrypt_string($getUserLogin['company_idx']),
            'employee_id'       => $this->secure->encrypt_string($getUserLogin['employee_id']),
            'user_address'      => $this->secure->encrypt_string($getUserLogin['address']),
            'user_email'        => $this->secure->encrypt_string($getUserLogin['email_id']),
            'user_phone'        => $this->secure->encrypt_string($getUserLogin['mobile_phone']),
            'token_apps'        => $this->secure->encrypt_string($token_apps)
        ];
        $this->session->set_userdata($data_session);
        
        echo json_encode(['status' => true, 'message' => 'Login is successfully']); die();
    }
    
    public function createNewPassword()
    {
        $user_id            = filter_var(trim($this->input->post('create_password_user_id')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $new_password       = filter_var(trim($this->input->post('create_password_new')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $confirm_password   = filter_var(trim($this->input->post('create_password_confirm')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $token_apps         = filter_var(trim($this->input->post('token_apps_newpass')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $now                = date("Y-m-d H:i:s");
        
        if(!$user_id) { echo json_encode(['status' => false, 'message' => 'Field user_id is required!']); die(); }
        if(!$new_password) { echo json_encode(['status' => false, 'message' => 'Field new_password is required!']); die(); }
        if(!$confirm_password) { echo json_encode(['status' => false, 'message' => 'Field confirm_password is required!']); die(); }
        if(!$token_apps) { echo json_encode(['status' => false, 'message' => 'Field token_apps is required!']); die(); }
        
        $getUserLogin = $this->ModelAuth->getUserLogin($user_id);
        if(!$getUserLogin) { echo json_encode(['status' => false, 'message' => 'Akun tidak terdaftar!']); die(); }
        
        if($new_password !== $confirm_password) { echo json_encode(['status' => false, 'message' => 'Konfirmasi sandi tidak sama!']); die(); }
        
        $update_user = [
            'password_apps'         => password_hash($confirm_password, PASSWORD_DEFAULT),
            'token_apps'            => $token_apps,
            'status_password'       => 1,
            'modified_on'           => $now,
            'modified_by'           => $user_id
        ];
                    
        $result = $this->ModelAuth->updateNewPasswordUser($update_user, $user_id);
        if($result == 0) { echo json_encode(['status' => false, 'message' => 'Buat sandi baru gagal']); die(); }
        
        echo json_encode(['status' => true, 'message' => 'Buat sandi baru berhasil, silahkan login']); die();
    }
    
    public function selectOffice()
    {
        $user_idx       = $this->session->userdata('user_idx');
        $office_idx     = filter_var(trim($this->input->post('office_idx')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $office_name    = filter_var(trim($this->input->post('office_name')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $now            = date("Y-m-d H:i:s");
        
        if(!$user_idx) { echo json_encode(['status' => false, 'message' => 'User Idx is required']); die(); }
        if(!$office_idx) { echo json_encode(['status' => false, 'message' => 'Office Idx is required!']); die(); }
        if(!$office_name) { echo json_encode(['status' => false, 'message' => 'Office Name is required!']); die(); }
        
        $user_idx       = $this->secure->decrypt_string($user_idx);
        
        $update_user = [
            'office_idx'            =>  $this->secure->decrypt_string($office_idx),
            'modified_on'           => $now,
            'modified_by'           => $user_idx
        ];
                    
        $result = $this->ModelAuth->updateUser($update_user, $user_idx);
        if($result == 0) { echo json_encode(['status' => false, 'message' => 'Update Office User fail, please try again']); die(); }

        $data_session = [
            'office_idx'        => $office_idx,
            'office_name'       => $this->secure->encrypt_string($office_name)
        ];
        $this->session->set_userdata($data_session);
        
        echo json_encode(['status' => true, 'message' => 'Change Office is successfully']); die();
    }
    
    public function submitChangePassword()
    {
        $user_idx           = $this->session->userdata('user_idx');
        $user_id            = $this->session->userdata('user_id');
        $old_password       = filter_var(trim($this->input->post('old_password')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $new_password       = filter_var(trim($this->input->post('new_password')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $confirm_password   = filter_var(trim($this->input->post('new_password')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $now                = date("Y-m-d H:i:s");
        
        if(!$user_idx) { echo json_encode(['status' => false, 'message' => 'User Idx is required!']); die(); }
        if(!$user_id) { echo json_encode(['status' => false, 'message' => 'User Id is required!']); die(); }
        if(!$old_password) { echo json_encode(['status' => false, 'message' => 'Old Password is required!']); die(); }
        if(!$new_password) { echo json_encode(['status' => false, 'message' => 'New Password is required!']); die(); }
        if(!$confirm_password) { echo json_encode(['status' => false, 'message' => 'Confirm Password is required!']); die(); }
        
        $user_idx           = $this->secure->decrypt_string($user_idx);
        $user_id            = $this->secure->decrypt_string($user_id);
        
        $getUserLogin = $this->ModelAuth->getUserLogin($user_id);
        if(!$getUserLogin) { echo json_encode(['status' => false, 'message' => 'User is not registered!']); die(); }

        if(password_verify($old_password, $getUserLogin['password']) === FALSE ) { echo json_encode(['status' => false, 'message' => 'Old Password is wrong!']); die(); }
        
        if($new_password !== $confirm_password) { echo json_encode(['status' => false, 'message' => 'Confirm Password does not match!']); die(); }
        
        $update_user = [
            'password'              => password_hash($confirm_password, PASSWORD_DEFAULT),
            'modified_on'           => $now,
            'modified_by'           => $user_idx
        ];
                    
        $result = $this->ModelAuth->updateUser($update_user, $user_idx);
        if($result == 0) { echo json_encode(['status' => false, 'message' => 'Change Password fail, please try again']); die(); }
        
        echo json_encode(['status' => true, 'message' => 'Change Office is successfully']); die();
    }
    
    private function _sendEmail($token, $type)
    {
        $config = [
            'protocol'  => 'smtp',
            'smtp_host' => 'qoorier.id',
            'smtp_user' => 'cs@qoorier.id',
            'smtp_pass' => 'cs@qOO123',
            'smtp_port' => 587,//465,
            'mailtype'  => 'html',
            'charset'   => 'utf-8',
            'newline'   => "\r\n"
        ];

        $this->email->initialize($config);

        $this->email->from('cs@qoorier.id', 'Qoorier');
        $this->email->to($this->input->post('signup-email'));

        if ($type == 'verify') {
            $this->email->subject('Account Verification');
            $this->email->message('Click this link to Qoorier verify you account : <a href="' . base_url() . 'auth/verify?email=' . $this->input->post('signup-email') . '&token=' . urlencode($token) . '">Activate</a>');
        } 
        else if ($type == 'forgot') {
            $this->email->subject('Reset Password');
            $this->email->message('Click this link to reset Qoorier your password : <a href="' . base_url() . 'auth/newpass/' . $token . '">Reset Password</a>');
        }

        if ($this->email->send()) { return true; } 
        else { return false; }
    }



    public function logout()
    {
        // $this->session->unset_userdata('user_idx');
        // $this->session->unset_userdata('user_id');
        // $this->session->unset_userdata('user_name');
        // $this->session->unset_userdata('user_type');
        // $this->session->unset_userdata('user_level_id');
        // $this->session->unset_userdata('user_email');
        // $this->session->unset_userdata('user_phone');
        $this->session->sess_destroy();
        
        redirect('Auth');
        
    }
}
?>
