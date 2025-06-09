<?php

use App\Blogs;
use App\User;
use App\Comment_replies;;
?>
	<!--blog body area start-->
    <div class="blog_details mt-60">
        <div class="container">
            <div class="row">
                
                <div class="col-lg-9 col-md-12">
                    <!--blog grid area start-->
                    <div class="blog_wrapper">
                        <?php
                        if (isset($_GET['id'])) {
                            $blog = Blogs::findById($db, $_GET['id']);
                            if ($blog) {
                        ?>
                        <article class="single_blog">
                            <figure>
                               <div class="post_header">
                                   <h3 class="post_title"><?= $blog->getTitle() ?></h3>
                                    <div class="blog_meta">                                        
                                        <span class="author">Posted by : <a href="">admin</a> / </span>
                                        <span class="post_date"><a href=""><?= date('F j, Y', strtotime($blog->getCreatedAt())) ?></a></span>
                                    </div>
                                </div>
                                <div class="blog_thumb">
                                   <a href="#"><img src="<?= $blog->getImage() ?>" alt=""></a>
                               </div>
                               <figcaption class="blog_content">
                                    <div class="post_content">
                                        <p><?= $blog->getContent() ?></p>
                                    </div>
                               </figcaption>
                            </figure>
                        </article>
                        <div class="comments_box">
                            <h3><?= $blog->getCommentCount($db) ?> Comments</h3>
                            <?php foreach ($blog->getComments($db) as $comment) :
                                $profile_image = User::get_profile_image($db, $comment['user_id']);
                            ?>
                            <div class="reviews_comment_box">
                                <div class="comment_thumb">
                                    <img src="<?=$profile_image?>" alt="" class="rounded-circle">
                                </div>
                                <div class="comment_text">
                                    <div class="reviews_meta">
                                        <div class="comment_meta">
                                            <h5><a href="#"><?= User::find_by_id($db,$comment['user_id'])->get_name() ?></a></h5>
                                            <span><?= date('F j, Y', strtotime($comment['created_at']))?></span> 
                                        </div>
                                        <p><?= $comment['comment'] ?></p>
                                        
                                        <div class="comment_actions">
                                            <form action="index.php?page=Comment_controller&action=remove" method="post" style="display: inline;">
                                                <input type="hidden" name="blog_id" value="<?= $blog->getId() ?>">
                                                <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fa fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                      
                                        <div class="comment_reply_section">
                                            <div class="reply_button">
                                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#replyForm<?= $comment['id'] ?>" aria-expanded="false">
                                                    <i class="fa fa-reply"></i> Reply
                                                </button>
                                            </div>
                                            <div class="collapse reply_form" id="replyForm<?= $comment['id'] ?>">
                                                <div class="card card-body">
                                                    <form action="index.php?page=Reply_controller&action=add" method="post">
                                                        <input type="hidden" name="blog_id" value="<?= $blog->getId() ?>">
                                                        <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                                                        <div class="form-group">
                                                            <label for="reply_comment<?= $comment['id'] ?>">Your Reply</label>
                                                            <textarea class="form-control" name="comment" id="reply_comment<?= $comment['id'] ?>" rows="3" required></textarea>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">Submit Reply</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                                foreach (Comment_replies::getRepliesForComment($db, $comment['id']) as $reply): 
                                    $reply_user = User::find_by_id($db, $reply->getUserId());
                                    $reply_profile_image = User::get_profile_image($db, $reply->getUserId());
                            ?>
                            <div class="comment_list list_two">
                                <div class="reviews_comment_box">
                                    <div class="comment_thumb">
                                        <img src="<?= $reply_profile_image ?>" alt="" class="rounded-circle">
                                    </div>
                                    <div class="comment_text">
                                        <div class="reviews_meta">
                                            <div class="comment_meta">
                                                <h5><a href="#"><?= $reply_user->get_name() ?></a></h5>
                                                <span><?= date('F j, Y', strtotime($reply->getCreatedAt())) ?></span> 
                                            </div>
                                            <p><?= $reply->getReply() ?></p>
                                        
                                        <div class="comment_actions">
                                            <form action="index.php?page=Reply_controller&action=remove" method="post" style="display: inline;">
                                                <input type="hidden" name="blog_id" value="<?= $blog->getId() ?>">
                                                <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                                                <input type="hidden" name="reply_id" value="<?= $reply->getId() ?>">
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fa fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <?php endforeach; ?>
                        </div>
                        <?php
                            } else {
                                echo '<div class="alert alert-danger">Blog not found!</div>';
                            }
                        } 
                        ?>
                         
                    </div>
                    <!--blog grid area start-->
                </div>
            </div>
        </div>
    </div>
    <!--blog section area end-->

<style>

.comment_thumb img {
    width: 45px; 
    height: 45px; 
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #fff; 
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}


.comment_list.list_two .comment_thumb img {
    width: 35px;  
    height: 35px; 
    border: 1px solid #fff;
}


.reviews_comment_box {
    gap: 15px; 
}

.comment_list.list_two {
    margin-left: 60px; 
}


@media (max-width: 768px) {
    .comment_thumb img {
        width: 40px;
        height: 40px;
    }
    
    .comment_list.list_two .comment_thumb img {
        width: 30px;
        height: 30px;
    }
    
    .comment_list.list_two {
        margin-left: 40px;
    }
}
</style>

