<?php

namespace App\Traits;

use Exception;

trait RequestTrait
{
    private function apiRequest($method, $parameters = [])
    {
        $url = 'https://api.telegram.org/bot' . env('TELEGRAM_BOT_TOKEN') . '/' . $method;
        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($handle, CURLOPT_TIMEOUT, 30);
        curl_setopt($handle, CURLOPT_IPRESOLVE, '192.168.2.9');
        curl_setopt($handle, CURLOPT_POSTFIELDS, http_build_query($parameters));
        $response = curl_exec($handle);
        curl_close($handle);
        if ($response === false) {
            throwException($e = new Exception($response['description']));
            return false;
        }
        $response = json_decode($response, true);
        $response = $response['result'];
        return $response;

    }

    private function apiGetFileId($file_id)
    {
        $url = 'https://api.telegram.org/bot' . env('TELEGRAM_BOT_TOKEN') . '/' . $file_id;
        $update = file_get_contents($url);
        $update = urldecode($update);
        $update = json_decode($update);
        $result = $update->result;
        $filePath = $result->file_path;
        return $filePath;
    }

}