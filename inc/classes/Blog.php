<?php

 // If there is no constant defined called __CONFIG__ do not load this file
 if(!defined('__CONFIG__')) {
    header('Location: ' . __PATH__);
    exit;

}

class Blog {

    public $title;
    public $content;
    public $author_id;


    private static $con;

    public function __construct($title, $content = null, $author_id) {
        $this->title = $title;
        $this->content = $content;
        $this->author_id = $author_id;

    }

    public function insertPost($con) {
        try {
            $postCreate = $con->prepare("INSERT INTO posts (post_title, post_content, author) VALUES(:post_title, :post_content, :author_id)");
            $postCreate->bindParam(":post_title", $this->title, PDO::PARAM_STR);
            $postCreate->bindParam(":post_content", $this->content, PDO::PARAM_STR);
            $postCreate->bindParam(":author_id", $this->author_id, PDO::PARAM_INT);
            $postCreate->execute();

        } catch (PDOExeception $e) {
            echo $e->getMessage();
        }
    }

    public function updatePost($con) {
        try {
            $postUpdate = $con->prepare("UPDATE posts SET post_title = :post_title, post_content = :post_content WHERE post_title = :post_title AND author = :author_id");
            $postUpdate->bindParam(":post_title", $this->title, PDO::PARAM_STR);
            $postUpdate->bindParam(":post_content", $this->content, PDO::PARAM_STR);
            $postUpdate->bindParam(":author_id", $this->author_id, PDO::PARAM_INT);
            $postUpdate->execute();

        } catch (PDOExeception $e) {
            echo $e->getMessage();
        }

    }

    public static function getSinglePost($pid, $con) {
        $pid = $pid;

        $postView = $con->prepare("SELECT * FROM posts WHERE pid = $pid");
        $postView->execute();
        $pView = $postView->fetch(PDO::FETCH_ASSOC);

        return $pView;
    }

    public static function getPostAuthor($auth_id, $con) {
      $author = $con->prepare("SELECT * FROM users WHERE uid = :author_id LIMIT 1");
      $author->bindParam(":author_id", $auth_id, PDO::PARAM_INT);
      $author->execute();

      $authorInfo = $author->fetch(PDO::FETCH_ASSOC);

      return $authorInfo;
    }


   

}
?>