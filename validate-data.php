<?php

public function verifyMyshopifyDomain($shop) {
        return preg_match("/\A[a-zA-Z0-9][a-zA-Z0-9\-]*\.myshopify\.com\z/", $shop);
    }
    
    function verifyEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    function verifyId($id) {
        return filter_var($id, FILTER_VALIDATE_INT);
    }

    function verifyShopifyOrderId($orderId) {
        return preg_match("/^[1-9]+\d{12,}$/", $orderId);
    }

    function verifyMobileNumber($mobile_no, $country_code = 'IN') {
        if($country_code == 'IN') {
            return preg_match("/^((\+){0,1}91(\s){0,1}(\-){0,1}(\s){0,1})?([0-9]{10})$/", $mobile_no);
        }

        return false;
    }

    function verifyUpiId($upi_id) {
        return preg_match("/^[\w.-]+@[\w.-]+$/", $upi_id);
    }

    function verifyBankIFSC($ifsc_code) {
        $return = $this->curl("https://ifsc.razorpay.com/".$ifsc_code);
        # return "Not Found" in case invalid IFSC code 
        return (isset($return['IFSC']) && $return['IFSC'] != '') ? true : false;
    }

    function verifyHumanName($name) {
        return preg_match('/^\w[\w ]*[a-zA-Z]$/', $name);
    }

function base64url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

 function curl($url, $method = "GET", $params = []) {
        try{
            $ch = curl_init();

            if($method === 'GET' && !empty($params)) {
                $build_query = http_build_query($params);
                $url .= '?'.$build_query;
            }
            
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    
            if($method === 'POST') {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
            }

            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    
            $buffer = curl_exec($ch);
            curl_close($ch);
    
            if (empty($buffer)){
                throw new Exception(curl_error($ch), curl_errno($ch));
            }
            
            return json_decode($buffer, true);
        }
        catch(Exception $e) {
            return ['err'=> 'failed'];
        }
    }

    
