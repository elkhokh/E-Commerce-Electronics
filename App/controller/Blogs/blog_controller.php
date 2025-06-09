<?php
namespace App\controller\Blogs;

use App\Blogs;
use App\Massage;
use App\Traits\MangesFiles;
use PDO;

class Blog_controller {
    public static function handler(PDO $db) {
        if (!isset($_GET['action'])) {
            Massage::set_Massages("danger", "No action specified");
            header("Location: index.php");
            exit;
        }

        $action = $_GET['action'];

        switch ($action) {
            case 'create':
                self::create($db);
                break;
            case 'update':
                self::update($db);
                break;
            case 'delete':
                self::delete($db);
                break;
            default:
                Massage::set_Massages("danger", "Invalid action");
                header("Location: index.php?page=blogs");
                exit;
        }
    }

    public static function create(PDO $db) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Massage::set_Massages("danger", "Invalid request");
            header("Location: index.php?page=blogs");
            exit;
        }

        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        
        if (!isset($_FILES['main_image']) || $_FILES['main_image']['error'] !== UPLOAD_ERR_OK) {
            Massage::set_Massages("danger", "Please select an image");
            header("Location: index.php?page=create_blog");
            exit;
        }

        $main_image = $_FILES['main_image'];

        if (empty($title) || empty($content)) {
            Massage::set_Massages("danger", "Title and content are required");
            header("Location: index.php?page=create_blog");
            exit;
        }

        if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
            Massage::set_Massages("danger", "You must be logged in to create a blog");
            header("Location: index.php?page=login");
            exit;
        }

        $user_id = $_SESSION['user']['id'];
        $upload_dir = "Public/assets/front/img/blog";
        
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $uploaded_file = MangesFiles::UploadFile($main_image, ['jpg','png','jpeg'], $upload_dir);
        if(!$uploaded_file){
            Massage::set_Massages("danger", "Failed to upload image");
            header("Location: index.php?page=create_blog");
            exit;
        }

        try {
            if (Blogs::create($db, $user_id, $title, $content, $uploaded_file['path'])) {
                Massage::set_Massages("success", "Blog created successfully");
                header("Location: index.php?page=blogs");
                exit;
            } else {
                unlink($uploaded_file['path']);
                Massage::set_Massages("danger", "Failed to create blog");
                header("Location: index.php?page=create_blog");
                exit;
            }
        } catch (\Exception $e) {
            if (file_exists($uploaded_file['path'])) {
                unlink($uploaded_file['path']);
            }
            Massage::set_Massages("danger", "An error occurred: " . $e->getMessage());
            header("Location: index.php?page=create_blog");
            exit;
        }
    }

    public static function update(PDO $db) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Massage::set_Massages("danger", "Invalid request");
            header("Location: index.php?page=blogs");
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');

        if ($id <= 0 || empty($title) || empty($content)) {
            Massage::set_Massages("danger", "ID, title and content are required");
            header("Location: index.php?page=edit_blog&id=" . $id);
            exit;
        }

        try {
            $blog = Blogs::findById($db, $id);
            if (!$blog) {
                Massage::set_Massages("danger", "Blog not found");
                header("Location: index.php?page=blogs");
                exit;
            }

            $blog->setTitle($title);
            $blog->setContent($content);

            if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = "Public/assets/front/img/blog";
                
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $uploaded_file = MangesFiles::UploadFile($_FILES['main_image'], ['jpg','png','jpeg'], $upload_dir);
                if(!$uploaded_file){
                    Massage::set_Massages("danger", "Failed to upload image");
                    header("Location: index.php?page=edit_blog&id=" . $id);
                    exit;
                }

                if (file_exists($blog->getImage())) {
                    unlink($blog->getImage());
                }
                $blog->setImage($uploaded_file['path']);
            }

            if ($blog->update($db)) {
                Massage::set_Massages("success", "Blog updated successfully");
                header("Location: index.php?page=blogs");
                exit;
            } else {
                Massage::set_Massages("danger", "Failed to update blog");
                header("Location: index.php?page=edit_blog&id=" . $id);
                exit;
            }
        } catch (\Exception $e) {
            Massage::set_Massages("danger", "An error occurred: " . $e->getMessage());
            header("Location: index.php?page=edit_blog&id=" . $id);
            exit;
        }
    }

    public static function delete(PDO $db) {
        if (!isset($_GET['id'])) {
            Massage::set_Massages("danger", "Blog ID not specified");
            header("Location: index.php?page=blogs");
            exit;
        }

        $id = (int)$_GET['id'];
        
        try {
            $blog = Blogs::findById($db, $id);
            if (!$blog) {
                Massage::set_Massages("danger", "Blog not found");
                header("Location: index.php?page=blogs");
                exit;
            }

            if (file_exists($blog->getImage())) {
                unlink($blog->getImage());
            }

            if ($blog->delete($db)) {
                Massage::set_Massages("success", "Blog deleted successfully");
            } else {
                Massage::set_Massages("danger", "Failed to delete blog");
            }
        } catch (\Exception $e) {
            Massage::set_Massages("danger", "An error occurred: " . $e->getMessage());
        }

        header("Location: index.php?page=blogs");
        exit;
    }
}

Blog_controller::handler($db);