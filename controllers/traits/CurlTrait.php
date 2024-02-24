<?php

trait CurlTrait{
    public function __construct($url, $arquivo)
    {
        $this->curlRequest($url, $arquivo);
    }

    public function curlRequest(string $url, $arquivo = null)
    {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_FILE, $arquivo);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        curl_exec($ch);
        if(curl_error($ch)) {
            fwrite($arquivo, curl_error($ch));
        }
        curl_close($ch);
        fclose($arquivo);
    }
}