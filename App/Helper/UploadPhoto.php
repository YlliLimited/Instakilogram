<?php

namespace App\Helper;

trait UploadPhoto {
  private $src;
  private $activeSrc;
  private $tmp;
  public $filename;
  private $type;
  private $size;
  private $uploadFile;
  private $uploadActiveFile;
  private $errors = [];



  public function startupLoad($file) {
    $this->filename = str_replace(" ", "", $file['name']);
    $this->tmp = $file['tmp_name'];
    $this->size = $file['size'];
    $this->type = $file['type'];
    $this->uploadFile = $this->src . basename(time() . "-" . $this->filename);
    $this->uploadActiveFile = $this->activeSrc . basename(time() . "-" . $this->filename);
  }


  public function uploadFile() {
    if ($this->filename) {
      $fileExtension = explode(".", $this->filename);
      $fileExtension = end($fileExtension);
      $allowedExtensions = ["png", "jpg", "jpeg", "webp"];
    }

    if(in_array($fileExtension, $allowedExtensions) === false) {
      $this->errors[] = "File extension not allowed. Please upload images in one of these extensions: png, jpg, jpeg, webp";
    }

    if($this->size > 1048576) {
      $this->errors[] = "File size must be smaller or equal to 1MB";
    }

    if(strlen($this->uploadFile) > 255) {
      $this->errors[] = "File name is too long";
    }

    if (empty($this->errors)) {
      move_uploaded_file($this->tmp, $this->uploadFile);
      return true;
    } else {

      return false;
    }

  }
  

}