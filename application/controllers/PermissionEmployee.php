<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PermissionEmployee extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->library('secure');
        $this->load->model('ModelGenId');
        $this->load->model('ModelGlobal');
        $this->load->model('ModelEmployeePermission');
    }

    public function index()
    {
        $data['company']    = $this->ModelGlobal->getCompany();
        $data['contentMenu'] = "menu/permission_employee";
        $this->load->view('menu/index', $data);
    }

    // untuk tampil list data ijin kerja
    public function getPermissionEmployee()
    {
        // get id karyawan berdasarkan si pengakses website
        $idUser             = $this->secure->decrypt_string($this->session->userdata('employee_id'));
        // get data untuk status head berdasar variabel $idUser, berupa objek
        $getOrganizationEmp = $this->ModelGlobal->getOrganizationEmp($idUser)->row();

        $month_period       = filter_var(trim($this->input->post('month_period')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $year_period        = filter_var(trim($this->input->post('year_period')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

        if (!$month_period) {
            echo json_encode(['status' => false, 'message' => 'Field month_period is required']);
            die();
        }
        if (!$year_period) {
            echo json_encode(['status' => false, 'message' => 'Field year_period is required']);
            die();
        }

        if (!preg_match('/^[0-9]*$/', $month_period)) {
            echo json_encode(['status' => false, 'message' => 'Field month_period is invalid']);
            die();
        }
        if (!preg_match('/^[0-9]*$/', $year_period)) {
            echo json_encode(['status' => false, 'message' => 'Field year_period is invalid']);
            die();
        }

        $result = $this->ModelEmployeePermission->getPermissionEmployee($month_period, $year_period);
        if (!$result) {
            echo json_encode(['status' => false, 'message' => 'Data izin tidak ditemukan!']);
            die();
        }

        $data               = "";
        $formatterDateFull  = new IntlDateFormatter('id_ID', IntlDateFormatter::FULL, IntlDateFormatter::NONE);
        $formatter          = new IntlDateFormatter('id_ID', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
        foreach ($result as $row) {
            // $getEmployeePermission = $this->getDataEmployeePermission->updateData($decPermIdx);

            $start_date = new DateTime($row['start_date']);
            $end_date   = new DateTime($row['end_date']);

            $attachment = empty($row['url_attachment']) ? '<b>Tidak ada lampiran</b>' : '<b class="text-info" onclick="getAttachment(`' . $row['url_attachment'] . '`)"><i class="fas fa-cloud-download-alt"></i> Unduh Lampiran</b>';
            !empty($row['organization_name']) ? $dept = ucwords($row['organization_name']) : $dept = '-'; // nama dept / organisasi

            $btnAcc              = '';
            $remarksRejected     = '';

            // kalo status 99 ditolak
            if ($row['status'] == 99) {
                $status_color = 'text-danger';
            }
            // kalo status 1 tunggu acc atasan
            elseif ($row['status'] == 1) {
                $status_color = 'text-primary';
            }
            // kalo status 2 tunggu acc hrd
            elseif ($row['status'] == 2) {
                $status_color = 'text-info';
            } else {
                $status_color = 'text-success';
            }
            $dataTemplates = '
            <div class="listdata mb-3">
                <div class="card border-left-primary" style="border-width: 1px;">
                    <div class="card-body p-2" style="font-size: 0.85rem;">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="mb-0 text-muted" style="line-height: 1;">
                                <i class="far fa-calendar-alt mr-1"></i> ' . $formatterDateFull->format($start_date) . '
                            </h6>
                        </div>
                        <table class="table" style="border-collapse: separate; border-spacing: 0 0.5rem;">
                            <tbody>
                                <tr>
                                    <th class="text-right pr-2 align-top" style="width: 30%; padding-top: 0.25rem; padding-bottom: 0.25rem; line-height: 1.1;">Nama:</th>
                                    <td style="padding-top: 0.25rem; padding-bottom: 0.25rem; line-height: 1.1;">' . ucwords($row['employee_name']) . '</td>
                                </tr>
                                <tr>
                                    <th class="text-right pr-2 align-top" style="padding-top: 0.25rem; padding-bottom: 0.25rem; line-height: 1.1;">Departemen:</th>
                                    <td style="padding-top: 0.25rem; padding-bottom: 0.25rem; line-height: 1.1;">' . $dept . '</td>
                                </tr>
                                <tr>
                                    <th class="text-right pr-2 align-top" style="padding-top: 0.25rem; padding-bottom: 0.25rem; line-height: 1.1;">Tanggal:</th>
                                    <td style="padding-top: 0.25rem; padding-bottom: 0.25rem; line-height: 1.1;">' . $formatter->format($start_date) . ' s/d ' . $formatter->format($end_date) . '</td>
                                </tr>
                                <tr>
                                    <th class="text-right pr-2 align-top" style="padding-top: 0.25rem; padding-bottom: 0.25rem; line-height: 1.1;">Keterangan:</th>
                                    <td style="padding-top: 0.25rem; padding-bottom: 0.25rem; line-height: 1.1;">' . $row['remarks'] . '</td>
                                </tr>
                                
                                <tr>
                                    <th class="text-right pr-2 align-top" style="border-top: 2px solid #151B54; padding-top: 0.25rem; padding-bottom: 0.25rem; line-height: 1.1;">Status:</th>
                                    <td style="border-top: 2px solid #151B54; padding-top: 0.25rem; padding-bottom: 0.25rem; line-height: 1.1;">
                                        <span class="' . $status_color . ' font-weight-bold">
                                            <a href="#" onclick="getPermissionEmployeeById(`' . $this->secure->encrypt_string($row['permission_idx']) . '`)" class="text-decoration-none text-reset">
                                                ' . $row['status_name'] . '
                                            </a>
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>';

            // Apakah data organisasi dari user saat ini ($getEmpPermission) ada, dan apakah idx organisasinya sama dengan organization_idx dari data izin ($row)
            $inSameOrg = !empty($getOrganizationEmp) && $getOrganizationEmp->idx == $row['organization_idx'];

            // tampil data sesuai departemen (atasan/head)
            // employee id (tabel permission_employee) tidak sama dengan idUser (tabel master_employee)
            if ($inSameOrg && $row['employee_id'] != $idUser) {
                $data .= $dataTemplates;
            }
            // id 3 untuk HRD
            else if ($getOrganizationEmp->idx == 3) {
                $data .= $dataTemplates;
            }
        } // end foreach

        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]);
        exit;
    }

    // untuk submit ijin kerja karyawan
    public function submitPermission()
    {
        // // get id karyawan berdasarkan si pengakses website
        // $idUser             = $this->secure->decrypt_string($this->session->userdata('employee_id'));
        // // get data untuk status head berdasar variabel $idUser, berupa objek
        // $getOrganizationEmp = $this->ModelGlobal->getOrganizationEmp($idUser)->row();

        // $action     = $this->input->post("action", TRUE);
        $getPermIdx = $this->input->post("idx", TRUE);
        // $getHeadIdx = $this->session->userdata('employee_id');
        // $remarksRejected = $this->input->post("remarksRejected", TRUE);

        // $decPermIdx = $this->secure->decrypt_string($getPermIdx); // decrypt id ijin kerja
        // $decHeadIdx = $this->secure->decrypt_string($getHeadIdx); // decrypt id head

        // $data = ['action' => $action, 'permission id' => $decPermIdx, 'head_id' => $decHeadIdx];
        // if ($action === 'accept') {
        //     $data = [
        //         'status'            => 2, // lanjut ke proses acc hrd
        //         'head_acc'          => $decHeadIdx,
        //         'head_acc_created'  => date('Y-m-d H:i:s')
        //     ];
        //     $result = $this->ModelEmployeePermission->updateData($data, $decPermIdx);
        //     $status = false;
        //     $message = 'Action tidak dikenali';

        //     if ($result == 0) {
        //         echo json_encode(['status' => false, 'message' => 'Acc Izin Gagal !']);
        //         exit;
        //     }
        //     echo json_encode(['status' => true, 'message' => 'Izin Kerja di Acc']);
        //     exit;
        // } else if ($action === 'reject') {
        //     $data = [
        //         'status'            => 99, // jika status ijin kerja ditolak
        //         'head_acc'          => $decHeadIdx,
        //         'head_acc_created'  => date('Y-m-d H:i:s'),
        //         'remarks_rejected'  => $remarksRejected
        //     ];
        //     $result = $this->ModelEmployeePermission->updateData($data, $decPermIdx);
        //     if ($result == 0) {
        //         echo json_encode(['status' => false, 'message' => 'Izin Gagal !']);
        //         exit;
        //     }
        // } else {
        //     $status = false;
        //     $message = 'Action tidak dikenali';
        // }


        echo json_encode(['status' => true, 'message' => $getPermIdx]);
        exit;
    }

    // untuk tampil list data ijin kerja berdasarkan idx
    public function getPermissionEmployeeById()
    {
        $idx        = filter_var(trim($this->input->post('idx')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $idx        = $this->secure->decrypt_string($idx); // decrypt id ijin kerja

        if(!preg_match('/^[0-9]*$/', $idx)) { echo json_encode(['status' => false, 'message' => 'Field idx is invalid']); die(); }

        $result = $this->ModelEmployeePermission->getPermissionEmployeeById($idx);
        if (!$result) {
            echo json_encode(['status' => false, 'message' => 'Data izin tidak ditemukan!']);
            exit;
        }

        $data_array = [];
        $data_array['idx'] = $this->secure->encrypt_string($result['idx']);
        $data_array['permission_no'] = $result['permission_no'];
        $data_array['from_date'] = $result['from_date'];
        $data_array['to_date'] = $result['to_date'];
        $data_array['permission_type'] = $result['permission_type'];
        $data_array['remarks'] = $result['remarks'];
        $data = $data_array;
        // $decPermIdx = $this->secure->decrypt_string($getPermIdx); // decrypt id ijin kerja

        // $result = $this->ModelEmployeePermission->getDataEmployeePermission($decPermIdx);
        // if (!$result) {
        //     echo json_encode(['status' => false, 'message' => 'Data izin tidak ditemukan!']);
        //     die();
        // }

        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]);
        exit;
    }
}
