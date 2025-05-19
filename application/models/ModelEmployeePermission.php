<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ModelEmployeePermission extends CI_Model
{
    // untuk get data karyawan sesuai dengan id head (atasan)
    public function getEmployeeByOrganization($idUser)
    {
        $sql = "SELECT * 
                    FROM master_employee me
                    LEFT JOIN master_organization mog ON mog.idx = me.organization_idx";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getPermissionEmployee($month_period, $year_period)
    {
        $sql = "SELECT 
                        pe.idx as permission_idx,
                        pe.permission_no,
                        pe.start_date,
                        pe.end_date,
                        pe.remarks,
                        pe.url_attachment,
                        pe.status,
                        pe.employee_id,
                        spt.status_name AS permission_type_name,
                        spe.status_name AS status_name,
                        me.employee_name,
                        mog.idx as organization_idx,
                        mog.organization_name,
                        pe.remarks_rejected
                    FROM permission_employee pe
                    LEFT JOIN status_permission_type spt ON spt.status = pe.permission_type
                    LEFT JOIN status_permission_employee spe ON spe.status = pe.status
                    LEFT JOIN master_employee me ON me.employee_id = pe.employee_id
                    LEFT JOIN master_organization mog ON mog.idx = me.organization_idx
                    -- WHERE MONTH(start_date) = ? AND YEAR(start_date) = ?
                    ORDER BY start_date DESC";
        // if ($where) {
        //     $sql .= ' AND ' . $where; // Menambahkan kondisi tambahan ke query
        // }

        $query = $this->db->query($sql, [$month_period, $year_period]);
        return $query->result_array();
    }

    // untuk get detail data ijin kerja
    public function getPermissionEmployeeById($idx)
    {
        $sql = "SELECT 
                idx AS idx,
                permission_no AS permission_no,
                DATE_FORMAT(start_date, '%d-%m-%Y') AS from_date,
                 DATE_FORMAT(end_date, '%d-%m-%Y') AS to_date,
                permission_type AS permission_type,
                remarks AS remarks,
                url_attachment AS url_attachment,
                filename_attachment AS filename_attachment,
                status AS status
            FROM permission_employee 
            WHERE idx = ?";
        $query = $this->db->query($sql, [$idx]);
        return $query->row_array();
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

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return 0;
        } else {
            $this->db->trans_commit();
            return 1;
        }
    }

    public function updateData($data, $idx)
    {
        // Lakukan update
        $this->db->trans_begin();
        $this->db->where('idx', $idx);
        $this->db->update('permission_employee', $data);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return 0;
        } else {
            $this->db->trans_commit();
            return 1;
        }
    }
}
