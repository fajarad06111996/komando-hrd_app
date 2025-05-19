<?php
defined('BASEPATH') or exit('No direct script access allowed');

class GlobalData extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in(); // dari helpers/check_session_helper
        $this->load->library('secure');
		$this->load->model('ModelGlobal');
    }

    public function getLocationByPostalCode()
    {
        $postal_code = filter_var(trim($this->input->post('postal_code')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        
        if(!$postal_code) { echo json_encode(['status' => false, 'message' => 'Postal Code is required!']); die(); }
        
        if(!preg_match('/^[0-9]*$/', $postal_code)) { echo json_encode(['status' => false, 'message' => 'Postal Code is invalid']); die(); }

        $result = $this->ModelGlobal->getLocationByPostalCode($postal_code);
        if (!$result) { echo json_encode(['status' => false, 'message' => 'Data not found!']); die(); }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $result]); die();
    }

    public function getItemByIdx($idx)
    {
        $idx            = filter_var(trim($idx), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        
        if(!$idx) { echo json_encode(['status' => false, 'message' => 'Field idx is required']); die(); }
        
        $idx            = $this->secure->decrypt_string($idx);
        
        if(!preg_match('/^[0-9]*$/', $idx)) { echo json_encode(['status' => false, 'message' => 'Field idx is invalid']); die(); }
        
        $result = $this->ModelGlobal->getItemByIdx($idx);
        if(!$result) { echo json_encode(['status' => false, 'message' => 'Data barang tidak ditemukan']); die(); }

        $data_array = [];
        $data_array['idx']                  = $this->secure->encrypt_string($result['idx']);
        $data_array['item_code']            = $result['item_code'];
        $data_array['item_name']            = $result['item_name'];
        $data_array['unit_qty']             = $result['unit_qty'];
        $data = $data_array;
            
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
        
    }
    
    public function listItemCategory()
    {
        $data = [];
        $result = $this->ModelGlobal->listItemCategory();
        if (!$result) { $data = ""; }
        else {
            $i = 0;
            foreach ($result as $row) {
                $data[$i] = [
                    'idx'              => $this->secure->encrypt_string($row['idx']),
                    'category_name'    => $row['category_name'],
                ];
                $i = $i + 1;
            }
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }

    public function listStockType()
    {
        $data = [];
        $result = $this->ModelGlobal->listStockType();
        if (!$result) { $data = ""; }
        else {
            $i = 0;
            foreach ($result as $row) {
                $data[$i] = [
                    'status'           => $this->secure->encrypt_string($row['status']),
                    'status_name'      => $row['status_name'],
                ];
                $i = $i + 1;
            }
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }

    public function listVehicleType()
    {
        $data = [];
        $result = $this->ModelGlobal->listVehicleType();
        if (!$result) { $data = ""; }
        else {
            $i = 0;
            foreach ($result as $row) {
                $data[$i] = [
                    'status'           => $this->secure->encrypt_string($row['status']),
                    'status_name'      => $row['status_name'],
                ];
                $i = $i + 1;
            }
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }

    public function listLoadType()
    {
        $data = [];
        $result = $this->ModelGlobal->listLoadType();
        if (!$result) { $data = ""; }
        else {
            $i = 0;
            foreach ($result as $row) {
                $data[$i] = [
                    'status'           => $this->secure->encrypt_string($row['status']),
                    'status_name'      => $row['status_name'],
                ];
                $i = $i + 1;
            }
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }

    public function listAllItem()
    {
        $data = [];
        $result = $this->ModelGlobal->listAllItem();
        if (!$result) { $data = ""; }
        else {
            $i = 0;
            foreach ($result as $row) {
                $data[$i] = [
                    'idx'               => $this->secure->encrypt_string($row['idx']),
                    'item_name'         => $row['item_name'],
                    'description'       => $row['description'],
                    'unit_qty'          => $row['unit_qty'],
                ];
                $i = $i + 1;
            }
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }

    public function listRawMaterial()
    {
        $data = [];
        $result = $this->ModelGlobal->listRawMaterial();
        if (!$result) { $data = ""; }
        else {
            $i = 0;
            foreach ($result as $row) {
                $data[$i] = [
                    'idx'               => $this->secure->encrypt_string($row['idx']),
                    'item_name'         => $row['item_name'],
                    'description'       => $row['description'],
                    'unit_qty'          => $row['unit_qty'],
                ];
                $i = $i + 1;
            }
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }

    public function listCountry()
    {
        $data = [];
        $result = $this->ModelGlobal->listCountry();
        if (!$result) { $data = ""; }
        else {
            $i = 0;
            foreach ($result as $row) {
                $data[$i] = [
                    'idx'           => $this->secure->encrypt_string($row['idx']),
                    'alpha_2'       => $row['alpha_2'],
                    'country_code'  => $row['alpha_3'],
                    'country_name'  => $row['country_name'],
                ];
                $i = $i + 1;
            }
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }

    public function listShipper()
    {
        $data = [];
        $result = $this->ModelGlobal->listShipper();
        if (!$result) { $data = ""; }
        else {
            $i = 0;
            foreach ($result as $row) {
                $data[$i] = [
                    'idx'           => $this->secure->encrypt_string($row['idx']),
                    'shipper_name'  => $row['shipper_name']
                ];
                $i = $i + 1;
            }
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }

    public function listConsignee()
    {
        $data = [];
        $result = $this->ModelGlobal->listConsignee();
        if (!$result) { $data = ""; }
        else {
            $i = 0;
            foreach ($result as $row) {
                $data[$i] = [
                    'idx'               => $this->secure->encrypt_string($row['idx']),
                    'consignee_name'    => $row['consignee_name']
                ];
                $i = $i + 1;
            }
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }

    public function listNotifyParty()
    {
        $data = [];
        $result = $this->ModelGlobal->listNotifyParty();
        if (!$result) { $data = ""; }
        else {
            $i = 0;
            foreach ($result as $row) {
                $data[$i] = [
                    'idx'               => $this->secure->encrypt_string($row['idx']),
                    'notify_party_name' => $row['notify_party_name']
                ];
                $i = $i + 1;
            }
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }

    public function listCarrier()
    {
        $data = [];
        $result = $this->ModelGlobal->listCarrier();
        if (!$result) { $data = ""; }
        else {
            $i = 0;
            foreach ($result as $row) {
                $data[$i] = [
                    'idx'               => $this->secure->encrypt_string($row['idx']),
                    'carrier_name'      => $row['carrier_name']
                ];
                $i = $i + 1;
            }
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }

    public function listFlight()
    {
        $data = [];
        $result = $this->ModelGlobal->listFlight();
        if (!$result) { $data = ""; }
        else {
            $i = 0;
            foreach ($result as $row) {
                $data[$i] = [
                    'idx'               => $this->secure->encrypt_string($row['idx']),
                    'flight_name'       => $row['flight_name'],
                    'airlines'          => $row['airlines']
                ];
                $i = $i + 1;
            }
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }

    public function listAirport()
    {
        $data = [];
        $result = $this->ModelGlobal->listAirport();
        if (!$result) { $data = "";}
        else {
            $i = 0;
            foreach ($result as $row) {
                $data[$i] = [
                    'idx'               => $this->secure->encrypt_string($row['idx']),
                    'country_alpha_2'   => $row['country_alpha_2'],
                    'city'              => $row['city'],
                    'airport_name'      => $row['airport_name']
                ];
                $i = $i + 1;
            }
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }

    public function listVessel()
    {
        $data = [];
        $result = $this->ModelGlobal->listVessel();
        if (!$result) { $data = ""; }
        else {
            $i = 0;
            foreach ($result as $row) {
                $data[$i] = [
                    'idx'               => $this->secure->encrypt_string($row['idx']),
                    'vessel_name'       => $row['vessel_name'],
                    'lloyds_code'       => $row['lloyds_code']
                ];
                $i = $i + 1;
            }
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }

    public function listSeaport()
    {
        $data = [];
        $result = $this->ModelGlobal->listSeaport();
        if (!$result) { $data = "";}
        else {
            $i = 0;
            foreach ($result as $row) {
                $data[$i] = [
                    'idx'               => $this->secure->encrypt_string($row['idx']),
                    'country_alpha_2'   => $row['country_alpha_2'],
                    'city'              => $row['city'],
                    'seaport_code'      => $row['seaport_code'],
                    'seaport_name'      => $row['seaport_name']
                ];
                $i = $i + 1;
            }
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }

    public function listDriverType()
    {
        $result = $this->ModelGlobal->listDriverType();
        if (!$result) { echo json_encode(['status' => false, 'message' => 'Data not found!']); die(); }
        
        $data = [];
        $i = 0;
        foreach ($result as $row) {
            $data[$i] = [
                'idx'           => $this->secure->encrypt_string($row['status']),
                'status_name'   => $row['status_name']
            ];
            $i = $i + 1;
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }
    
    public function listOffice()
    {
        $result = $this->ModelGlobal->listOffice();
        if (!$result) { echo json_encode(['status' => false, 'message' => 'Data not found!']); die(); }
        
        $data = [];
        $i = 0;
        foreach ($result as $row) {
            $data[$i] = [
                'idx'           => $this->secure->encrypt_string($row['idx']),
                'office_name'   => $row['office_name']
            ];
            $i = $i + 1;
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }

    public function listRegion()
    {
        $result = $this->ModelGlobal->listRegion();
        if (!$result) { echo json_encode(['status' => false, 'message' => 'Data not found!']); die(); }
        
        $data = [];
        $i = 0;
        foreach ($result as $row) {
            $data[$i] = [
                'idx'           => $this->secure->encrypt_string($row['idx']),
                'region_name'   => $row['region_name']
            ];
            $i = $i + 1;
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }

    public function listDepot()
    {
        $result = $this->ModelGlobal->listDepot();
        if (!$result) { echo json_encode(['status' => false, 'message' => 'Data not found!']); die(); }
        
        $data = [];
        $i = 0;
        foreach ($result as $row) {
            $data[$i] = [
                'idx'           => $this->secure->encrypt_string($row['idx']),
                'depot_name'    => $row['depot_name']
            ];
            $i = $i + 1;
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }
    
    public function listOfficeUser()
    {
        $user_idx = filter_var(trim($this->input->post('user_idx')), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
        $user_idx = $this->secure->decrypt_string($user_idx);
        $result = $this->ModelGlobal->listOfficeUser($user_idx);
        if (!$result) { echo json_encode(['status' => false, 'message' => 'Data not found!']); die(); }
        
        $data = [];
        $i = 0;
        foreach ($result as $row) {
            $data[$i] = [
                'idx'           => $this->secure->encrypt_string($row['idx']),
                'office_name'   => $row['office_name']
            ];
            $i = $i + 1;
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }
    
    public function listLevelAccess()
    {
        $result = $this->ModelGlobal->listLevelAccess();
        if (!$result) { echo json_encode(['status' => false, 'message' => 'Data not found!']); die(); }
        
        $data = [];
        $i = 0;
        foreach ($result as $row) {
            $data[$i] = [
                'idx'           => $this->secure->encrypt_string($row['idx']),
                'level_name'    => $row['level_name']
            ];
            $i = $i + 1;
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }

    public function listPRType()
    {
        $result = $this->ModelGlobal->listPRType();
        if (!$result) { echo json_encode(['status' => false, 'message' => 'Data not found!']); die(); }
        
        $data = [];
        $i = 0;
        foreach ($result as $row) {
            $data[$i] = [
                'status'        => $this->secure->encrypt_string($row['status']),
                'status_name'   => $row['status_name']
            ];
            $i = $i + 1;
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }

    public function listPRStatus()
    {
        $result = $this->ModelGlobal->listPRStatus();
        if (!$result) { echo json_encode(['status' => false, 'message' => 'Data not found!']); die(); }
        
        $data = [];
        $i = 0;
        foreach ($result as $row) {
            $data[$i] = [
                'status'        => $this->secure->encrypt_string($row['status']),
                'status_name'   => $row['status_name']
            ];
            $i = $i + 1;
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }

    public function listPOStatus()
    {
        $result = $this->ModelGlobal->listPOStatus();
        if (!$result) { echo json_encode(['status' => false, 'message' => 'Data not found!']); die(); }
        
        $data = [];
        $i = 0;
        foreach ($result as $row) {
            $data[$i] = [
                'status'        => $this->secure->encrypt_string($row['status']),
                'status_name'   => $row['status_name']
            ];
            $i = $i + 1;
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }

    public function listExtendType()
    {
        $result = $this->ModelGlobal->listExtendType();
        if (!$result) { echo json_encode(['status' => false, 'message' => 'Data not found!']); die(); }
        
        $data = [];
        $i = 0;
        foreach ($result as $row) {
            $data[$i] = [
                'status'        => $this->secure->encrypt_string($row['status']),
                'status_name'   => $row['status_name']
            ];
            $i = $i + 1;
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }
    
    public function listClient()
    {
        $data = [];
        $result = $this->ModelGlobal->listClient();
        if (!$result) { $data = ""; }
        else {
            $i = 0;
            foreach ($result as $row) {
                $data[$i] = [
                    'client_id'     => $this->secure->encrypt_string($row['client_id']),
                    'client_code'   => $row['client_code'],
                    'client_name'   => $row['client_name']
                ];
                $i = $i + 1;
            }
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }

    public function listClientVerifyAccount()
    {
        $data = [];
        $result = $this->ModelGlobal->listClientVerifyAccount();
        if (!$result) { $data = ""; }
        else {
                                        
            $i = 0;
            foreach ($result as $row) {
                $data[$i] = [
                    'client_id'     => $this->secure->encrypt_string($row['client_id']),
                    'client_code'   => $row['client_code'],
                    'client_name'   => $row['client_name'],
                    'address'       => $row['address'],
                    'city'          => $row['city'],
                    'postal_code'   => $row['postal_code'],
                    'pic_name'      => $row['pic_name'],
                    'mobile_phone'  => $row['mobile_phone'],
                    'tax_id'        => $row['tax_id']
                ];
                $i = $i + 1;
            }
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }
    
    public function listVendor()
    {
        $result = $this->ModelGlobal->listVendor();
        if (!$result) { $data = ""; }
        else {
                                        
            $i = 0;
            foreach ($result as $row) {
                $data[$i] = [
                    'vendor_idx'    => $this->secure->encrypt_string($row['idx']),
                    'vendor_code'   => $row['vendor_code'],
                    'vendor_name'   => $row['vendor_name'],
                    'address'       => $row['address'],
                    'city'          => $row['city'],
                    'postal_code'   => $row['postal_code'],
                    'pic_name'      => $row['pic_name'],
                    'mobile_phone'  => $row['mobile_phone'],
                    'tax_id'        => $row['tax_id']
                ];
                $i = $i + 1;
            }
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }

    public function listVendorVerifyAccount()
    {
        $data = [];
        $result = $this->ModelGlobal->listVendorVerifyAccount();
        if (!$result) { $data = ""; }
        else {
                                        
            $i = 0;
            foreach ($result as $row) {
                $data[$i] = [
                    'vendor_idx'    => $this->secure->encrypt_string($row['idx']),
                    'vendor_code'   => $row['vendor_code'],
                    'vendor_name'   => $row['vendor_name'],
                    'address'       => $row['address'],
                    'city'          => $row['city'],
                    'postal_code'   => $row['postal_code'],
                    'pic_name'      => $row['pic_name'],
                    'mobile_phone'  => $row['mobile_phone'],
                    'tax_id'        => $row['tax_id']
                ];
                $i = $i + 1;
            }
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }

    public function listChargeCodeFull()
    {
        $data = [];
        $result = $this->ModelGlobal->listChargeCodeFull();
        if (!$result) { $data = ""; }
        else {
                                        
            $i = 0;
            foreach ($result as $row) {
                $data[$i] = [
                    'idx'                       => $this->secure->encrypt_string($row['idx']),
                    'charge_code'               => $row['charge_code'],
                    'charge_name'               => $row['charge_name'],
                    'account_payable_idx'       => $this->secure->encrypt_string($row['account_payable_idx']),
                    'account_receivable_idx'    => $this->secure->encrypt_string($row['account_receivable_idx'])
                ];
                $i = $i + 1;
            }
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }
    
    public function listVehicle()
    {
        $result = $this->ModelGlobal->listVehicle();
        if (!$result) { echo json_encode(['status' => false, 'message' => 'Data not found!']); die(); }
        
        $data = [];
        $i = 0;
        foreach ($result as $row) {
            $data[$i] = [
                'vehicle_idx'   => $this->secure->encrypt_string($row['idx']),
                'vehicle_no'    => $row['vehicle_no'],
                'vehicle_code'  => $row['vehicle_code'],
                'vehicle_type'  => $row['vehicle_type']
            ];
            $i = $i + 1;
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }
    
    public function listDriver()
    {
        $result = $this->ModelGlobal->listDriver();
        if (!$result) { echo json_encode(['status' => false, 'message' => 'Data not found!']); die(); }
        
        $data = [];
        $i = 0;
        foreach ($result as $row) {
            $data[$i] = [
                'driver_id'     => $this->secure->encrypt_string($row['driver_id']),
                'driver_name'   => $row['driver_name']
            ];
            $i = $i + 1;
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }
    
    public function listServices()
    {
        $result = $this->ModelGlobal->listServices();
        if (!$result) { echo json_encode(['status' => false, 'message' => 'Data not found!']); die(); }
        
        $data = [];
        $i = 0;
        foreach ($result as $row) {
            $data[$i] = [
                'idx'           => $this->secure->encrypt_string($row['idx']),
                'service_code'  => $row['service_code'],
                'service_name'  => $row['service_name']
            ];
            $i = $i + 1;
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }
    
    public function listModeTransport()
    {
        $data = [];
        $result = $this->ModelGlobal->listModeTransport();
        if (!$result) { $data = ""; }
        else {
            $i = 0;
            foreach ($result as $row) {
                $data[$i] = [
                    'status'        => $this->secure->encrypt_string($row['status']),
                    'status_name'   => $row['status_name']
                ];
                $i = $i + 1;
            }
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }

    public function listLoadingType()
    {
        $data = [];
        $result = $this->ModelGlobal->listLoadingType();
        if (!$result) { $data = ""; }
        else {
            $i = 0;
            foreach ($result as $row) {
                $data[$i] = [
                    'status'        => $this->secure->encrypt_string($row['status']),
                    'status_name'   => $row['status_name']
                ];
                $i = $i + 1;
            }
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }

    public function listMovementType()
    {
        $data = [];
        $result = $this->ModelGlobal->listMovementType();
        if (!$result) { $data = ""; }
        else {
            $i = 0;
            foreach ($result as $row) {
                $data[$i] = [
                    'status'        => $this->secure->encrypt_string($row['status']),
                    'status_name'   => $row['status_name']
                ];
                $i = $i + 1;
            }
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }

    public function listStatusOrder()
    {
        $result = $this->ModelGlobal->listStatusOrder();
        if (!$result) { echo json_encode(['status' => false, 'message' => 'Data not found!']); die(); }
        
        $data = [];
        $i = 0;
        foreach ($result as $row) {
            $data[$i] = [
                'status'        => $row['status'],
                'status_name'   => $row['status_name']
            ];
            $i = $i + 1;
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }
    
    public function listStatusVehicleReady()
    {
        $result = $this->ModelGlobal->listStatusVehicleReady();
        if (!$result) { echo json_encode(['status' => false, 'message' => 'Data not found!']); die(); }
        
        $data = [];
        $i = 0;
        foreach ($result as $row) {
            $data[$i] = [
                'status'        => $row['status'],
                'status_name'   => $row['status_name']
            ];
            $i = $i + 1;
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }
    
    public function listStatusDriverReady()
    {
        $result = $this->ModelGlobal->listStatusDriverReady();
        if (!$result) { echo json_encode(['status' => false, 'message' => 'Data not found!']); die(); }
        
        $data = [];
        $i = 0;
        foreach ($result as $row) {
            $data[$i] = [
                'status'        => $row['status'],
                'status_name'   => $row['status_name']
            ];
            $i = $i + 1;
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }
    
    public function listCityVendor()
    {
        $result = $this->ModelGlobal->listCityVendor();
        if (!$result) { echo json_encode(['status' => false, 'message' => 'Data not found!']); die(); }
        
        $data = [];
        $i = 0;
        foreach ($result as $row) {
            $data[$i] = [
                'city'        => $row['city']
            ];
            $i = $i + 1;
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }

    public function listAccountAll()
    {
        $data = [];
        $result = $this->ModelGlobal->listAccountAll();
        if (!$result) { $data = ""; }
        else {
            $i = 0;
            foreach ($result as $row) {
                $data[$i] = [
                    'idx'               => $this->secure->encrypt_string($row['idx']),
                    'account_number'    => $row['account_number'],
                    'account_name'      => $row['account_name']
                ];
                $i = $i + 1;
            }
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }

    public function listAccountChild()
    {
        $data = [];
        $result = $this->ModelGlobal->listAccountChild();
        if (!$result) { $data = ""; }
        else {
            $i = 0;
            foreach ($result as $row) {
                $data[$i] = [
                    'idx'               => $this->secure->encrypt_string($row['idx']),
                    'account_number'    => $row['account_number'],
                    'account_name'      => $row['account_name']
                ];
                $i = $i + 1;
            }
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }

    public function listAccountCashBank()
    {
        $data = [];
        $result = $this->ModelGlobal->listAccountCashBank();
        if (!$result) { $data = ""; }
        else {
            $i = 0;
            foreach ($result as $row) {
                $data[$i] = [
                    'idx'               => $this->secure->encrypt_string($row['idx']),
                    'account_number'    => $row['account_number'],
                    'account_name'      => $row['account_name']
                ];
                $i = $i + 1;
            }
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }

    public function listAccountPayable()
    {
        $data = [];
        $result = $this->ModelGlobal->listAccountPayable();
        if (!$result) { $data = ""; }
        else {
            $i = 0;
            foreach ($result as $row) {
                $data[$i] = [
                    'idx'               => $this->secure->encrypt_string($row['idx']),
                    'account_number'    => $row['account_number'],
                    'account_name'      => $row['account_name']
                ];
                $i = $i + 1;
            }
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }

    public function listAccountReceivable()
    {
        $data = [];
        $result = $this->ModelGlobal->listAccountReceivable();
        if (!$result) { $data = ""; }
        else {
            $i = 0;
            foreach ($result as $row) {
                $data[$i] = [
                    'idx'               => $this->secure->encrypt_string($row['idx']),
                    'account_number'    => $row['account_number'],
                    'account_name'      => $row['account_name']
                ];
                $i = $i + 1;
            }
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }

    public function listPaymentMode()
    {
        $data = [];
        $result = $this->ModelGlobal->listPaymentMode();
        if (!$result) { $data = ""; }
        else {
            $i = 0;
            foreach ($result as $row) {
                $data[$i] = [
                    'status'            => $this->secure->encrypt_string($row['status']),
                    'status_name'       => $row['status_name']
                ];
                $i = $i + 1;
            }
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }

    public function listAccountType()
    {
        $data = [];
        $result = $this->ModelGlobal->listAccountType();
        if (!$result) { $data = ""; }
        else {
            $i = 0;
            foreach ($result as $row) {
                $data[$i] = [
                    'idx'               => $this->secure->encrypt_string($row['idx']),
                    'account_name'      => $row['account_name']
                ];
                $i = $i + 1;
            }
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }

    public function listAccountSegment1($account_type)
    {
        $data = [];
        $result = $this->ModelGlobal->listAccountSegment1($this->secure->decrypt_string($account_type));
        if (!$result) { $data = ""; }
        else {
            $i = 0;
            foreach ($result as $row) {
                $data[$i] = [
                    'idx'               => $this->secure->encrypt_string($row['idx']),
                    'account_number'    => $row['account_number'],
                    'account_name'      => $row['account_name']
                ];
                $i = $i + 1;
            }
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }

    public function listAccountSegment2($account_segment1)
    {
        $data = [];
        $result = $this->ModelGlobal->listAccountSegment2($this->secure->decrypt_string($account_segment1));
        if (!$result) { $data = ""; }
        else {
            $i = 0;
            foreach ($result as $row) {
                $data[$i] = [
                    'idx'               => $this->secure->encrypt_string($row['idx']),
                    'account_number'    => $row['account_number'],
                    'account_name'      => $row['account_name']
                ];
                $i = $i + 1;
            }
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }

    public function listAccountSegment3($account_segment2)
    {
        $data = [];
        $result = $this->ModelGlobal->listAccountSegment3($this->secure->decrypt_string($account_segment2));
        if (!$result) { $data = ""; }
        else {
            $i = 0;
            foreach ($result as $row) {
                $data[$i] = [
                    'idx'               => $this->secure->encrypt_string($row['idx']),
                    'account_number'    => $row['account_number'],
                    'account_name'      => $row['account_name']
                ];
                $i = $i + 1;
            }
        }
        
        echo json_encode(['status' => true, 'message' => 'Data is available', 'data' => $data]); die();
    }
}
?>
