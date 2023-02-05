<?php

namespace Umuttaymaz\ParatikaPhp\Client;

class HttpClient
{
    public function makeRequest($url, $requestData)
    {
        $request = curl_init();

        curl_setopt($request, CURLOPT_URL, $url);
        curl_setopt($request, CURLOPT_HEADER, false);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($request, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', "Expect:"));
        curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($request, CURLOPT_POSTFIELDS, $requestData);

        $post_response = curl_exec($request);
        curl_close($request);

        return json_decode($post_response);
    }
}