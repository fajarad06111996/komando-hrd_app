<?php 
defined('BASEPATH') or exit('No direct script access allowed');

class ModelHistoryAttendance extends CI_Model
{
    public function getDataAttendance($month_attendance, $year_attendance)
    {
        date('Y-m-d');
        $result = $this->db->query("SELECT attendance_date AS attendance_date,
                                            check_in AS check_in,
                                            check_out AS check_out,
                                            target_in AS target_in,
                                            target_out AS target_out,
                                            DATE(check_in) AS date_check_in,
                                            DATE(check_out) AS date_check_out,
                                            TIME_FORMAT(check_in, '%H:%i') AS time_check_in,
                                            TIME_FORMAT(check_out, '%H:%i') AS time_check_out,
                                            TIME_FORMAT(target_in, '%H:%i') AS time_target_in,
                                            TIME_FORMAT(target_out, '%H:%i') AS time_target_out,
                                            (SELECT shift_name FROM master_shift WHERE idx = attendance_employee.shift_idx) AS shift_name,
                                            (SELECT photo FROM master_employee WHERE employee_id = attendance_employee.employee_id) AS url_photo_employee
                                    FROM attendance_employee 
                                    WHERE employee_id = " . $this->secure->decrypt_string($this->session->userdata('employee_id')) . " AND
                                            MONTH(attendance_date) = '" . $month_attendance . "' AND 
                                            YEAR(attendance_date) = '" . $year_attendance . "'
                                    ORDER BY attendance_date DESC")->result_array();
        return $result;
    }
}
?>