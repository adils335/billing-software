<?php

namespace app\components;

use yii;
use yii\helpers\Url;

class Curl extends yii\base\BaseObject{
    const POST = 'POST';
    const GET = 'GET';
    const PUT = 'PUT';
    
    public function callApi( $url, $method, $headerParams, $queryParams = '', $postData = Null ){
        $headers = [];
        $fields_string = '';
        foreach ($headerParams as $key => $val) {
             $headers[] = "$key: $val";
        }
        if (!empty($queryParams)) {
             $url = ($url . '?' . http_build_query($queryParams));
        }
        $fields_string = '';
        if ($postData && in_array('Content-Type: application/x-www-form-urlencoded', $headers, true) ) {
            $fields_string = http_build_query($postData);
        } elseif ((is_object($postData) || is_array($postData)) && !in_array('Content-Type: multipart/form-data', $headers, true)) { // json model
            $fields_string = json_encode($postData);
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        //curl_setopt($curl, CURLOPT_DNS_CACHE_TIMEOUT, 2 );
        if( $method == self::PUT ){
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
        }
        if( $method == self::POST || $method == self::PUT){
            curl_setopt($curl, CURLOPT_POSTFIELDS, $fields_string);
        }
        //echo "<pre>";print_r( $curl );die();
        // Make the request
        $response = curl_exec($curl);
        
        //echo "<pre>";print_r( curl_getinfo($curl) ) . '<br/>';
        //echo "<pre>";var_dump( curl_errno($curl) ) . '<br/>';
        //echo "<pre>";var_dump( curl_error($curl) ) . '<br/>';
        //die();
        if( curl_errno($curl) ){
            var_dump( curl_getinfo($curl) ) . '<br/>';
            var_dump( curl_errno($curl) ) . '<br/>';
            var_dump( curl_error($curl) ) . '<br/>';die();
        }
        //var_dump( "https://api.mastergst.com/einvoice/type/GENERATE/version/V1_03?email=adils335%40gmail.com" , $url );
        //echo "<pre>";var_dump($url,$method, $headerParams, $queryParams, $postData, $fields_string, $response );die();
        $http_header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $http_header = $this->httpParseHeaders(substr($response, 0, $http_header_size));
        $http_body = substr($response, $http_header_size);
        $response_info = curl_getinfo($curl);
        $data = '';
        // Handle the response
        if ($response_info['http_code'] === 0) {
            $curl_error_message = curl_error($curl);

            // curl_exec can sometimes fail but still return a blank message from curl_error().
            if (!empty($curl_error_message)) {
                $error_message = "API call to $url failed: $curl_error_message";
            } else {
                $error_message = "API call to $url failed, but for an unknown reason. " .
                    "This could happen if you are disconnected from the network.";
            }

        } else{
            $data = json_decode($http_body);
            if (json_last_error() > 0) { // if response is a string
                $data = $http_body;
            }
        } 
        return [$data, $response_info['http_code'], $http_header];
    }

    /**
     * Return an array of HTTP response headers
     *
     * @param string $raw_headers A string of raw HTTP response headers
     *
     * @return string[] Array of HTTP response heaers
     */
    protected function httpParseHeaders($raw_headers)
    {
        $headers = [];
        $key = '';

        foreach (explode("\n", $raw_headers) as $h) {
            $h = explode(':', $h, 2);

            if (isset($h[1])) {
                if (!isset($headers[$h[0]])) {
                    $headers[$h[0]] = trim($h[1]);
                } elseif (is_array($headers[$h[0]])) {
                    $headers[$h[0]] = array_merge($headers[$h[0]], [trim($h[1])]);
                } else {
                    $headers[$h[0]] = array_merge([$headers[$h[0]]], [trim($h[1])]);
                }

                $key = $h[0];
            } else {
                if (substr($h[0], 0, 1) === "\t") {
                    $headers[$key] .= "\r\n\t".trim($h[0]);
                } elseif (!$key) {
                    $headers[0] = trim($h[0]);
                }
                trim($h[0]);
            }
        }

        return $headers;
    }
}