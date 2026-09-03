<?php

use Illuminate\Support\Facades\Log;

function commanCurlCall($url, $method = 'GET', $data = [], $headers = [])
{
    try {
        $method = strtoupper($method);
 
        // Default headers
        $defaultHeaders = [
            "Content-Type" => "application/json",
        ];
 
        // Merge headers (user can override)
        $headers = array_merge($defaultHeaders, $headers);
 
        // Detect content type
        $isFormUrlEncoded = false;
        foreach ($headers as $key => $value) {
            if (
                stripos($key, 'Content-Type') !== false &&
                stripos($value, 'application/x-www-form-urlencoded') !== false
            ) {
                $isFormUrlEncoded = true;
            }
        }
 
        // Format headers for curl
        $formattedHeaders = [];
        foreach ($headers as $key => $value) {
            $formattedHeaders[] = "$key: $value";
        }
 
        // If GET and data exists → append query string
        if ($method === 'GET' && !empty($data)) {
            $queryString = http_build_query($data);
            $url .= (strpos($url, '?') === false ? '?' : '&') . $queryString;
        }
 
        $curl = curl_init();
 
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $formattedHeaders,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);
 
        // Attach body for non-GET requests
        if ($method !== 'GET' && !empty($data)) {
            if ($isFormUrlEncoded) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
            } else {
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            }
        }
 
        $response = curl_exec($curl);
 
        // Error handling
        if ($response === false) {
            $error = curl_error($curl);
            curl_close($curl);
 
            return [
                'status' => false,
                'error' => $error,
            ];
        }
 
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
 
        return [
            'status' => $httpCode >= 200 && $httpCode < 300,
            'response' => json_decode($response, true),
        ];
    } catch (\Throwable $e) {
        Log::error('commanCurlCall', ['message' => $e->getMessage(), 'traceAsString' => $e->getTraceAsString()]);
        return [
            'status' => false,
            'error' => $e->getMessage(),
        ];
    }
}