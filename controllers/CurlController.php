<?php

namespace Controller;
class CurlController
{
    public function __construct()
    {
    }

    public function curlRequest(string $url, $data)
    {
        $ch = curl_init($url);

        $headers = array(

            "Content-type: application/json;charset=\"utf-8\"",

            "Content-length: ".strlen($data),

            "Authorization: Basic " . base64_encode(getenv("TOKEN_CERTISIGN")),

            "Token: ". getenv("TOKEN_CERTISIGN")

        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER , 1);

        $result = json_decode(curl_exec($ch), true);

        if (curl_error($ch)) {
            dd($ch);
        }

        curl_close($ch);

        return $result;
    }


}