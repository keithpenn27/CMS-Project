<?php

 // If there is no constant defined called __CONFIG__ do not load this file
 if(!defined('__CONFIG__')) {
    header('Location: ../index.php');
    exit;

}

class DB {
    protected static $con;

    private function __construct($host, $port, $dbName, $dbUsername, $dbPass) {
        try {
            self::$con = new PDO('mysql:charset=utf8mb4;host=' . $host . ';port=' . $port . ';dbname=' . $dbName, $dbUsername, $dbPass);
            self::$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$con->setAttribute(PDO::ATTR_PERSISTENT, false);

        } catch (PDOException $e) {
            // We are not connected and we need to set up the database.
            // We need to make sure that we are not currently on the dbsetup.php page so we don't create an infinite redirect
            $uri = explode("/", $_SERVER['REQUEST_URI']);

            $uri= end($uri);

           // If not on the dbsetup.php page, redirect to it.
            if ($uri != "dbsetup.php" && $uri != "dbsubmission.php") {
                header("Location: " . Url::getBasePath() . "inc/dbsetup.php");
            }
        }

    }

    public static function getConnection() {
        return self::$con;
    }

    /**
     * Sets up the initial DB connection if one does not exist.
     * @param string $host The database host i.e. localhost
     * @param string $port The port that the database host is listening on.
     * @param string $dbName The name of the database.
     * @param string $dbUsername The username that has full access to the database.
     * @param string $dbPass The password for database user account.
     */
    public static function setConnection($host, $port, $dbName, $dbUsername, $dbPass) {
        
        // If this instance has not been started, start it.
        if (!self::$con) {
            new DB($host, $port, $dbName, $dbUsername, $dbPass);
        }
    }
    
    /**
     * Creates the necessary tables in the database after connection.
     * @param DB $pdo The connection PDO object that contains the database connection.
     */
    public static function createTables($pdo) {
        $userTable = 'CREATE TABLE users(
            uid   INT  (5) UNSIGNED AUTO_INCREMENT COMMENT "User Id" NOT NULL,
            first_name VARCHAR (50)  COMMENT "The users first name"   NOT NULL,
            last_name  VARCHAR (100)  COMMENT "The users last name"  NOT NULL,
            email  VARCHAR (250) COLLATE utf8_unicode_ci COMMENT "The users email" NOT NULL ,
            password   VARCHAR (200) COMMENT "The users password" NOT Null,
            profile_image   VARCHAR (200) COMMENT "The users profile image" NOT Null,
            birthdate DATE COMMENT "The users birthdate",
            bio LONGTEXT COMMENT "The users bio \from\ the profile",
            reg_time   TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT "The date and time the user registered" NOT NULL, 
            PRIMARY KEY (uid),
            UNIQUE KEY (email)
         )
         ENGINE = INNODB;';
        try {
            $ex = $pdo->prepare($userTable);
            $ex->execute();
        } catch (PDOException $e) {
            echo "<div class=\"container\">
            <div class=\"alert alert-dismissible alert-warning\">
                <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
                <h3>Your database has already been set up!</h3>
            </div>
        </div>" . $e->getMessage();
            return;
        }

        $fileTable = 'CREATE TABLE files (
            fid INT (5) UNSIGNED AUTO_INCREMENT COMMENT "The id of the uploaded file" NOT NULL,
            filename VARCHAR (200) COMMENT "The file name from the meta" NOT NULL,
            owner INT (5) UNSIGNED COMMENT "The person that uploaded the file" NOT NULL,
            artist VARCHAR (200) COMMENT "The artist that recorded the song",
            album VARCHAR (200) COMMENT "The album that a song belongs to",
            song_title VARCHAR (200) COMMENT "The title of the track",
            image_title VARCHAR (200) COMMENT "The title of the image",
            upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT "The date and time the file was uploaded" NOT NULL,
            last_update TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT "The date and time the file was last updated" NOT NULL,
            mime_type VARCHAR (100) COMMENT "The mime type of the file" NOT NULL,
            PRIMARY KEY (fid),
            INDEX (filename),
            INDEX (owner),
            CONSTRAINT files FOREIGN KEY (owner)
            REFERENCES users(uid)
            ON DELETE CASCADE
            ON UPDATE CASCADE
        )
        ENGINE INNODB;';

        try {
            $ex = $pdo->prepare($fileTable);
            $ex->execute();
        } catch (PDOException $e) {
            echo "<div class=\"container\">
            <div class=\"alert alert-dismissible alert-warning\">
                <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
                <h3>Your database has already been set up!</h3>
            </div>
        </div>" . $e-getMessage();
        }

        echo "<div class=\"container\">
        <div class=\"alert alert-dismissible alert-success\">
            <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
            <h3>Your database has been set up!</h3>
            <a href=\"../register.php\">Create Your Account</a>
        </div>
    </div>";
    
    }

    public static function updateUser($profile_image, $first_name, $last_name, $newEmail, $oldEmail, $password, $birthDate, $bio) {

        if ($profile_image !== null) {
            $file = new FileHandler($profile_image);

            move_uploaded_file($profile_image['tmp_name'], $file->filePath);
        }

        $con = DB::getConnection();
            try {
            $addUser = $con->prepare("UPDATE users SET profile_image = :profile_image, first_name = :firstname, last_name = :lastname, email = LOWER(:email), password = :password, birthdate = :birthdate, bio = :bio WHERE email = \"$oldEmail\"");
            $addUser->bindParam(':profile_image', $profile_image['name'], PDO::PARAM_STR);
            $addUser->bindParam(':firstname', $first_name, PDO::PARAM_STR);
            $addUser->bindParam(':lastname', $last_name, PDO::PARAM_STR);
            $addUser->bindParam(':email', $newEmail, PDO::PARAM_STR);
            $addUser->bindParam(':password', $password, PDO::PARAM_STR);
            $addUser->bindParam(':birthdate', $birthDate, PDO::PARAM_STR);
            $addUser->bindParam(':bio', $bio, PDO::PARAM_STR);
            $addUser->execute();
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
    }

    // TODO add query builder functions for users, files, and other content. One function to handle all if possible.

    // TODO add update db function when user edits profile
}
?>