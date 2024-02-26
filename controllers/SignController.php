<?php

class SignController{
    private string $signUrl;

    public function __construct()
    {
        $this->signUrl = getenv('URL_CERTISIGN');

    }

    public function createUploadRequest(string $filename): string
    {
        $pdfContent = file_get_contents($filename);

        $pdfBytes = [];
        for ($i = 0; $i < strlen($pdfContent); $i++) {
            $pdfBytes[] = ord($pdfContent[$i]);
        }

        $data = [
            "fileName" => $filename,
            "bytes" => $pdfBytes
        ];

        return json_encode($data);
    }

    public function createSendToSignRequest(array $uploadedResponse, array &$signers,  array &$eletronicSigner): string
    {
        $file = explode('/', $uploadedResponse["uploadId"]);
        $fileName = end($file);

        $request = [
            "document"=> [
                "name"=> $fileName,
                "upload"=> [
                    "id"=> $uploadedResponse["uploadId"],
                    "name"=> $fileName
                ]
            ],
            "sender" => [
                "name"=> "Bruno",
                "email"=> "brunoribas68@gmail.com",
                "individualIdentificationCode"=> null
            ],
            "signers" => $signers,
            "electronicSigners" => $eletronicSigner
        ];

        return json_encode($request);
    }

    public function uploadDocumentRequest($file = "/var/www/html/storage/uploads/carta-assinatura.pdf"): array
    {
        $curl = new \Controller\CurlController();

        return $curl->curlRequest($this->signUrl."/document/upload", $this->createUploadRequest($file));
    }

    public function sendToSignRequest(array $uploadedDocument, array $signer, ?array$eletronicSigner):string
    {
        $sendToSign = $this->createSendToSignRequest($uploadedDocument, $signer, $eletronicSigner);

        $curl = new \Controller\CurlController();
        $uploadedDocument = $curl->curlRequest($this->signUrl."/document/create", $sendToSign);

        return json_encode($uploadedDocument);
    }

    public function sendToSign(string $fileName)
    {

        $uploadedDocument = $this->uploadDocumentRequest($fileName);
        $signer = [
            [
                "step"=> 2,
                "title"=> "Signer",
                "name"=> $_POST['firstName'],
                "email"=> $_POST['firstEmail']
            ]
        ];

        $eletronicSigner = [
            [
                "step"=> 3,
                "title"=> "Signer",
                "name"=> $_POST['secondName'],
                "email"=> $_POST['secondEmail']
            ]
        ];

        echo $this->sendToSignRequest($uploadedDocument, $signer, $eletronicSigner);
        die;
    }

}