<?php

 // If there is no constant defined called __CONFIG__ do not load this file
 if(!defined('__CONFIG__')) {
    header('Location: ' . __PATH__);
    exit;

}

class FileHandler {

    public $file;
    private $err;
    private $tempDir;
    private $newDir;
    public $mimeType;
    private $fileSize;
    public $filePath;

    // Relative path to user's uploads dir
    private $dir;


    /**
     * Contructs the file to be uploaded
     * @param Array The file stored in $_FILES. (i.e $_FILES['file'])
     */
    public function __construct($file) {
        $this->fileName = $file['name'];

        // We can't use our __PATH__ constant here because move_uploaded_file() will not work over http
        $this->dir = '../uploads/' . self::getUserDir();

        // Check if the file name exists. If so, add an incremented number and reassign. 

        $this->fileName = $this->exists($this->dir, $this->fileName);

        $this->filePath = $this->dir . $this->fileName;

        // Get the rest of our file info
        $this->err = $file['error'];
        $this->fileSize = $file['size'];
        $this->mimeType = $file['type'];
        
        // Set up the dirs and paths
        $this->tempDir = $file['tmp_name'];
        $this->newDir = $this->dir;


        // Check if the users upload dir exists
        if (!is_dir($this->dir)) {
            mkdir($this->dir, 0777, true);
            chmod('../uploads', 0777);
            chmod('../uploads/' . substr(self::getUserDir($_SESSION['user_id']), 0, strlen(self::getUserDir($_SESSION['user_id'])) - 1), 0777);
        }
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
                echo $e->getMessage();
            }
        }
    }

    
    /**
     * Moves the uploaded image to a permanent directory and inserts the image info into the database
     * @param Array $arr A multidimensional array containing the form input values and PDO::Params. Used to bind the params to place holders in the query.
     */
    public function imageUpload($arr) {

        // Get the mime type so we can handle audio and images separately
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
                echo $e->getMessage();
            }
        } else {
            die("The file type is not allowed.");
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
    public function exists($path, $filename){
        if ($pos = strrpos($filename, '.')) {
            $name = substr($filename, 0, $pos);
            $ext = substr($filename, $pos);
     } else {
            $name = $filename;
     }
 
     $newpath = $path.'/'.$filename;
     $newname = $filename;
     $counter = 1;
     while (file_exists($newpath)) {
            $newname = $name .'_'. $counter . $ext;
            $newpath = $path.'/'.$newname;
            $counter++;
      }
 
     return $newname;
 }

    /**
     * Gets the directory of the currently logged in user
     * @return Returns the directory name of the user's file folder inside of the uploads directory
     */
    public static function getUserDir() {

        $user = User::getCurrentUser();
        $uEmail = $user['email'];

        $pos = strrpos($uEmail, '@');
        $userDir = substr($uEmail, 0, $pos) . '/';

        return $userDir;
    }

    /**
     * Returns a list of songs uploaded by the given user formatted to display in an html5 audio player
     * @param $uid The user id that uploaded the files. 
     * @param $con The database connection PDO object.
     */
    public static function getSongs($uid, $con) {

        try {
            $getFile = $con->prepare("SELECT * FROM files WHERE owner = :uid AND mime_type LIKE 'audio/%' ORDER BY upload_date DESC LIMIT 5");
            $getFile->bindParam(':uid', $uid);
            $getFile->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        $sListDisplay = "";

        // Build our song list with the html5 audio player
        while ($file = $getFile->fetch(PDO::FETCH_ASSOC)) {
            $sListDisplay .= "<div class=\"song-wrapper\"><div class=\"song-info\"><strong>Song Title:</strong> " . $file['song_title'] . "<br />";
            $sListDisplay .= "<strong>Artist:</strong> " . $file['artist'] . "<br />";
            $sListDisplay .= "<strong>Album:</strong> " . $file['album'] . "<br />";
            $sListDisplay .= "<strong>Uploaded On:</strong> " . $file['upload_date'] . "<br /></div>";
            $sListDisplay .= "<audio controls><source src=\"" . "../uploads/" . self::getUserDir($uid) . $file['filename'] . "\" type=\"audio/mp3\">Your browser does not support the audio element.</audio>";
            

            $link = (User::userCanEdit($file['owner'])) ? '<a class="delete-file" data-file-id="' . $file['fid'] . '" data-file-name="' . $file['filename'] . '" href="#" />Delete</a>' : '';
            
            $sListDisplay .= $link;
            $sListDisplay .= "</div>";
        }

        // Return the list
        return $sListDisplay;

    }

    /**
     * Returns a list of images uploaded by the given user
     * @param $uid The user that uploaded the files
     * @param $con The database connection PDO Object
     */
    public static function getImages($uid, $con) {
        
        try {
            $getFile = $con->prepare("SELECT * FROM files WHERE owner = :uid AND mime_type LIKE 'image/%' ORDER BY upload_date DESC LIMIT 5");
            $getFile->bindParam(':uid', $uid);
            $getFile->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        $iListDisplay = "";

        // Build our list of images
        while ($file = $getFile->fetch(PDO::FETCH_ASSOC)) {
            $iListDisplay .= "<div class=\"image-wrapper\"><div class=\"image-info\"><strong>Image Title:</strong> " . $file['image_title'] . "<br />";
            $iListDisplay .= "<strong>Uploaded On:</strong> " . $file['upload_date'] . "<br /></div>";
            $iListDisplay .= "<img src=\"" . "../uploads/" . self::getUserDir($uid) . $file['filename'] . "\" width=\"100%\"/>";
            
            $link = (User::userCanEdit($file['owner'])) ? '<a class="delete-file" data-file-id="' . $file['fid'] . '" data-file-name="' . $file['filename'] . '" href="#" />Delete</a>' : '';
        
            $iListDisplay .= $link;
            $iListDisplay .= "</div>";

        }

        // Return the list
        return $iListDisplay;
    }

    /**
     * Deletes a single file. Can be an image or audio file.
     * @param $path The directory that the file is located in
     * @param $fName The file name that should be deleted
     */
    public static function deleteFile($path, $fName) {
        $tmpDir = self::getUserDir();
        
        // Make sure the the file exists before we try to unlink
        if (file_exists($path . $tmpDir . $fName) && !is_dir($path . $tmpDir . $fName)) {
            unlink($path . $tmpDir . $fName);
        }

        // Check if the directory is now empty, if so, delete the directory
        if (self::is_dir_empty($path)) {
            rmdir($path . self::getUserDir());
        }
    }

    /**
     * A helper method to check if a directory is empty
     * @param $path The path to the directory to check
     * @return Returns true if the directory is empty. Otherwise, false.
     */
    public static function is_dir_empty($path) {
        $theDir = $path . self::getUserDir();
        if (!is_readable($theDir)) return NULL;
        $handle = opendir($theDir);
        while (false !== ($entry = readdir($handle))) {
          if ($entry != "." && $entry != "..") {
            return FALSE;
          }
        }
        return TRUE;
      }

}
?>