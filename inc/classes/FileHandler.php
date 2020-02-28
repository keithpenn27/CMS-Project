<?php

 // If there is no constant defined called __CONFIG__ do not load this file
 if(!defined('__CONFIG__')) {
    header('Location: ../index.php');
    exit;

}

include_once ('Url.php');

class FileHandler {

    public $fileName;
    private $err;
    private $tempDir;
    private $newDir;
    public $mimeType;
    private $fileSize;
    private $filePath;


    public function __construct($file) {
        $this->fileName = $file['name'];
        $this->err = $file['error'];
        $this->fileSize = $file['size'];
        $this->mimeType = $file['type'];
        $this->tempDir = $file['tmp_name'];
        $this->newDir = Url::getBasePath() . 'uploads/';
        $this->filePath = '../uploads/' . $this->fileName;

    }

    public function songUpload($arr) {

        $con = DB::getConnection();

        move_uploaded_file($this->tempDir, $this->filePath);

         // Store the file info in the database.
        try {
            $addFile = $con->prepare("INSERT INTO files(filename, owner, artist, album, song_title, mime_type) VALUES(:filename, :owner, :artist, :album, :song_title, :mime_type)");

            $addFile->bindParam(':filename', $this->fileName, PDO::PARAM_STR);
            $addFile->bindParam(':mime_type', $this->mimeType, PDO::PARAM_STR);

            // The $val passed into bindParam has to be passed by refernce
            foreach($arr as $key => &$val) {
                $addFile->bindParam($key, $val);
            }


            $addFile->execute();
        } catch (PDOException $e) {
            var_dump($e->getMessage());
        }
    }

    public function getTempDir() {
        return $this->tempDir;
    }

    public function getsrc() {
        return (string) $this->newDir . $this->fileName;
    }

    public function getFilePath() {
        return $this->filePath;
    }

}
?>