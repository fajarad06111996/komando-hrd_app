<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ModelGenId extends CI_Model
{
    public function genIdYear($config_id, $id_login)
    {
        $cekConfigId = $this->db->query("select * from configuration where config_id = '" . strtoupper($config_id) . "'")->row_array();
        if($cekConfigId) {
            $cekConfigYear = $this->db->query("select * from configuration where config_id = '" . strtoupper($config_id) . "' AND YEAR(config_date) = '" . date('Y') . "'")->row_array();
            if($cekConfigYear) {
                $result = $cekConfigYear['config_value'] + 1;
                $this->db->query("update configuration set
                                                config_value = config_value + 1,
                                                config_date = '" . date("Y-m-d H:i:s") . "',
                                                modified_by = " . $id_login . ",
                                                modified_on = '" . date("Y-m-d H:i:s") . "'
                                    where config_id = '" . strtoupper($config_id) . "'");
            }
			else { 
			    $this->db->query("update configuration set
                                                config_value = 1,
                                                config_date = '" . date("Y-m-d H:i:s") . "',
                                                modified_by = " . $id_login . ",
                                                modified_on = '" . date("Y-m-d H:i:s") . "'
                                    where config_id = '" . strtoupper($config_id) . "'");
                $result = 1;
			}
        } 
        else {
            $this->db->query("insert into configuration (
                                                config_id,
                                                config_value,
                                                config_date,
                                                modified_by,
                                                modified_on)
                                        values (
                                            '".strtoupper($config_id)."',
                                            1,
                                            '".date("Y-m-d H:i:s")."',
                                            $id_login,
                                            '".date("Y-m-d H:i:s")."'
                                        )");
            $result = 1;
        }

        return $result;
    }
    
    public function genIdMonth($config_id, $id_login)
    {
        $cekConfigId = $this->db->query("select * from configuration where config_id = '" . strtoupper($config_id) . "'")->row_array();
        if($cekConfigId) {
            $cekConfigMonth = $this->db->query("select * from configuration where config_id = '" . strtoupper($config_id) . "' AND (MONTH(config_date) = '" . date('m') . "' AND YEAR(config_date) = '" . date('Y') . "')")->row_array();
            if($cekConfigMonth) {
                $result = $cekConfigMonth['config_value'] + 1;
                $this->db->query("update configuration set
                                                config_value = config_value + 1,
                                                config_date = '" . date("Y-m-d H:i:s") . "',
                                                modified_by = " . $id_login . ",
                                                modified_on = '" . date("Y-m-d H:i:s") . "'
                                    where config_id = '" . strtoupper($config_id) . "'");
            }
			else { 
			    $this->db->query("update configuration set
                                                config_value = 1,
                                                config_date = '" . date("Y-m-d H:i:s") . "',
                                                modified_by = " . $id_login . ",
                                                modified_on = '" . date("Y-m-d H:i:s") . "'
                                    where config_id = '" . strtoupper($config_id) . "'");
                $result = 1;
			}
        } 
        else {
            $this->db->query("insert into configuration (
                                                config_id,
                                                config_value,
                                                config_date,
                                                modified_by,
                                                modified_on)
                                        values (
                                            '".strtoupper($config_id)."',
                                            1,
                                            '".date("Y-m-d H:i:s")."',
                                            $id_login,
                                            '".date("Y-m-d H:i:s")."'
                                        )");
            $result = 1;
        }

        return $result;
    }
    
    public function genIdDate($config_id, $id_login)
    {
        $cekConfigId = $this->db->query("select * from configuration where config_id = '" . strtoupper($config_id) . "'")->row_array();
        if($cekConfigId) {
            $cekConfigDate = $this->db->query("select * from configuration where config_id = '" . strtoupper($config_id) . "' AND DATE(config_date) = '" . date('Y-m-d') . "'")->row_array();
            if($cekConfigDate) {
                $result = $cekConfigDate['config_value'] + 1;
                $this->db->query("update configuration set
                                                config_value = config_value + 1,
                                                config_date = '" . date("Y-m-d H:i:s") . "',
                                                modified_by = " . $id_login . ",
                                                modified_on = '" . date("Y-m-d H:i:s") . "'
                                    where config_id = '" . strtoupper($config_id) . "'");
            }
			else { 
			    $this->db->query("update configuration set
                                                config_value = 1,
                                                config_date = '" . date("Y-m-d H:i:s") . "',
                                                modified_by = " . $id_login . ",
                                                modified_on = '" . date("Y-m-d H:i:s") . "'
                                    where config_id = '" . strtoupper($config_id) . "'");
                $result = 1;
			}
        } 
        else {
            $this->db->query("insert into configuration (
                                                config_id,
                                                config_value,
                                                config_date,
                                                modified_by,
                                                modified_on)
                                        values (
                                            '".strtoupper($config_id)."',
                                            1,
                                            '".date("Y-m-d H:i:s")."',
                                            $id_login,
                                            '".date("Y-m-d H:i:s")."'
                                        )");
            $result = 1;
        }

        return $result;
    }

	public function genIdUnlimited($config_id, $id_login)
    {
        $cekConfigId = $this->db->query("select * from configuration where config_id = '" . strtoupper($config_id) . "'")->row_array();
        if($cekConfigId) {
            $result = $cekConfigId['config_value'] + 1;
            $this->db->query("update configuration set
                                                config_value = config_value + 1,
                                                config_date = '" . date("Y-m-d H:i:s") . "',
                                                modified_by = " . $id_login . ",
                                                modified_on = '" . date("Y-m-d H:i:s") . "'
                                where config_id = '" . strtoupper($config_id) . "'");
        }
        else {
            $this->db->query("insert into configuration (
                                                 config_id,
                                                 config_value,
                                                 config_date,
                                                 modified_by,
                                                 modified_on)
                                        values (
                                            '".strtoupper($config_id)."',
                                            1,
                                            '".date("Y-m-d H:i:s")."',
                                            $id_login,
                                            '".date("Y-m-d H:i:s")."'
                                        )");
            $result = 1;
        }

        return $result;
    }
}
?>
