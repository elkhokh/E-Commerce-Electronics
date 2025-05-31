<?php

namespace App\controller\Blogs;

use App\Blogs;
use App\Massage;
use PDO;

class Comment_controller {


    public static function handler(PDO $db)
    {
        if (!isset($_SESSION['user'])) {
            Massage::set_Massages("danger","Please login to add a comment" );
            header("Location: index.php?page=Login");
            exit;
        }
        if (!$_GET['action']) {
            Massage::set_Massages("danger","NO action" );
            header("Location: index.php?page=home");
            exit;
        }


        $user_id =(int) $_SESSION['user']['id'];
        $action = $_GET['action'] ?? '';

        switch ($action) {
            case 'add':
                self::addComment($db,$user_id);
               break;
            case 'remove':
                self::deleteComment($db,$user_id);
                break;
       }
    }

    static public function addComment($db,$user_id): void {


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $blog_id = htmlspecialchars(trim($_POST['blog_id']));
            $comment = htmlspecialchars(trim($_POST['comment']));

            if (!$blog_id || empty($comment)) {
                Massage::set_Massages("danger","Invalid comment data" );
                header("Location: index.php?page=blog_details&id=" . $blog_id);
                exit;
            }

            if (Blogs::addComment($db, $blog_id, $user_id, $comment)) {
                Massage::set_Massages("success","Comment added successfully" );
            } else {
                Massage::set_Massages("danger","Failed to add comment" );
            }
        }

        header("Location: index.php?page=blog_details&id=" . $blog_id);
        exit;
    }


   static public function deleteComment($db,$user_id): void {


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $blog_id = htmlspecialchars(trim($_POST['blog_id']));
            $comment_id = htmlspecialchars(trim($_POST['comment_id']));
            

            if (!$comment_id || !$blog_id) {
                Massage::set_Massages("danger","Invalid comment data" );
                header("Location: index.php?page=blog_details&id=" . $blog_id);
                exit;
            }

            if (Blogs::deleteComment($db, $comment_id, $user_id)) {
                Massage::set_Massages("success","Comment deleted successfully" );
            } else {
                Massage::set_Massages("danger","Failed to delete comment" );
            }
        }

        header("Location: index.php?page=blog_details&id=" . $blog_id);
        exit;
    }

   
}
// Execute controller
Comment_controller::handler($db);