<?php

use App\Blogs;
use App\User;
use App\Comment_replies;
$user_id = (int)$_SESSION['user']['id'];
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
                                    <div class="entry_content">
                                        <div class="post_meta">
                                            <span>Tags: </span>
                                            <span><a href="">Drone, </a></span>
                                            <span><a href="">Sky, </a></span>
                                            <span><a href="">Fly</a></span>
                                        </div>

                                        <div class="social_sharing">
                                            <p>share this post:</p>
                                            <ul>
                                                <li><a href="#" title="facebook"><i class="fa fa-facebook"></i></a></li>
                                                <li><a href="#" title="twitter"><i class="fa fa-twitter"></i></a></li>
                                                <li><a href="#" title="pinterest"><i class="fa fa-pinterest"></i></a></li>
                                                <li><a href="#" title="google+"><i class="fa fa-google-plus"></i></a></li>
                                                <li><a href="#" title="linkedin"><i class="fa fa-linkedin"></i></a></li>
                                            </ul>
                                        </div>
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
                                        <?php if ($user_id == $comment['user_id']): ?>
                                        <div class="comment_actions">
                                            <form action="index.php?page=Comment_controller&action=remove" method="post" style="display: inline;">
                                                <input type="hidden" name="blog_id" value="<?= $blog->getId() ?>">
                                                <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fa fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                        <?php endif; ?>
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
                                        <?php if ($user_id ==$reply->getUserId()): ?>
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
                                        <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <?php endforeach; ?>
                        </div>
                        <div class="comments_form">
                            <h3>Leave a Comment </h3>
                            <form action="index.php?page=Comment_controller&action=add" method="post">
                                <div class="row">
                                    <div class="col-12">
                                        <label for="review_comment">Comment </label>
                                        <textarea name="comment" id="review_comment" ></textarea>
                                        <input type="hidden" value="<?= $blog->getId() ?>" name="blog_id">
                                    </div> 
                                </div>
                                <button class="button" type="submit">Post Comment</button>
                             </form>    
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
                <div class="col-lg-3 col-md-12">
                    <div class="blog_sidebar_widget">
                        <div class="widget_list widget_search">
                            <h3>Search</h3>
                            <form action="index.php?page=blog_search" method="Post">
                                <input type="hidden" name="page" value="blogs">
                                <input type="text" name="search" placeholder="Search...">
                                <button type="submit">search</button>
                            </form>
                        </div>
                        <div class="widget_list widget_post">
                            <h3>Recent Posts</h3>
                            <?php
                            $recentBlogs = Blogs::getLatest($db, 3);
                            foreach ($recentBlogs as $recentBlog) :
                            ?>
                            <div class="post_wrapper">
                                <div class="post_thumb">
                                    <a href="index.php?page=blog_details&id=<?= $recentBlog->getId() ?>">
                                        <img src="<?= $recentBlog->getImage() ?>" alt="<?= $recentBlog->getTitle() ?>">
                                    </a>
                                </div>
                                <div class="post_info">
                                    <h3>
                                        <a href="index.php?page=blog_details&id=<?= $recentBlog->getId() ?>">
                                            <?= $recentBlog->getTitle() ?>
                                        </a>
                                    </h3>
                                    <span><?= date('F j, Y', strtotime($recentBlog->getCreatedAt())) ?></span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="widget_list widget_tag">
                            <h3>Tag products</h3>
                            <div class="tag_widget">
                                <ul>
                                    <li><a href="#">Drone</a></li>
                                    <li><a href="#">Sky</a></li>
                                    <li><a href="#">Fly</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--blog section area end-->

