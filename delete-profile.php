<?php

    // Allow the config
    define('__CONFIG__', true);
    // Require the config file
    require_once "inc/config.php";
    
    if ($_SERVER["REQUEST_METHOD"] == 'POST') {

        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != null) {
            $uid = $_SESSION['user_id'];
            $path = dirname(__FILE__) . "/uploads/";
            
            try {
                $q = $con->prepare("SELECT * FROM files WHERE owner = :uid");
                $q->bindParam(":uid", $uid, PDO::PARAM_INT);
                $q->execute();

                while ($file = $q->fetch(PDO::FETCH_ASSOC)) {
                    FileHandler::deleteFile($path, $file['filename']);
                }
                
                $qUser = $con->prepare("SELECT * FROM users WHERE uid = :uid LIMIT 1");
                $qUser->bindParam(":uid", $uid, PDO::PARAM_INT);
                $qUser->execute();

                while ($user = $qUser->fetch(PDO::FETCH_ASSOC)) {
                    FileHandler::deleteFile($path, $user["profile_image"]);
                }

            } catch (PDOException $e) {
                echo $e->getMessage();
            }
            
            try {
                $user = $con->prepare("DELETE FROM users WHERE uid = :uid");
                $user->bindParam(":uid", $uid, PDO::PARAM_INT);
                $user->execute();

                session_destroy();
                session_write_close();
                setcookie(session_name() . '' . 0 . '/');
                

            } catch (PDOException $e) {
                echo $e->getMessage();
            }
            $_GET['message'] = str_replace(' ', '%20', "Your account has been deleted.");
    
            $query = http_build_query($_GET);
        
            $location = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/' . basename(dirname(__FILE__)) . '/' . "?" . $query;
            header("Location: " . $location);
        }
    }
    