<?php

 // If there is no constant defined called __CONFIG__ do not load this file
 if(!defined('__CONFIG__')) {
    header('Location: ../index.php');
    exit;

}

class User {

    private $con;

    public $profileImage;
    public $first_name;
    public $last_name;
    public $user_id;
    public $email;
    public $birthDate;
    public $bio;
    public $reg_time;
    
    public function __construct(int $user_id) {
        $this->con = DB::getConnection();

        $user_id = Filter::Int($user_id);
        try {
        $user = $this->con->prepare("SELECT uid, profile_image, first_name, last_name, email, password, birthdate, bio, reg_time FROM users WHERE uid = :user_id LIMIT 1");
        $user->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $user->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        if ($user->rowCount() == 1) {
            $user = $user->fetch(PDO::FETCH_OBJ);

            $this->email        = (string) $user->email;
            $this->user_id      = (int) $user->uid;
            $this->reg_time     = (string) $user->reg_time;
            $this->first_name   = (string) $user->first_name;
            $this->last_name    = (string) $user->last_name;
            $this->profileImage    = (string) $user->profile_image;
            $this->birthDate    = (string) $user->birthdate;
            $this->bio    = (string) $user->bio;

        } else {
            // No user.
            // Redirect to logout
            header("Location: logout.php");
        }
    }

    /**
     * @param string $email     Required. The email address of the user to find in the database.
     * @param bool $return_assoc Optional. If true, returns an associative array.
     * @return                  Returns an associative array of the user that was found if $return_assoc is true. Otherwise, returns a true if the user was found and false if not.
     */
    public static function Find($email, $return_assoc  = false){
       
        $con = DB::getConnection();

        $email = (string) Filter::String($email);
        
    
        $findUser = $con->prepare("SELECT uid, profile_image, first_name, last_name, email, password, birthdate, bio FROM users WHERE email = LOWER(:email) LIMIT 1");
        $findUser->bindParam(':email', $email, PDO::PARAM_STR);
        $findUser->execute();
    
        if ($return_assoc) {
            return $findUser->fetch(PDO::FETCH_ASSOC);
        }

        $user_found = (boolean) $findUser->rowCount();
    
        return $user_found;
    }

    public static function getCurrentUser() {
        $user = new User($_SESSION['user_id']);

        return array(
            'email' => $user->email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'profile_img' => $user->profileImage,
            'birthdate' => $user->birthDate,
            'bio' => $user->bio
        );
    }
}
?>