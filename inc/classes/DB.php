<?php


 // If there is no constant defined called __CONFIG__ do not load this file
 if(!defined('__CONFIG__')) {
    header('Location: ' . __PATH__);
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
            
               header("Location: " . __PATH__ . "inc/dbsetup.php");
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
            profile_image   VARCHAR (200) COMMENT "The users profile image",
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
            // Catch the error and display a message to the user.

            echo "<div class=\"alert alert-dismissible alert-warning\">
                <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
                <h3>The users table already exists in the database!</h3>" . $e->getMessage() . "</div>";
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
            // Catch the error and display a message to the user
            echo "<div class=\"alert alert-dismissible alert-warning\">
            <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
                <h3>The files table already exists in the database!</h3>" . $e->getMessage() . "</div>";
        }

        $postTable = 'CREATE TABLE posts (
            pid INT (5) UNSIGNED AUTO_INCREMENT COMMENT "The id of the post" NOT NULL,
            post_title VARCHAR (200) COMMENT "The title of the post" NOT NULL,
            post_content LONGTEXT COMMENT "The content of the post",
            author INT (5) UNSIGNED COMMENT "The person that wrote the post" NOT NULL,
            created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT "The date and time the post was created" NOT NULL,
            last_update TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT "The date and time the post was last edited" NOT NULL,
            PRIMARY KEY (pid),
            INDEX (post_title),
            INDEX (author),
            CONSTRAINT posts FOREIGN KEY (author)
            REFERENCES users(uid)
            ON DELETE CASCADE
            ON UPDATE CASCADE
        )
        ENGINE INNODB;';

        try {
            $ex = $pdo->prepare($postTable);
            $ex->execute();
        } catch (PDOException $e) {
            // Catch the error and display a message to the user
            die("<div class=\"alert alert-dismissible alert-warning\">
                <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
                <h3>The posts table already exists in the database!</h3>" . $e->getMessage() . "</div>");
        }
        
        // If we made it this far, we know the database is connected and the tables have been setup
        echo "<div class=\"alert alert-dismissible alert-success\">
            <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
            <h3>Your database has been set up!</h3>
            <a href=\"../register/\">Create Your Account</a>
        </div>";
    
    }

    /**
     * Updates the user's account information.
     * @param String $profile_image the user's profile image file name.
     * @param String $first_name The user's first name.
     * @param String $last_name The user's last name.
     * @param String $newEmail The user's new email address to replace the $oldEmail (if updating email address).
     * @param String $oldEmail The user's current email address before updating. Used to find the user in database query.
     * @param String $password The user's password.
     * @param Date $birthDate The user's birthdate. In date format 00-00-0000.
     * @param String $bio The user's bio section
     */
    public static function updateUser($profile_image, $first_name, $last_name, $newEmail, $oldEmail, $password, $birthDate, $bio) {

        if ($profile_image !== null && is_array($profile_image)) {
            self::updateProfilePic($profile_image, $oldEmail);
        }

        $con = DB::getConnection();
            try {
            $addUser = $con->prepare("UPDATE users SET first_name = :firstname, last_name = :lastname, email = LOWER(:email), password = :password, birthdate = :birthdate, bio = :bio WHERE email = \"$oldEmail\"");
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

    /**
     * A helper method to update the user's profile picture. Called from the updateUser method if the profile image field is populated.
     * @param String $profile_image A string representation of the user's profile image file name.
     * @param String @oldEmail The user's current email address. Not the email address we are updating to if editing the user's profile.
     */
    public function updateProfilePic($profile_image, $oldEmail) {
        $file = new FileHandler($profile_image);

        move_uploaded_file($profile_image['tmp_name'], $file->filePath);

        $con = DB::getConnection();
       
        try {
            $addUser = $con->prepare("UPDATE users SET profile_image = :profile_image WHERE email = \"$oldEmail\"");
            $addUser->bindParam(':profile_image', $profile_image['name'], PDO::PARAM_STR);
            $addUser->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }


    /**
     * A helper method to query the database and get a list of blogs. Will display an edit link if user can edit.
     * @param Int $uid If a user id is passed this method will echo all blogs created by the given user. Defaults to all posts on the site. Optional.
     * @param Int $exLength Number of characters to shorten the post content to. Defaults to full content. Optional.
     */
    public static function getBlogRoll($uid = null, $exLength = 0) {
        if ($uid == null) {
            $query = self::$con->prepare("SELECT * FROM posts ORDER BY created_date DESC");
            $query->execute();
        } else {
            $query = self::$con->prepare("SELECT * FROM posts WHERE author = $uid ORDER BY created_date DESC");
            $query->execute();
        }

        while ($roll_display = $query->fetch(PDO::FETCH_ASSOC)) {
            $excerpt = $exLength;
            $conLength = strlen($roll_display['post_content']);
            
            if ($excerpt != 0 && $conLength > $excerpt) {
                $roll_display['post_content'] = substr($roll_display['post_content'], 0, $excerpt);
                $roll_display['post_content'] .= "...";
            }

            $pid = $roll_display['pid'];
            $pTitle = $roll_display['post_title'];

            $q = "?pid=" . $pid . "&title=" . $pTitle;

            $link = (User::userCanEdit($roll_display['author'])) ? '<a href="' . __PATH__ . 'post-edit/?pid=' . $pid . '&title=' . $pTitle . '" />Edit Post</a>' : '';

            echo '<div class="excerpt"><div class="blog-title"><a href="' . __PATH__ . 'blog/' . $q . '"><h2>' . $roll_display['post_title'] . '</h2></a></div><p>' . nl2br($roll_display['post_content']) . '</p><div class="post-date"><small> Posted on: ' . date('D, M, d, Y', strtotime($roll_display['created_date'])) . '</small></div>' . $link . '</div>';
        }
    }
}
?>