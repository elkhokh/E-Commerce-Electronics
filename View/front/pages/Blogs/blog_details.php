<?php

use App\Blogs;
use App\User;
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
                                        <span class="author">Posted by : <a href="#">Rahul</a> / </span>
                                        <span class="post_date"><a href="#">Sep 20, 2019</a></span>
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
                            <h3><?= $blog->getCommentCount($db) ?> Comments	</h3>
                            <?php
                            foreach ($blog->getComments($db) as  $comment) :
                                // var_dump($comment['id']);
                            ?>
                            <div class="comment_list">
                                <div class="comment_thumb">
                                    <img src="assets/img/blog/comment3.png.jpg" alt="">
                                </div>
                                <div class="comment_content">
                                    <div class="comment_meta">
                                        <h5><a href="#"><?= User::find_by_id($db,$comment['user_id'])->get_name() ?></a></h5>
                                        <span><?= date('F j, Y', strtotime($comment['created_at']))?></span> 
                                    </div>
                                    <p><?= $comment['comment'] ?></p>
                                    <div class="comment_reply">
                                    <?php if ($user_id==$comment['user_id']) :
                                     ?>
                                       <form action="index.php?page=Comment_controller&action=remove" method="post">
                                        <input type="hidden" value="<?= $blog->getId() ?>" name="blog_id">
                                        <input type="hidden" value="<?= $comment['id']?>" name="comment_id">
                                       <button class="button" type="submit" style="background-color: #000; color: #fff;">Delete</button>
                                      </form>  
                                    <?php endif; ?>
                                    </div>
                                </div>

                            </div>
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
                            <form action="index.php" method="GET">
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