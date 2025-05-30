<?php
use App\Blogs;
use App\User;
?>
    <!--breadcrumbs area start-->
    <div class="breadcrumbs_area">
        <div class="container">   
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb_content">
                        <ul>
                            <li><a href="index.php">home</a></li>
                            <li>All Blogs</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>         
    </div>
    <!--breadcrumbs area end-->
	
	
	<!--blog body area start-->
    <div class="blog_details mt-60">
        <div class="container">
            <div class="row">
                
                <div class="col-lg-9">
                    <div class="blog_wrapper">
                        <div class="section-title">
                            <h2>All Blogs</h2>
                        </div>
                        <div class="row">
                            <?php
                            $allBlogs = Blogs::getAll($db);
                            if (!empty($allBlogs)):
                                foreach ($allBlogs as $blog):
                            ?>
                            <div class="col-lg-6 col-md-6">
                                <article class="single_blog mb-60">
                                    <figure>
                                        <div class="blog_thumb">
                                            <a href="index.php?page=blog_details&id=<?= $blog->getId() ?>">
                                                <img src="<?= $blog->getImage() ?>" alt="<?= $blog->getTitle() ?>">
                                            </a>
                                        </div>
                                        <figcaption class="blog_content">
                                            <h3>
                                                <a href="index.php?page=blog_details&id=<?= $blog->getId() ?>">
                                                    <?= $blog->getTitle() ?>
                                                </a>
                                            </h3>
                                            <div class="blog_meta">                                        
                                                <span class="author">Posted by : 
                                                    <a href="#"><?= User::find_by_id($db, $blog->getUserId())->get_name() ?></a>
                                                </span>
                                                <span class="post_date">
                                                    On : <a href="#"><?= date('F j, Y', strtotime($blog->getCreatedAt())) ?></a>
                                                </span>
                                            </div>
                                            <div class="blog_desc">
                                                <p><?= $blog->getContent() ?>...</p>
                                            </div>
                                            <footer class="readmore_button">
                                                <a href="index.php?page=blog_details&id=<?= $blog->getId() ?>">Read more</a>
                                            </footer>
                                        </figcaption>
                                    </figure>
                                </article>
                            </div>
                            <?php
                                endforeach;
                            else:
                            ?>
                            <div class="col-12">
                                <div class="alert alert-info">No blogs found.</div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div> 
                <div class="col-lg-3 col-md-12">
                    <div class="blog_sidebar_widget">
                        <div class="widget_list widget_search">
                            <h3>Search</h3>
                            <form action="index.php" method="GET">
                                <input type="hidden" name="page" value="blogs">
                                <input placeholder="Search..." type="text" name="search">
                                <button type="submit">search</button>
                            </form>
                        </div>
                        <div class="widget_list widget_post">
                            <h3>Recent Posts</h3>
                            <?php
                            $recentBlogs = Blogs::getRandomBlogs($db, 3);
                            foreach ($recentBlogs as $recentBlog):
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
	
	    
    <!--blog pagination area start-->
    <div class="blog_pagination">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="pagination">
                        <ul>
                            <li class="current">1</li>
                            <li><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li class="next"><a href="#">next</a></li>
                            <li><a href="#"><i class="fa fa-angle-right"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--blog pagination area end-->