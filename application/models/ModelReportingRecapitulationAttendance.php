<?php 
defined('BASEPATH') or exit('No direct script access allowed');

class ModelReportingRecapitulationAttendance extends CI_Model
{
    public function listData($load_type)
    {
        if($load_type == 'ALL') { $filter_load_type = ""; }
        else { $filter_load_type = " and load_type = " . $load_type . ""; }

        $sql = "
        select idx as idx,
                vehicle_no as vehicle_no, 
                YEAR(vehicle_date) as vehicle_year,
                weight_capacity as weight_capacity,
                (select depot_name from master_depot where idx = master_vehicle.depot_idx) as depot_name
        from master_vehicle
        where status = 1 and
                (select office_idx from master_depot where idx = master_vehicle.depot_idx) = " . $this->secure->decrypt_string($this->session->userdata('office_idx')) . "
                " . $filter_load_type . "
        order by depot_name, vehicle_no asc
        ";
        $result = $this->db->query($sql);
        return $result;
    }

    public function getData($date, $vehicle_idx)
    {
        $sql = "
        select d.attendance_flag as attendance_flag,
                (select status_code from status_attendance where idx = d.attendance_type) as attendance_code
        from attendance_vehicle_detail d, attendance_vehicle h
        where d.attendance_id = h.attendance_id and
                h.status = 2 and
                h.attendance_date = '" . $date . "' and
                d.vehicle_idx = " . $vehicle_idx . " and
                d.office_idx = " . $this->secure->decrypt_string($this->session->userdata('office_idx')) . "
        ";
        $result = $this->db->query($sql)->row_array();
        return $result;
    }
}
?>