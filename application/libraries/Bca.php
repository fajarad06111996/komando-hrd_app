<?php
if (!defined("BASEPATH")) exit("No direct script access allowed");

class Bca
{
    // Set Credential**********************************************
    var $api_key            = 'a16c5bb4-49d1-4a12-9194-db3df367d893';
    var $api_secret         = '2ad77de8-7f0e-4379-bce5-71d70529a611';
    var $client_id          = 'e305a76a-78d3-4f92-b734-c23ae58c97d8';
    var $client_secret      = '04031743-9645-4bc7-84e5-c8943618c2c8';
    var $channel_id         = '95051';
    var $credential_id      = 'UATCORP001';
    var $corporate_id       = 'UATCORP001';
    var $source_acc_number  = '0611105893';
    var $url                = 'https://devapi.klikbca.com:9443';
    
    function request_token_to_bca()
    {
        $string_to_auth     = $this->client_id . ':' . $this->client_secret;
        $auth_basic         = base64_encode($string_to_auth);
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL               => $this->url . '/api/oauth/token',
          CURLOPT_RETURNTRANSFER    => true,
          CURLOPT_ENCODING          => '',
          CURLOPT_MAXREDIRS         => 10,
          CURLOPT_TIMEOUT           => 0,
          CURLOPT_FOLLOWLOCATION    => true,
          CURLOPT_HTTP_VERSION      => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST     => 'POST',
          CURLOPT_POSTFIELDS        => 'grant_type=client_credentials',
          CURLOPT_HTTPHEADER        => array(
            'Authorization: Basic ' . $auth_basic,
            'Content-Type: application/x-www-form-urlencoded'
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        return $response;
    }
        
    function signature_to_inquiry_nama($bank_code, $account_number, $time_stamp, $token)
    {
        // Get Authorization & Header**********************************************
        $x_bca_key          = $this->api_key;
        $x_bca_time_stamp   = $time_stamp;
        $HTTPMethod         = 'GET';
        $RelativeUrl        = '/banking/corporates/transfers/v2/domestic/beneficiaries/banks/' . $bank_code . '/accounts/' . $account_number;
        $access_token       = $token;
        
        // Get Request Body**********************************************
        $RequestBody        = ''; 
        $hashRequestBody    = strtolower(hash("sha256", $RequestBody));
        
        
        // Param Signature ***************************************************
        $StringToSign       = $HTTPMethod . ":" . $RelativeUrl . ":" . $access_token . ":" . $hashRequestBody . ":" . $x_bca_time_stamp;
        $signature          = hash_hmac('sha256', $StringToSign, $this->api_secret);
        return $signature;
    }
    
    function signature_to_online_transfer($transaction_id, $transaction_date, $reference_id, $account_number, $amount, $time_stamp, $token)
    {
        // Format amount ***************************************
        $amount             = number_format($amount,2,'.','');
        
        // Get Authorization & Header**********************************************
        $x_bca_key          = $this->api_key;
        $x_bca_time_stamp   = $time_stamp;
        $HTTPMethod         = 'POST';
        $RelativeUrl        = '/banking/corporates/transfers';
        $access_token       = $token;
        
        // Get Request Body**********************************************
        $RequestBody = [
            "CorporateID"               => $this->corporate_id,
            "SourceAccountNumber"       => $this->source_acc_number,
            "TransactionID"             => $transaction_id,
            "TransactionDate"           => $transaction_date,
            "ReferenceID"               => $reference_id,
            "CurrencyCode"              => 'IDR',
            "Amount"                    => $amount,
            "BeneficiaryAccountNumber"  => $account_number,
            "Remark1"                   => "Withdraw Balance",
            "Remark2"                   => "Komando Cash"
        ];
        $jsonRequestBody    = json_encode($RequestBody);
        $jsonRequestBody    = str_replace(array("\r", "\n", "\t", " "), array('', '', '', ''), $jsonRequestBody);
        $hashRequestBody    = strtolower(hash("sha256", $jsonRequestBody));
        
        
        // Param Signature ***************************************************
        $StringToSign       = $HTTPMethod . ":" . $RelativeUrl . ":" . $access_token . ":" . $hashRequestBody . ":" . $x_bca_time_stamp;
        $signature          = hash_hmac('sha256', $StringToSign, $this->api_secret);
        return $signature;
    }
    
    function signature_to_domestik_transfer($transaction_id, $transaction_date, $account_number, $bank_code, $account_name, $amount, $time_stamp, $token)
    {
        // Format amount ***************************************
        $amount             = number_format($amount,2,'.','');
        
        // Get Authorization & Header**********************************************
        $x_bca_key          = $this->api_key;
        $x_bca_time_stamp   = $time_stamp;
        $HTTPMethod         = 'POST';
        $RelativeUrl        = '/banking/corporates/transfers/v2/domestic';
        $access_token       = $token;
        
        // Get Request Body**********************************************
        $RequestBody = [
            "transaction_id"                => $transaction_id,
            "transaction_date"              => $transaction_date,
            "source_account_number"         => $this->source_acc_number,
            "beneficiary_account_number"    => $account_number,
            "beneficiary_bank_code"         => $bank_code,
            "beneficiary_name"              => $account_name,
            "amount"                        => $amount,
            "transfer_type"                 => "ONL",
            "beneficiary_cust_type"         => "3",
            "beneficiary_cust_residence"    => "1",
            "currency_code"                 => "IDR",
            "remark1"                       => "",
            "remark2"                       => "",
            "beneficiary_email"             => ""
        ];
        $jsonRequestBody    = json_encode($RequestBody);
        $jsonRequestBody    = str_replace(array("\r", "\n", "\t", " "), array('', '', '', ''), $jsonRequestBody);
        $hashRequestBody    = strtolower(hash("sha256", $jsonRequestBody));
        
        
        // Param Signature ***************************************************
        $StringToSign       = $HTTPMethod . ":" . $RelativeUrl . ":" . $access_token . ":" . $hashRequestBody . ":" . $x_bca_time_stamp;
        $signature          = hash_hmac('sha256', $StringToSign, $this->api_secret);
        return $signature;
    }

    function inquiry_nama_to_bca($bank_code, $account_number, $time_stamp, $signature, $token)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL               => $this->url . '/banking/corporates/transfers/v2/domestic/beneficiaries/banks/' . $bank_code . '/accounts/' . $account_number,
          CURLOPT_RETURNTRANSFER    => true,
          CURLOPT_ENCODING          => '',
          CURLOPT_MAXREDIRS         => 10,
          CURLOPT_TIMEOUT           => 0,
          CURLOPT_FOLLOWLOCATION    => true,
          CURLOPT_HTTP_VERSION      => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST     => 'GET',
          CURLOPT_HTTPHEADER        => array(
            'X-BCA-Key: ' . $this->api_key,
            'X-BCA-Timestamp: ' . $time_stamp,
            'X-BCA-Signature: ' . $signature,
            'channel-id: ' . $this->channel_id,
            'credential-id: ' . $this->credential_id,
            'Authorization: Bearer ' . $token
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        return $response;
    }
    
    function push_online_transfer($transaction_id, $transaction_date, $account_number, $reference_id, $account_name, $amount, $time_stamp, $signature, $token)
    {
        //Format $transaction_date = 'YYYY-mm-dd'
        // Format amount ***************************************
        $amount                     = number_format($amount,2,'.','');
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL               => $this->url . '/banking/corporates/transfers',
          CURLOPT_RETURNTRANSFER    => true,
          CURLOPT_ENCODING          => '',
          CURLOPT_MAXREDIRS         => 10,
          CURLOPT_TIMEOUT           => 0,
          CURLOPT_FOLLOWLOCATION    => true,
          CURLOPT_HTTP_VERSION      => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST     => 'POST',
          CURLOPT_POSTFIELDS        =>'{ 
             "CorporateID":"' . $this->corporate_id . '", 
             "SourceAccountNumber":"' . $this->source_acc_number . '", 
             "TransactionID":"' . $transaction_id . '", 
             "TransactionDate":"' . $transaction_date . '", 
             "ReferenceID":"' . $reference_id . '", 
             "CurrencyCode":"IDR", 
             "Amount":"' . $amount . '", 
             "BeneficiaryAccountNumber":"' . $account_number . '", 
             "Remark1":"Withdraw Balance", 
             "Remark2":"Komando Cash"
            }',
          CURLOPT_HTTPHEADER        => array(
            'X-BCA-Key: ' . $this->api_key,
            'X-BCA-Timestamp: ' . $time_stamp,
            'X-BCA-Signature: ' . $signature,
            'channel-id: ' . $this->channel_id,
            'credential-id: ' . $this->credential_id,
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        return $response;
    }
    
    function push_domestik_transfer($transaction_id, $transaction_date, $account_number, $bank_code, $account_name, $amount, $time_stamp, $signature, $token)
    {
        //Format $transaction_date = 'YYYY-mm-dd'
        // Format amount ***************************************
        $amount                     = number_format($amount,2,'.','');
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL               => $this->url . '/banking/corporates/transfers/v2/domestic',
          CURLOPT_RETURNTRANSFER    => true,
          CURLOPT_ENCODING          => '',
          CURLOPT_MAXREDIRS         => 10,
          CURLOPT_TIMEOUT           => 0,
          CURLOPT_FOLLOWLOCATION    => true,
          CURLOPT_HTTP_VERSION      => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST     => 'POST',
          CURLOPT_POSTFIELDS        =>'{ 
             "transaction_id":"' . $transaction_id . '", 
             "transaction_date":"' . $transaction_date . '", 
             "source_account_number":"' . $this->source_acc_number . '", 
             "beneficiary_account_number":"' . $account_number . '", 
             "beneficiary_bank_code":"' . $bank_code . '", 
             "beneficiary_name":"' . $account_name . '", 
             "amount":"' . $amount . '", 
             "transfer_type":"ONL", 
             "beneficiary_cust_type":"3", 
             "beneficiary_cust_residence":"1", 
             "currency_code":"IDR", 
             "remark1":"", 
             "remark2":"", 
             "beneficiary_email":""
            }',
          CURLOPT_HTTPHEADER        => array(
            'X-BCA-Key: ' . $this->api_key,
            'X-BCA-Timestamp: ' . $time_stamp,
            'X-BCA-Signature: ' . $signature,
            'channel-id: ' . $this->channel_id,
            'credential-id: ' . $this->credential_id,
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        return $response;
    }
}