<?php


class DocumentController{

    use CurlTrait;
    public function __construct()
    {
        $this->uploadDocumentRequest();
    }

    public function readFilesBytes()
    {
        $filename = "/var/www/html/controllers/carta-assinatura.pdf";
        $handle = fopen($filename, "rb");
        $fsize = filesize($filename);
        $contents = fread($handle, $fsize);
        $byteArray = unpack("N*",$contents);

        return $byteArray;
    }

    public function uploadDocumentRequest()
    {
//        getenv("TOKEN_CERTISIGN")
//        dd([getenv("URL_CERTISIGN"),]);
        new CurlTrait(getenv("URL_CERTISIGN"), $this->readFilesBytes());
    }
}