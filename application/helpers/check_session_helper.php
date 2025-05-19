<?php 

function is_logged_in() {
    
    $ci = get_instance();
    $ci->load->model('ModelAuth');
    $ci->load->library('secure');
    
    if (!$ci->session->userdata('user_idx')) {
        redirect('Auth/logout');
    }
    else {
        $user_id    = $ci->secure->decrypt_string($ci->session->userdata('user_id'));
        $token_apps = $ci->secure->decrypt_string($ci->session->userdata('token_apps'));
        
        $getUserLogin = $ci->ModelAuth->getUserAfterLogin($user_id, $token_apps);
        if(!$getUserLogin) { redirect(base_url('Auth/logout')); }

        $data_session = [
            'office_idx'        => $ci->secure->encrypt_string($getUserLogin['office_id']),
            'office_name'       => $ci->secure->encrypt_string($getUserLogin['office_name']),
            'office_address'    => $ci->secure->encrypt_string($getUserLogin['office_address']),
            'employee_code'     => $ci->secure->encrypt_string($getUserLogin['employee_code']),
            'company_idx'       => $ci->secure->encrypt_string($getUserLogin['company_idx']),
            'company_name'      => $ci->secure->encrypt_string($getUserLogin['company_name']),
            'company_address'   => $ci->secure->encrypt_string($getUserLogin['company_address'])
        ];
        $ci->session->set_userdata($data_session);
    }
}

?>