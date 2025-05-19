<?php 
defined('BASEPATH') or exit('No direct script access allowed');

class ModelHome extends CI_Model
{
    public function getDataEmployee()
    {
        date('Y-m-d');
        $result = $this->db->query("SELECT office_shift AS office_shift,
                                            employee_name AS employee_name,
                                            photo AS photo,
                                            attendance_radius AS attendance_radius,
                                            (SELECT idx FROM attendance_employee WHERE DATE(attendance_date) = '" . date('Y-m-d') . "' AND employee_id = master_employee.employee_id) AS attendance_idx,
                                            (SELECT TIME(check_in) FROM attendance_employee WHERE DATE(attendance_date) = '" . date('Y-m-d') . "' AND employee_id = master_employee.employee_id) AS check_in,
                                            (SELECT TIME(check_out) FROM attendance_employee WHERE DATE(attendance_date) = '" . date('Y-m-d') . "' AND employee_id = master_employee.employee_id) AS check_out,
                                            (SELECT status_overtime FROM attendance_employee WHERE DATE(attendance_date) = '" . date('Y-m-d') . "' AND employee_id = master_employee.employee_id) AS status_overtime,
                                            (SELECT reason_overtime FROM attendance_employee WHERE DATE(attendance_date) = '" . date('Y-m-d') . "' AND employee_id = master_employee.employee_id) AS reason_overtime,
                                            (SELECT status_permission FROM attendance_employee WHERE DATE(attendance_date) = '" . date('Y-m-d') . "' AND employee_id = master_employee.employee_id) AS status_permission,
                                            (SELECT remarks FROM attendance_employee WHERE DATE(attendance_date) = '" . date('Y-m-d') . "' AND employee_id = master_employee.employee_id) AS remarks
                                    FROM master_employee 
                                    WHERE employee_id = " . $this->secure->decrypt_string($this->session->userdata('employee_id')) . " AND 
                                            status = 1")->row_array();
        return $result;
    }

    public function getDataOffice()
    {
        date('Y-m-d');
        $result = $this->db->query("SELECT *
                                    FROM master_office 
                                    WHERE idx = " . $this->secure->decrypt_string($this->session->userdata('office_idx')) . " AND 
                                            status = 1")->row_array();
        return $result;
    }

    public function getAttendanceEmployee($attendance_idx)
    {
        date('Y-m-d');
        $result = $this->db->query("SELECT target_out AS target_out,
                                            (SELECT attendance_radius FROM master_employee WHERE employee_id = attendance_employee.employee_id) AS radius
                                    FROM attendance_employee 
                                    WHERE idx = " . $attendance_idx . "")->row_array();
        return $result;
    }

    public function checkShitEmployee($employee_id)
    {
        switch (date('D')) {
            case "Mon":
                $field_check_in     = '(SELECT monday_in FROM master_shift WHERE idx = master_employee.office_shift)';
                $field_check_out    = '(SELECT monday_out FROM master_shift WHERE idx = master_employee.office_shift)';
                break;
            case "Tue":
                $field_check_in     = '(SELECT tuesday_in FROM master_shift WHERE idx = master_employee.office_shift)';
                $field_check_out    = '(SELECT tuesday_out FROM master_shift WHERE idx = master_employee.office_shift)';
                break;
            case "Wed":
                $field_check_in     = '(SELECT wednesday_in FROM master_shift WHERE idx = master_employee.office_shift)';
                $field_check_out    = '(SELECT wednesday_out FROM master_shift WHERE idx = master_employee.office_shift)';
                break;
            case "Thu":
                $field_check_in     = '(SELECT thursday_in FROM master_shift WHERE idx = master_employee.office_shift)';
                $field_check_out    = '(SELECT thursday_out FROM master_shift WHERE idx = master_employee.office_shift)';
                break;
            case "Fri":
                $field_check_in     = '(SELECT friday_in FROM master_shift WHERE idx = master_employee.office_shift)';
                $field_check_out    = '(SELECT friday_out FROM master_shift WHERE idx = master_employee.office_shift)';
                break;
            case "Sat":
                $field_check_in     = '(SELECT saturday_in FROM master_shift WHERE idx = master_employee.office_shift)';
                $field_check_out    = '(SELECT saturday_out FROM master_shift WHERE idx = master_employee.office_shift)';
                break;
            case "Sun":
                $field_check_in     = '(SELECT sunday_in FROM master_shift WHERE idx = master_employee.office_shift)';
                $field_check_out    = '(SELECT sunday_out FROM master_shift WHERE idx = master_employee.office_shift)';
                break;
            default:
                $field_check_in     = '';
                $field_check_out    = '';
        }

        $result = $this->db->query("SELECT office_shift AS shift_idx,
                                            attendance_radius AS radius,
                                            " . $field_check_in . " AS target_in,
                                            " . $field_check_out . " AS target_out,
                                            (SELECT work_time FROM master_shift WHERE idx = master_employee.office_shift) AS work_time
                                    FROM master_employee 
                                    WHERE employee_id = " . $employee_id . " AND 
                                            status = 1")->row_array();
        return $result;
    }

    public function getDataShiftEmployee($idx)
    {
        switch (date('D')) {
            case "Mon":
                $field_check_in     = 'monday_in';
                $field_check_out    = 'monday_out';
                break;
            case "Tue":
                $field_check_in     = 'tuesday_in';
                $field_check_out    = 'tuesday_out';
                break;
            case "Wed":
                $field_check_in     = 'wednesday_in';
                $field_check_out    = 'wednesday_out';
                break;
            case "Thu":
                $field_check_in     = 'thursday_in';
                $field_check_out    = 'thursday_out';
                break;
            case "Fri":
                $field_check_in     = 'friday_in';
                $field_check_out    = 'friday_out';
                break;
            case "Sat":
                $field_check_in     = 'saturday_in';
                $field_check_out    = 'saturday_out';
                break;
            case "Sun":
                $field_check_in     = 'sunday_in';
                $field_check_out    = 'sunday_out';
                break;
            default:
                $field_check_in     = '';
                $field_check_out    = '';
        }

        $result = $this->db->query("SELECT shift_name AS shift_name,
                                        DATE_FORMAT(" . $field_check_in . ", '%H:%i') AS shift_check_in,
                                        DATE_FORMAT(" . $field_check_out . ", '%H:%i') AS shift_check_out
                                    FROM master_shift 
                                    WHERE idx = " . $idx . "")->row_array();
        return $result;
    }

    public function insertCheckIn($insert_attendance_employee)
    {
        $this->db->trans_begin();

    	$this->db->insert('attendance_employee', $insert_attendance_employee);

    	if($this->db->trans_status() === FALSE) {
    		$this->db->trans_rollback();
    		return 0;
    	}
    	else {
    		$this->db->trans_commit();
    		return 1;
    	}
    }

    public function updateCheckOut($update_attendance_employee, $attendance_idx)
    {
        $this->db->trans_begin();

    	$this->db->where('idx', $attendance_idx);
        $this->db->update('attendance_employee', $update_attendance_employee);

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