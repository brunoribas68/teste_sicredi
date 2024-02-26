<?php

require 'CurlController.php';
class DocumentController{

    private string $signUrl;
    public function __construct()
    {
        $this->signUrl = getenv('URL_CERTISIGN');

        return $this->requestedByFront();

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

    public function requestedByFront(): string
    {
        $fileName = "/var/www/html/storage/uploads/";
        $fileName = $fileName . basename($_FILES["file"]["name"]);

        if($this->fileValidation($fileName)){
            if (!file_exists($fileName)) {
                move_uploaded_file($_FILES["file"]["tmp_name"], $fileName);
            }

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

        return 'Arquivo nao valido!';

    }

    public function isRequestedByFront(){
        return $_POST['firstEmail']  && $_SERVER['HTTP_REFERER'] == getenv("HOST");
    }

    public function fileValidation($filename)
    {
        $imageFileType = strtolower(pathinfo($filename,PATHINFO_EXTENSION));

        if ($_FILES["file"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            return false;
        }

        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "pdf" ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            return false;
        }

        return true;
    }


}