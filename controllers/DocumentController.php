<?php

require 'CurlController.php';
require 'SignController.php';
class DocumentController{

    private string $fileName;

    public function __construct()
    {
        $this->uploadFile();
        return $this->sentToSign();
    }

    public function uploadFile()
    {
        $this->fileName = "/var/www/html/storage/uploads/";
        $this->fileName = $this->fileName . basename($_FILES["file"]["name"]);
        if($this->fileValidation($this->fileName)){
            if (!file_exists($this->fileName)) {
                move_uploaded_file($_FILES["file"]["tmp_name"], $this->fileName);
            }
        }

        return 'Arquivo nao valido!';

    }

    public function sentToSign(): string
    {
        $sign = new SignController();

        return $sign->sendToSign($this->fileName);
    }


    public function fileValidation($filename)
    {
        $imageFileType = strtolower(pathinfo($filename,PATHINFO_EXTENSION));

        if ($_FILES["file"]["size"] > 500000) {
            echo "Arquivo muito grande!";
            return false;
        }

        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "pdf" ) {
            echo "Apenas sao permitidos JPG, JPEG, PNG & GIF.";
            return false;
        }

        return true;
    }


}