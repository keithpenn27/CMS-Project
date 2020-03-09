<?php

// If there is no constant defined called __CONFIG__ do not load this file
 if(!defined('__CONFIG__')) {
    header('Location: ' . __PATH__);
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
    public static function Find($uid = null, $email = null, $return_assoc  = false){
       
        $con = DB::getConnection();

        $user_id = $uid;
        $email = $email;
    
        // If updating the user's email on edit page, we need to be able search by uid.
        if ($user_id != null) {
            // search by uid when editing the user
            $findUser = $con->prepare("SELECT * FROM users WHERE uid = :uid");
            $findUser->bindParam(':uid', $user_id, PDO::PARAM_INT);
            $findUser->execute();
        } else {
            // search by email when loggin in or registering user.
            $findUser = $con->prepare("SELECT * FROM users WHERE email = LOWER(:email)");
            $findUser->bindParam(':email', $email, PDO::PARAM_STR);
            $findUser->execute();
        }
    
        if ($return_assoc) {
            return $findUser->fetch(PDO::FETCH_ASSOC);
        }

        $user_found = (boolean) $findUser->rowCount();
    
        return $user_found;
    }

    public static function getCurrentUser() {
        $user = new User($_SESSION['user_id']);

        return array(
            'uid' => $user->user_id,
            'email' => $user->email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'profile_img' => $user->profileImage,
            'birthdate' => $user->birthDate,
            'bio' => $user->bio
        );
    }

    public static function getAge($uid) {

        date_default_timezone_set('America/Chicago');

        $con = DB::getConnection();
        $q = $con->prepare("SELECT birthdate FROM users WHERE uid = :uid LIMIT 1");
        $q->bindParam(':uid', $uid, PDO::PARAM_INT);
        $q->execute();

        $val = $q->fetch();

        $dob = strtotime($val['birthdate']);

        $date = strtotime(date('Y-m-d'));

        // Calculate the age
        $age = date('Y', $date)-date('Y', $dob);

        // Correct age for leap year
        return (date('md', date('U', $dob)) > date('md', date('U', $date))) 
        ? $age-1 : $age;
    }
}
?>