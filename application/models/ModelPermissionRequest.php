<?php 
defined('BASEPATH') or exit('No direct script access allowed');

class ModelPermissionRequest extends CI_Model
{
    public function listDataPermission($month_period, $year_period)
    {
        $result = $this->db->query("SELECT idx AS idx,
                                            permission_no AS permission_no,
                                            start_date AS start_date,
                                            end_date AS end_date,
                                            remarks AS remarks,
                                            url_attachment AS url_attachment,
                                            status AS status,
                                            start_date,
                                            end_date,
                                            (SELECT status_name FROM status_permission_type WHERE status = permission_employee.permission_type) AS permission_type_name,
                                            (SELECT status_name FROM status_permission_employee WHERE status = permission_employee.status) AS status_name
                                    FROM permission_employee 
                                    WHERE employee_id = " . $this->secure->decrypt_string($this->session->userdata('employee_id')) . " AND
                                            MONTH(start_date) = '" . $month_period . "' AND 
                                            YEAR(start_date) = '" . $year_period . "'
                                    ORDER BY start_date DESC")->result_array();
        return $result;
    }

    public function getDataEmployeePermission($idx)
    {
        $result = $this->db->query("SELECT idx AS idx,
                                            permission_no AS permission_no,
                                            DATE_FORMAT(start_date, '%d-%m-%Y') AS from_date,
                                            DATE_FORMAT(end_date, '%d-%m-%Y') AS to_date,
                                            permission_type AS permission_type,
                                            remarks AS remarks,
                                            url_attachment AS url_attachment,
                                            filename_attachment AS filename_attachment,
                                            status AS status,
                                            (SELECT status_name FROM status_permission_type WHERE status = permission_employee.permission_type) AS permission_type_name,
                                            (SELECT status_name FROM status_permission_employee WHERE status = permission_employee.status) AS status_name
                                    FROM permission_employee 
                                    WHERE idx = " . $idx . "")->row_array();
        return $result;
    }

    public function listPermissionType()
    {
        $result = $this->db->query("SELECT status AS status,
                                            status_name AS status_name
                                    FROM status_permission_type
                                    WHERE status > 0
                                    ORDER BY status ASC")->result_array();
        return $result;
    }

    public function insertNew($insert_permission_employee)
    {
        $this->db->trans_begin();

    	$this->db->insert('permission_employee', $insert_permission_employee);

    	if($this->db->trans_status() === FALSE) {
    		$this->db->trans_rollback();
    		return 0;
    	}
    	else {
    		$this->db->trans_commit();
    		return 1;
    	}
    }

    public function updateData($update_permission_employee, $idx)
    {
        $this->db->trans_begin();

    	$this->db->where('idx', $idx);
        $this->db->update('permission_employee', $update_permission_employee);

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