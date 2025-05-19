<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ModelAuth extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
		$this->load->model('ModelGlobal');
    }

    public function getUserLogin($user_id)
    {
        $result = $this->db->query("select
                                        idx,
                                        user_id,
                                        user_name,
                                        employee_id,
                                        address,
                                        email_id,
                                        mobile_phone,
                                        status_password,
                                        password_apps,
                                        token_apps,
                                        office_id,
                                        (select office_name from master_office where idx = user_account.office_id) as office_name,
                                        (select company_idx from master_employee where employee_id = user_account.employee_id) as company_idx,
                                        status
                                    from user_account 
                                    where user_id = '" . $user_id . "' and (user_type = 2)")->row_array();
        return $result;
    }

    public function getUserAfterLogin($user_id, $token_apps)
    {
        $result = $this->db->query("select
                                        idx,
                                        user_id,
                                        user_name,
                                        employee_id,
                                        address,
                                        email_id,
                                        mobile_phone,
                                        status_password,
                                        password_apps,
                                        token_apps,
                                        office_id,
                                        (select employee_code from master_employee where employee_id = user_account.employee_id) as employee_code,
                                        (select office_name from master_office where idx = user_account.office_id) as office_name,
                                        (select address from master_office where idx = user_account.office_id) as office_address,
                                        (select company_idx from master_employee where employee_id = user_account.employee_id) as company_idx,
                                        (select master_company.company_name from master_company, master_employee where master_company.idx = master_employee.company_idx and master_employee.employee_id = user_account.employee_id) as company_name,
                                        (select master_company.address from master_company, master_employee where master_company.idx = master_employee.company_idx and master_employee.employee_id = user_account.employee_id) as company_address,
                                        status
                                    from user_account 
                                    where user_id = '" . $user_id . "' and (user_type = 2) and token_apps = '" . $token_apps . "'")->row_array();
        return $result;
    }

	public function getAccessMenu($level_access_idx)
    {
        $result = $this->db->query("select *
                                    from user_access_level_menu
                                    where level_access_idx = " . $level_access_idx . " and status = 1")->row_array();
        return $result;
    }
    
    public function updateUser($update_user, $user_idx)
    {
        $this->db->trans_begin();
        
        $this->db->where('idx', $user_idx);
        $this->db->update('user_account', $update_user);

    	if($this->db->trans_status() === FALSE) {
    		$this->db->trans_rollback();
    		return 0;
    	}
    	else {
    		$this->db->trans_commit();
    		return 1;
    	}
    }
    
    public function updateNewPasswordUser($update_user, $user_id)
    {
        $this->db->trans_begin();
        
        $this->db->where('user_id', $user_id);
        $this->db->where_in('user_type', [99, 2]);
        $this->db->update('user_account', $update_user);

    	if($this->db->trans_status() === FALSE) {
    		$this->db->trans_rollback();
    		return 0;
    	}
    	else {
    		$this->db->trans_commit();
    		return 1;
    	}
    }
}
?>
