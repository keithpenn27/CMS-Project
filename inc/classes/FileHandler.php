<?php

 // If there is no constant defined called __CONFIG__ do not load this file
 if(!defined('__CONFIG__')) {
    header('Location: ../index.php');
    exit;

}

include_once ('Url.php');

class FileHandler {

    public $file;
    private $err;
    private $tempDir;
    private $newDir;
    public $mimeType;
    private $fileSize;
    public $filePath;


    /**
     * Contructs the file to be uploaded
     * @param Array The file stored in $_FILES. (i.e $_FILES['file'])
     */
    public function __construct($file) {
        $this->fileName = $file['name'];

        // Check if the file name exists. If so, add an incremented number and reassign. 
        $this->fileName = $this->exists($this->fileName);

        // Get the rest of our file info
        $this->err = $file['error'];
        $this->fileSize = $file['size'];
        $this->mimeType = $file['type'];

        // Set up the dirs and paths
        $this->tempDir = $file['tmp_name'];
        $this->newDir = Url::getBasePath() . 'uploads/';
        $this->filePath = '../uploads/' . $this->fileName;
    }

    /**
     * Moves the uploaded song to a permanent directory and inserts the song info into the database
     * @param Array $arr A multidimensional array containing the form input values and PDO::Params. Used to bind the params to place holders in the query.
     */
    public function songUpload($arr) {

        $ext = $this->mimeType;

        if ($ext == 'audio/mp3' || $ext == 'audio/ogg' || $ext == 'audio/wav' || $ext == 'audio/vnd.wav') {
            $con = DB::getConnection();

            move_uploaded_file($this->tempDir, $this->filePath);

            // Store the file info in the database.
            try {
                $addFile = $con->prepare("INSERT INTO files(filename, owner, artist, album, song_title, mime_type) VALUES(:filename, :owner, :artist, :album, :song_title, :mime_type)");

                $addFile->bindParam(':filename', $this->fileName, PDO::PARAM_STR);
                $addFile->bindParam(':mime_type', $this->mimeType, PDO::PARAM_STR);

                // The $val passed into bindParam has to be passed by refernce
                foreach($arr as $key => &$val) {

                    $addFile->bindParam($key, $val["pdoVal"], $val['pdoType']);
                }


                $addFile->execute();
            } catch (PDOException $e) {
                var_dump($e->getMessage());
            }
        }
    }

    
    /**
     * Moves the uploaded image to a permanent directory and inserts the image info into the database
     * @param Array $arr A multidimensional array containing the form input values and PDO::Params. Used to bind the params to place holders in the query.
     */
    public function imageUpload($arr) {

        $ext = $this->mimeType;

        if ($ext == 'image/jpeg' || $ext == 'image/gif' || $ext == 'image/png') {
            $con = DB::getConnection();

            move_uploaded_file($this->tempDir, $this->filePath);

            // Store the file info in the database.
            try {
                $addFile = $con->prepare("INSERT INTO files(filename, owner, image_title, mime_type) VALUES(:filename, :owner, :image_title, :mime_type)");

                $addFile->bindParam(':filename', $this->fileName, PDO::PARAM_STR);
                $addFile->bindParam(':mime_type', $this->mimeType, PDO::PARAM_STR);


                // The $val passed into bindParam has to be passed by refernce
                foreach($arr as $key => &$val) {
                    $addFile->bindParam($key, $val["pdoVal"], $val['pdoType']);
                }


                $addFile->execute();
            } catch (PDOException $e) {
                var_dump($e->getMessage());
            }
        }
    }

    /**
     * @return Returns the temporary upload direction to the current instance.
     */
    public function getTempDir() {
        return $this->tempDir;
    }

    /**
     * @return Returns the entire source link to the current instance.
     */
    public function getsrc() {
        return (string) $this->newDir . $this->fileName;
    }

    /**
     * @return Returns the file path to the current instance.
     */
    public function getFilePath() {
        return $this->filePath;
    }

    /**
     * Checks if the current file exists in directory. If so, add an incremented number to the end of the file name
     * @param String $tmpFile The file name to check.
     * @return Returns a string of the new file name if it already exists or the original file name if it does not.
     */
    public function exists($tmpFile){
        $file = $tmpFile;
        $path = '../uploads/';

        if ($pos = strrpos($file, '.')) {
            $name = substr($file, 0, $pos);
            $ext = substr($file, $pos);
        } else {
            $name = $file;
        }

        $newpath = $path . $file;
        $newname = $file;
        $counter = 1;
        while (file_exists($newpath)) {
            $newname = $name .'_'. $counter . $ext;
            $newpath = $path.'/'.$newname;
            $counter++;
        }
 
     return $newname;

    }

    // Delete all files from the uploads directory by specific user if user is deleted.

}
?>