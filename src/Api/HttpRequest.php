<?php

namespace Moladoust\Rubrularavel\Api;

class HttpRequest
{
    private $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * Prepares and sends the request to the server via HTTP POST
     *
     * @param   [string|array]  $data Request body in json encoded or url encoded format
     * @param   string  $format Content type
     * @return  array   Response array
     * @throws  HttpException
     * @throws  JsonException
     * @throws  NetworkException
     */
    public function call($data, $method = 'POST', $headers = [], $format = 'json')
    {
        $contentType = 'application/json';

        if ($format !== 'json') {
            $contentType = 'application/x-www-form-urlencoded';
            $queryString = '';
            if (is_array($data)) {
                foreach($data as $key => $value) {
                    $queryString .= $key . '=' . $value . '&';
                }
                $data = rtrim($queryString, '&');
            }
        }

        $hd = [
            'Accept: application/json',
            "Content-Type: $contentType",
        ];

        $method = strtoupper($method);

        $headers = array_merge($hd, $headers);

        // set request options
        $curl = curl_init($this->url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method); 
        if ($method == 'POST') curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        // make the request
        $response = curl_exec($curl);
        $errNo = curl_errno($curl);
        if ($errNo !== 0) {
            throw new NetworkException(curl_error($curl), $errNo);
        }

        // check HTTP status code
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if ($http_code !== 200) {
            throw new HttpException('HTTP Error', $http_code);
        }

        // decode JSON response
        $response = json_decode($response, true);
        if ($response === null) {
            throw new JsonException('Invalid Response', json_last_error());
        }

        return $response;
    }
    
}

