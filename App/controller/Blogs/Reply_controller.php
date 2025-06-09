<?php

namespace App\controller\Blogs;

use App\Blogs;
use App\Massage;
use App\Comment_replies;
use PDO;

class Reply_controller {


    public static function handler(PDO $db)
    {
        if (!isset($_SESSION['user'])) {
            Massage::set_Massages("danger","Please login to add a Reply" );
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
                self::addReply($db,$user_id);
               break;
            case 'remove':
                self::deleteReply($db,);
                break;
                default:
                Massage::set_Massages("danger", "Invalid action");
                header("Location: index.php?page=blogs");
                exit;
       }
    }

    static public function addReply($db,$user_id): void {


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $blog_id = htmlspecialchars(trim($_POST['blog_id']));
            $comment_id = htmlspecialchars(trim($_POST['comment_id']));
            $comment = htmlspecialchars(trim($_POST['comment']));

            if (!$blog_id || empty($comment ||!$comment_id)) {
                Massage::set_Massages("danger","Invalid comment data" );
                header("Location: index.php?page=blog_details&id=" . $blog_id);
                exit;
            }

            if (Comment_replies::create($db, $comment_id, $user_id, $comment)) {
                Massage::set_Massages("success","Reply added successfully" );
            } else {
                Massage::set_Massages("danger","Failed to add Reply" );
            }
        }

        header("Location: index.php?page=blog_details&id=" . $blog_id);
        exit;
    }


   static public function deleteReply($db): void {


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $blog_id = htmlspecialchars(trim($_POST['blog_id']));
            $comment_id = htmlspecialchars(trim($_POST['comment_id']));
            $reply_id = htmlspecialchars(trim($_POST['reply_id']));
            

            if (!$comment_id || !$blog_id) {
                Massage::set_Massages("danger","Invalid Reply data" );
                header("Location: index.php?page=blog_details&id=" . $blog_id);
                exit;
            }

            if (Comment_replies::delete($db, $reply_id)) {
                Massage::set_Massages("success","Reply deleted successfully" );
            } else {
                Massage::set_Massages("danger","Failed to delete Reply" );
            }
        }

        header("Location: index.php?page=blog_details&id=" . $blog_id);
        exit;
    }

   
}
// Execute controller
Reply_controller::handler($db);