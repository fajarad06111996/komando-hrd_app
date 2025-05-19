<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ModelGlobal extends CI_Model
{
    public function selectDateFormat($date)
    {
        //set to MySQL
        return "DATE_FORMAT($date,'%d-%b-%Y')";
    }

    public function selectDateTimeFormat($date)
    {
        //set to MySQL
        return "DATE_FORMAT($date,'%d-%b-%Y %h:%m')";
    }

    public function whereDateFormat($date)
    {
        //set to MySQL
        return "DATE_FORMAT($date,'%d-%b-%Y')";
    }

    public function executeDateFormat($date)
    {
        //set to MySQL
        return "STR_TO_DATE('" . $date . "', '%d-%b-%Y')";
    }

    function getMonthRomawi($bln)
    {

        switch ($bln) {
            case "01":
                return "I";
                break;
            case "02":
                return "II";
                break;
            case "03":
                return "III";
                break;
            case "04":
                return "IV";
                break;
            case "05":
                return "V";
                break;
            case "06":
                return "VI";
                break;
            case "07":
                return "VII";
                break;
            case "08":
                return "VIII";
                break;
            case "09":
                return "IX";
                break;
            case "10":
                return "X";
                break;
            case "11":
                return "XI";
                break;
            case "12":
                return "XII";
                break;
        }
    }

    function getMonthID($bln)
    {

        switch ($bln) {
            case "1":
                return "JANUARI";
                break;
            case "2":
                return "FEBRUARI";
                break;
            case "3":
                return "MARET";
                break;
            case "4":
                return "APRIL";
                break;
            case "5":
                return "MEI";
                break;
            case "6":
                return "JUNI";
                break;
            case "7":
                return "JULI";
                break;
            case "8":
                return "AGUSTUS";
                break;
            case "9":
                return "SEPTEMBER";
                break;
            case "10":
                return "OKTOBER";
                break;
            case "11":
                return "NOVEMBER";
                break;
            case "12":
                return "DESEMBER";
                break;
        }
    }

    public function getCompany()
    {
        $result = $this->db->query("select *
                                    from setting_company_finance 
                                    where status = 1
                                    limit 1")->row_array();
        return $result;
    }

    // get data organisasi dari parameter id karyawan
    public function getOrganizationEmp($empId)
    {
        $this->db->where('employee_id', $empId);
        return $this->db->get('master_organization');
    }

    public function getBack()
    {
        return "<a href='javascript:window.history.go(-1);' class='text-lg text-white'><i class='fas fa-angle-left'></i></a>";
    }

    public function getLocationByPostalCode($postal_code)
    {
        $result = $this->db->query("select
                                        district_name,
                                        city_name,
                                        province_name,
                                        country_name
                                    from master_location 
                                    where postal_code = '" . $postal_code . "' and status = 1
                                    limit 1")->row_array();
        return $result;
    }

    public function getVehicle($vehicle_idx)
    {
        $result = $this->db->query("select *
                                    from master_vehicle 
                                    where idx = " . $vehicle_idx . " and status = 1
                                    limit 1")->row_array();
        return $result;
    }

    public function getDriver($driver_id)
    {
        $result = $this->db->query("select *
                                    from master_driver 
                                    where driver_id = " . $driver_id . " and status = 1
                                    limit 1")->row_array();
        return $result;
    }

    public function getAccountParent($setting_account_code)
    {
        $result = $this->db->query("select a.idx as idx, 
                a.account_number as account_number, 
                a.account_name as account_name, 
                a.account_type as account_type,
                a.account_segment as account_segment,
                IFNULL((select CASE
                                WHEN b.account_segment = 1 THEN (b.part_one_account_number + 1)
                                WHEN b.account_segment = 2 THEN (b.part_two_account_number + 1)
                                WHEN b.account_segment = 3 THEN (b.part_three_account_number + 1)
                                WHEN b.account_segment = 4 THEN (b.part_four_account_number + 1)
                            END
                        from master_account b
                        where b.parent_idx = a.idx
                        order by b.idx desc
                        limit 1), 1) as new_account_number
            from setting_account s, master_account a
            where s.account_idx = a.idx and s.setting_code = '" . $setting_account_code . "'")->row_array();
        return $result;
    }

    public function getAccountIdx($idx)
    {
        $result = $this->db->query("select a.idx as idx, 
                a.account_number as account_number,
                a.account_name as account_name, 
                a.account_type as account_type,
                a.account_segment as account_segment
            from master_account a
            where a.status = 1 and a.idx = '" . $idx . "'")->row_array();
        return $result;
    }

    public function getAccountIdxSetting($setting_account_code)
    {
        $result = $this->db->query("select a.idx as idx, 
                a.account_number as account_number, 
                a.account_type as account_type,
                a.account_segment as account_segment
            from setting_account s, master_account a
            where s.account_idx = a.idx and s.setting_code = '" . $setting_account_code . "'")->row_array();
        return $result;
    }

    public function getClientVerifyAccount($client_id)
    {
        $result = $this->db->query("select
                                        idx,
                                        client_id,
                                        client_code,
                                        client_name,
                                        address,
                                        city,
                                        postal_code,
                                        pic_name,
                                        mobile_phone,
                                        tax_id,
                                        (select idx from master_account where account_number = master_client.account_number and status = 1) as account_idx
                                    from master_client
                                    where status = 1 and account_number IS NOT NULL and client_id = " . $client_id . "")->row_array();
        return $result;
    }

    public function getVendor($idx)
    {
        $result = $this->db->query("select
                                        idx,
                                        vendor_id,
                                        vendor_code,
                                        vendor_name,
                                        address,
                                        city,
                                        postal_code,
                                        pic_name,
                                        mobile_phone,
                                        tax_id,
                                        (select idx from master_account where account_number = master_vendor.account_number and status = 1) as account_idx
                                    from master_vendor
                                    where status = 1 and idx = " . $idx . "")->row_array();
        return $result;
    }

    public function getVendorVerifyAccount($idx)
    {
        $result = $this->db->query("select
                                        idx,
                                        vendor_id,
                                        vendor_code,
                                        vendor_name,
                                        address,
                                        city,
                                        postal_code,
                                        pic_name,
                                        mobile_phone,
                                        tax_id,
                                        (select idx from master_account where account_number = master_vendor.account_number and status = 1) as account_idx
                                    from master_vendor
                                    where status = 1 and account_number IS NOT NULL and idx = " . $idx . "")->row_array();
        return $result;
    }

    public function getItemByIdx($idx)
    {
        $result = $this->db->query("select idx as idx, 
                                        item_code as item_code, 
                                        item_name as item_name, 
                                        description as description,
                                        unit_qty as unit_qty,
                                        (select status_name from status_item_category where status = master_item.item_category) as item_category,
                                        IF(status = 1, 'AKTIF', 'NON AKTIF') as status_name,
                                        status as status
                                    from master_item
                                    where idx = " . $idx . "")->row_array();
        return $result;
    }

    public function listChargeCodeFull()
    {
        $result = $this->db->query("select
                                        idx,
                                        charge_code,
                                        charge_name,
                                        account_payable_idx,
                                        account_receivable_idx
                                    from master_charge_code
                                    where status = 1")->result_array();
        return $result;
    }

    public function listItemCategory()
    {
        $result = $this->db->query("select *
                                    from master_item_category
                                    where status > 0
                                    order by category_name asc")->result_array();
        return $result;
    }

    public function listStockType()
    {
        $result = $this->db->query("select *
                                    from status_stock_type
                                    where status > 0
                                    order by status asc")->result_array();
        return $result;
    }

    public function listVehicleType()
    {
        $result = $this->db->query("select *
                                    from status_vehicle_type
                                    where status > 0
                                    order by status_name asc")->result_array();
        return $result;
    }

    public function listLoadType()
    {
        $result = $this->db->query("select *
                                    from status_load_type
                                    where status > 0
                                    order by status_name asc")->result_array();
        return $result;
    }

    public function listAllItem()
    {
        $result = $this->db->query("select *
                                    from master_item
                                    where status > 0 and office_idx = " . $this->secure->decrypt_string($this->session->userdata('office_idx')) . "
                                    order by item_name asc")->result_array();
        return $result;
    }

    public function listRawMaterial()
    {
        $result = $this->db->query("select *
                                    from master_item
                                    where status > 0 and item_category = 1 and office_idx = " . $this->secure->decrypt_string($this->session->userdata('office_idx')) . "
                                    order by item_name asc")->result_array();
        return $result;
    }

    public function listCountry()
    {
        $result = $this->db->query("select *
                                    from master_country
                                    where status = 1
                                    order by country_name asc")->result_array();
        return $result;
    }

    public function listShipper()
    {
        $result = $this->db->query("select *
                                    from master_shipper
                                    where status = 1
                                    order by shipper_name asc")->result_array();
        return $result;
    }

    public function listCarrier()
    {
        $result = $this->db->query("select *
                                    from master_carrier
                                    where status = 1
                                    order by carrier_name asc")->result_array();
        return $result;
    }

    public function listConsignee()
    {
        $result = $this->db->query("select *
                                    from master_consignee
                                    where status = 1
                                    order by consignee_name asc")->result_array();
        return $result;
    }

    public function listNotifyParty()
    {
        $result = $this->db->query("select *
                                    from master_notify_party
                                    where status = 1
                                    order by notify_party_name asc")->result_array();
        return $result;
    }

    public function listFlight()
    {
        $result = $this->db->query("select *
                                    from master_flight
                                    where status = 1
                                    order by flight_name asc")->result_array();
        return $result;
    }

    public function listAirport()
    {
        $result = $this->db->query("select *
                                    from master_airport
                                    where status = 1
                                    order by airport_name asc")->result_array();
        return $result;
    }

    public function listVessel()
    {
        $result = $this->db->query("select *
                                    from master_vessel
                                    where status = 1
                                    order by vessel_name asc")->result_array();
        return $result;
    }

    public function listSeaport()
    {
        $result = $this->db->query("select *
                                    from master_seaport
                                    where status = 1
                                    order by seaport_name asc")->result_array();
        return $result;
    }

    public function listDriverType()
    {
        $result = $this->db->query("select
                                        status,
                                        status_name
                                    from status_driver_type")->result_array();
        return $result;
    }

    public function listOffice()
    {
        $result = $this->db->query("select
                                        idx,
                                        office_name
                                    from master_office
                                    where status = 1")->result_array();
        return $result;
    }

    public function listPRStatus()
    {
        $result = $this->db->query("select
                                        status,
                                        status_name
                                    from status_purchase_request")->result_array();
        return $result;
    }

    public function listPOStatus()
    {
        $result = $this->db->query("select
                                        status,
                                        status_name
                                    from status_purchase_order")->result_array();
        return $result;
    }

    public function listPRType()
    {
        $result = $this->db->query("select
                                        status,
                                        status_name
                                    from status_purchase_request_type
                                    where status > 0")->result_array();
        return $result;
    }

    public function listExtendType()
    {
        $result = $this->db->query("select
                                        status,
                                        status_name
                                    from status_extend_type
                                    where status > 0")->result_array();
        return $result;
    }

    public function listRegion()
    {
        $result = $this->db->query("select
                                        idx,
                                        region_name
                                    from master_region
                                    where status = 1")->result_array();
        return $result;
    }

    public function listDepot()
    {
        $result = $this->db->query("select
                                        idx,
                                        depot_name
                                    from master_depot
                                    where status = 1")->result_array();
        return $result;
    }

    public function listClient()
    {
        $result = $this->db->query("select
                                        idx,
                                        client_id,
                                        client_code,
                                        client_name
                                    from master_client
                                    where status = 1
                                    order by client_name asc")->result_array();
        return $result;
    }

    public function listClientVerifyAccount()
    {
        $result = $this->db->query("select
                                        idx,
                                        client_id,
                                        client_code,
                                        client_name,
                                        address,
                                        city,
                                        postal_code,
                                        pic_name,
                                        mobile_phone,
                                        tax_id
                                    from master_client
                                    where status = 1 and status_account = 1
                                    order by client_name asc")->result_array();
        return $result;
    }

    public function listVendor()
    {
        $result = $this->db->query("select
                                        idx,
                                        vendor_id,
                                        vendor_code,
                                        vendor_name,
                                        address,
                                        city,
                                        postal_code,
                                        pic_name,
                                        mobile_phone,
                                        tax_id
                                    from master_vendor
                                    where status = 1
                                    order by vendor_name asc")->result_array();
        return $result;
    }

    public function listVendorVerifyAccount()
    {
        $result = $this->db->query("select
                                        idx,
                                        vendor_id,
                                        vendor_code,
                                        vendor_name,
                                        address,
                                        city,
                                        postal_code,
                                        pic_name,
                                        mobile_phone,
                                        tax_id
                                    from master_vendor
                                    where status = 1 and account_number IS NOT NULL
                                    order by vendor_name asc")->result_array();
        return $result;
    }

    public function listVehicle()
    {
        $result = $this->db->query("select
                                        idx,
                                        vehicle_no,
                                        vehicle_code,
                                        vehicle_type
                                    from master_vehicle
                                    where status = 1 and office_idx = " . $this->secure->decrypt_string($this->session->userdata('office_idx')) . "
                                    order by vehicle_code asc")->result_array();
        return $result;
    }

    public function listVehicleReady()
    {
        $result = $this->db->query("select
                                        idx,
                                        vehicle_no,
                                        vehicle_code,
                                        vehicle_type
                                    from master_vehicle
                                    where status = 1 and status_vehicle_ready = 1 and office_idx = " . $this->secure->decrypt_string($this->session->userdata('office_idx')) . "
                                    order by vehicle_code asc")->result_array();
        return $result;
    }

    public function listDriver()
    {
        $result = $this->db->query("select
                                        idx,
                                        driver_id,
                                        driver_name
                                    from master_driver
                                    where status = 1 and office_idx = " . $this->secure->decrypt_string($this->session->userdata('office_idx')) . "
                                    order by driver_name asc")->result_array();
        return $result;
    }

    public function listDriverReady()
    {
        $result = $this->db->query("select
                                        idx,
                                        driver_id,
                                        driver_name
                                    from master_driver
                                    where status = 1 and status_driver_ready = 1 and office_idx = " . $this->secure->decrypt_string($this->session->userdata('office_idx')) . "
                                    order by driver_name asc")->result_array();
        return $result;
    }

    public function listServices()
    {
        $result = $this->db->query("select
                                        idx,
                                        service_code,
                                        service_name
                                    from master_services
                                    where status = 1
                                    order by service_name asc")->result_array();
        return $result;
    }

    public function listOfficeUser($user_idx)
    {
        if ($user_idx == 1) {
            $result = $this->db->query("select
                                            idx as idx,
                                            office_name as office_name
                                        from master_office
                                        where status = 1")->result_array();
        } else {
            $result = $this->db->query("select
                                            o.idx as idx,
                                            o.office_name as office_name
                                        from user_access_office u, master_office o
                                        where u.user_idx = " . $user_idx . " and
                                                u.office_idx = o.idx and
                                                u.status = 1 and
                                                o.status = 1")->result_array();
        }

        return $result;
    }

    public function listLevelAccess()
    {
        $result = $this->db->query("select
                                        idx,
                                        level_name
                                    from user_access_level
                                    where status = 1 and app_type = 2")->result_array();
        return $result;
    }

    public function listModeTransport()
    {
        $result = $this->db->query("select
                                        status,
                                        status_name
                                    from status_mode_transport")->result_array();
        return $result;
    }

    public function listLoadingType()
    {
        $result = $this->db->query("select
                                        status,
                                        status_name
                                    from status_loading_type")->result_array();
        return $result;
    }

    public function listMovementType()
    {
        $result = $this->db->query("select
                                        status,
                                        status_name
                                    from status_movement_type")->result_array();
        return $result;
    }

    public function listStatusOrder()
    {
        $result = $this->db->query("select
                                        status,
                                        status_name
                                    from status_job_order")->result_array();
        return $result;
    }

    public function listStatusVehicleReady()
    {
        $result = $this->db->query("select
                                        status,
                                        status_name
                                    from status_vehicle_ready")->result_array();
        return $result;
    }

    public function listStatusDriverReady()
    {
        $result = $this->db->query("select
                                        status,
                                        status_name
                                    from status_driver_ready")->result_array();
        return $result;
    }

    public function listCityVendor()
    {
        $result = $this->db->query("select distinct(city)
                                    from master_vendor")->result_array();
        return $result;
    }

    public function listAccountAll()
    {
        $result = $this->db->query("select *
                                    from master_account
                                    where status = 1
                                    order by account_number asc")->result_array();
        return $result;
    }

    public function listAccountChild()
    {
        $result = $this->db->query("select *
                                    from master_account
                                    where status = 1 and parent_active = 0
                                    order by account_number asc")->result_array();
        return $result;
    }

    public function listAccountCashBank()
    {
        $result = $this->db->query("select *
                                    from master_account
                                    where status = 1 and parent_active = 0 and account_type = 1
                                    order by account_number asc")->result_array();
        return $result;
    }

    public function listAccountPayable()
    {
        $result = $this->db->query("select *
                                    from master_account
                                    where status = 1 and parent_active = 0 and account_type IN (13, 14, 15)
                                    order by account_number asc")->result_array();
        return $result;
    }

    public function listAccountReceivable()
    {
        $result = $this->db->query("select *
                                    from master_account
                                    where status = 1 and parent_active = 0 and account_type IN (12, 16)
                                    order by account_number asc")->result_array();
        return $result;
    }

    public function listPaymentMode()
    {
        $result = $this->db->query("select *
                                    from status_payment_mode
                                    where status > 0
                                    order by status asc")->result_array();
        return $result;
    }

    public function listAccountType()
    {
        $result = $this->db->query("select status as idx, status_name as account_name
                                    from status_account_type
                                    where status > 0
                                    order by status asc")->result_array();
        return $result;
    }

    public function listAccountSegment1($account_type)
    {
        $result = $this->db->query("select idx as idx, account_number as account_number, account_name as account_name
                                    from master_account
                                    where status = 1 and account_segment = 1 and account_type = " . $account_type . "
                                    order by account_number asc")->result_array();
        return $result;
    }

    public function listAccountSegment2($account_segment1)
    {
        $result = $this->db->query("select idx as idx, account_number as account_number, account_name as account_name
                                    from master_account
                                    where status = 1 and account_segment = 2 and parent_idx = " . $account_segment1 . "
                                    order by account_number asc")->result_array();
        return $result;
    }

    public function listAccountSegment3($account_segment2)
    {
        $result = $this->db->query("select idx as idx, account_number as account_number, account_name as account_name
                                    from master_account
                                    where status = 1 and account_segment = 3 and parent_idx = " . $account_segment2 . "
                                    order by account_number asc")->result_array();
        return $result;
    }

    private function _sendEmail($token, $type)
    {
        $config = [
            'protocol'  => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_user' => 'gigihadip.if@gmail.com',
            'smtp_pass' => 'Prabowo@161191',
            'smtp_port' => 465,
            'mailtype'  => 'html',
            'charset'   => 'utf-8',
            'newline'   => "\r\n"
        ];

        $this->email->initialize($config);

        $this->email->from('gigihadip.if@gmail.com', 'PT. Qourier');
        $this->email->to($this->input->post('signup-email'));

        if ($type == 'verify') {
            $this->email->subject('Account Verification');
            $this->email->message('Click this link to verify you account : <a href="' . base_url() . 'auth/verify?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '">Activate</a>');
        } else if ($type == 'forgot') {
            $this->email->subject('Reset Password');
            $this->email->message('Click this link to reset your password : <a href="' . base_url() . 'auth/resetpassword?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '">Reset Password</a>');
        }

        if ($this->email->send()) {
            return true;
        } else {
            echo $this->email->print_debugger();
            die;
        }
    }

    public function send_sms($hp, $pesan)
    {
        // Script Sent SMS 
        /*$userkey = 'tnwm2a';
		$passkey = 'yoga1507';
		$telepon = $hp;
		$message = $pesan;
		$url = 'https://gsm.zenziva.net/api/sendsms/';
		$curlHandle = curl_init();
		curl_setopt($curlHandle, CURLOPT_URL, $url);
		curl_setopt($curlHandle, CURLOPT_HEADER, 0);
		curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curlHandle, CURLOPT_TIMEOUT,30);
		curl_setopt($curlHandle, CURLOPT_POST, 1);
		curl_setopt($curlHandle, CURLOPT_POSTFIELDS, array(
			'userkey' => $userkey,
			'passkey' => $passkey,
			'nohp' => $telepon,
			'pesan' => $message
		));
		$results = json_decode(curl_exec($curlHandle), true);
		curl_close($curlHandle);*/
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://api.nusasms.com/api/v3/sendsms/plain',
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => array(
                'user' => 'qoorier_api',
                'password' => 'qOO@#12345',
                'SMSText' => $pesan,
                'GSM' => $hp,
                'otp' => 'Y'
            )
        ));
        $resp = curl_exec($curl);
        if (!$resp) {
            //die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
            return 0;
        } else {
            //header('Content-type: text/xml'); /*if you want to output to be an xml*/
            return 1;
        }
    }

    public function getDistanceBetweenPoints($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return compact('miles', 'feet', 'yards', 'kilometers', 'meters');
    }
}
