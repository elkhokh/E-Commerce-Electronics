<?php

use App\Blogs;
use App\Offers;
use App\Product;
use App\User;
use App\Massage;


$isLoggedIn = isset($_SESSION['user']);
?>
    <!--slider area start-->
    <section class="slider_section d-flex align-items-center" data-bgimg="assets/img/slider/slider3.jpg">
        <div class="slider_area owl-carousel">
            <?php
            foreach (Offers::getAll($db) as $offer) :
                // var_dump($offer->getDiscountPercentage());
                // exit;
            ?>
            <div class="single_slider d-flex align-items-center">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="slider_content">
                                <h1><?=$offer->getTitle()?></h1>
                                <h2><?=$offer->getDescription()?></h2>
                                <p>Special offer <span> <?=(int)$offer->getDiscountPercentage()?>% off </span> this week</p>
                                <a class="button" href="index.php?page=product_details&id=<?=$offer->getProductId()?>">Buy now</a>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6">
                            <div class="slider_content">
                                <img src="<?=$offer->getProduct($db)->getMainImage()?>" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach ; ?>
        </div>
    </section>
    <!--slider area end-->

    <!--Tranding product-->
    <section class="pt-60 pb-30 gray-bg">
        <div class="container">
            <div class="row">
                <div class="col text-center">
                    <div class="section-title">
                        <h2>Tranding Products</h2>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <?php
                foreach (Product::getRandomProducts($db) as $product) :
                ?>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                    <div class="single-tranding">
                        <a href="index.php?page=product_details&id=<?= $product->getId() ?>">
                            <div class="tranding-pro-img">
                                <img src="<?= $product->getMainImage() ?>" alt="<?= $product->getName() ?>">
                            </div>
                            <div class="tranding-pro-info">
                                <div class="tranding-pro-title">
                                    <h3><?= $product->getName() ?></h3>
                                    <?php 
                                    $subcategory = $product->getSubcategory($db);
                                    if ($subcategory): 
                                    ?>
                                        <h4><?= $subcategory['name'] ?></h4>
                                    <?php endif; ?>
                                </div>
                                <div class="tranding-pro-price">
                                    <div class="price_box">
                                        <span class="current_price">$<?= $product->getFinalPrice($db) ?></span>
                                        <?php if ($product->getDiscount($db) > 0): ?>
                                            <span class="old_price">$<?= $product->getPrice() ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </a>
                        
                        <div class="product-colors">
                            <?php foreach ($product->getColors($db) as $color): ?>
                                <span class="color-option" 
                                      style="background-color: <?= $color['code'] ?>" 
                                      title="<?= $color['name'] ?>">
                                </span>
                            <?php endforeach; ?>
                        </div>
                        
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section><!--Tranding product-->

    <!--Features area-->
    <section class="gray-bg pt-60 pb-60">
        <div class="container">
            <div class="row">
                <!-- First Feature -->

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 order-lg-3 order-md-4 order-sm-4 order-4">
                    <div class="pro-details-feature">
                        <h3>Fast Delivery</h3>
                        <p>Experience lightning-fast delivery with our efficient shipping network. We ensure your products reach you in perfect condition and within the promised timeframe.</p>
                        <ul>
                            <li>Express Shipping Available</li>
                            <li>Real-time Tracking</li>
                            <li>Worldwide Delivery</li>
                            <li>Free Shipping on Orders Over $100</li>
                            <li>Same Day Processing</li>
                            <li>Delivery Status Updates</li>
                        </ul>
                        <a href="index.php?page=tracking" class="btn btn-primary">Shipping Info</a>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 order-lg-4 order-md-3 order-sm-3 order-3">
                    <div class="pro-details-feature">
                        <img src="Public\assets\front\img\Fast Delivery.jpg" alt="Fast Delivery">
                    </div>
                </div>

                <!-- Second Feature -->
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 order-lg-1 order-md-1 order-sm-1">
                    <div class="pro-details-feature">
                        <img src="Public\assets\front\img\customer-support.jpg" alt="24/7 Support">
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 order-lg-2 order-md-2 order-sm-2">
                    <div class="pro-details-feature">
                        <h3>24/7 Customer Support</h3>
                        <p>Our dedicated support team is available round the clock to assist you with any questions or concerns. We're committed to providing exceptional customer service.</p>
                        <ul>
                            <li>Live Chat Support</li>
                            <li>Email Support</li>
                            <li>Phone Support</li>
                            <li>Technical Assistance</li>
                            <li>Product Guidance</li>
                            <li>Order Support</li>
                        </ul>
                        <a href="index.php?page=contact_us" class="btn btn-primary">Contact Us</a>
                    </div>
                </div>
            </div>
        </div>
    </section><!--Features area-->

    
    <!--product details start-->
<?php
if ($isLoggedIn) {
    $allProducts = Product::getAll($db);
    if (!empty($allProducts)) {
        $randomProduct = $allProducts[array_rand($allProducts)];
        $_GET['id'] = $randomProduct->getId();
        require_once 'product/product_details.php';
    }
}
?>
    <!--product details end-->

    
    <!--area-->
    <section class="pt-60 pb-60 gray-bg">
        <div class="container">
            <div class="row">
                <div class="col text-center">
                    <div class="section-title">
                        <h2>Watch it now</h2>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12">
                    <div style="padding:56.25% 0 0 0;position:relative;"><iframe src="https://player.vimeo.com/video/136938394?color=FA5252&amp;title=0&amp;byline=0" style="position:absolute;top:0;left:0;width:100%;height:100%;" allow="autoplay; fullscreen" allowfullscreen></iframe></div><script src="../../../player.vimeo.com/api/player.js"></script>
                </div>
            </div>
        </div>
    </section><!--area-->

    
    <!--Other product-->
    <section class="pt-60 pb-30">
        <div class="container">
            <div class="row">
                <div class="col text-center">
                    <div class="section-title">
                        <h2>Other collections</h2>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <?php
                if ($isLoggedIn) {
                    foreach (Product::getRandomProducts($db, 3) as $product) :
                    ?>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                        <div class="single-tranding">
                            <a href="index.php?page=product_details&id=<?= $product->getId() ?>">
                                <div class="tranding-pro-img">
                                    <img src="<?= $product->getMainImage() ?>" alt="<?= $product->getName() ?>">
                                </div>
                                <div class="tranding-pro-info">
                                    <div class="tranding-pro-title">
                                        <h3><?= $product->getName() ?></h3>
                                        <?php 
                                        $subcategory = $product->getSubcategory($db);
                                        if ($subcategory): 
                                        ?>
                                            <h4><?= $subcategory['name'] ?></h4>
                                        <?php endif; ?>
                                    </div>
                                    <div class="tranding-pro-price">
                                        <div class="price_box">
                                            <span class="current_price">$<?= $product->getFinalPrice($db) ?></span>
                                            <?php if ($product->getDiscount($db) > 0): ?>
                                                <span class="old_price">$<?= $product->getPrice() ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <?php 
                    endforeach;
                } else {
                    ?>
                    <div class="col-12 text-center">
                        <p>Please <a href="index.php?page=Login">login</a> to view more products.</p>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </section><!--Other product-->

    <!--Testimonials-->
    <section class="pb-60 pt-60 gray-bg">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    <div class="testimonial_are">
                        <div class="testimonial_active owl-carousel">       
                            <article class="single_testimonial">
                                <figure>
                                    <div class="testimonial_thumb">
                                        <a href="#"><img src="Public/assets/front/img/about/mostafa.png" alt="Sarah Johnson"></a>
                                    </div>
                                    <figcaption class="testimonial_content">
                                        <p>"As a PHP developer, I'm impressed by the quality of their e-commerce platform. The clean code structure and efficient database queries make it a pleasure to work with. Their technical support team is also very knowledgeable and responsive."</p>
                                        <h3><a href="#">Mustapha Khalid</a><span> -  Developer php</span></h3>
                                    </figcaption>
                                </figure>
                            </article>
                            <article class="single_testimonial">
                                <figure>
                                    <div class="testimonial_thumb">
                                        <a href="#"><img src="Public\assets\front\img\about\osama.jpg.png" alt="Michael Chen"></a>
                                    </div>
                                    <figcaption class="testimonial_content">
                                    <p>"As a PHP developer, I'm impressed by the quality of their e-commerce platform. The clean code structure and efficient database queries make it a pleasure to work with. Their technical support team is also very knowledgeable and responsive."</p>
                                        <h3><a href="#">Osama Elgendy</a><span> - Developer php</span></h3>
                                    </figcaption>
                                </figure>
                            </article>  
                        </div>   
                    </div>
                </div>
            </div>
        </div>
    </section><!--/Testimonials-->

    <!--Blog-->
    <section class="pt-60">
        <div class="container">
            <div class="row">
                <div class="col text-center">
                    <div class="section-title">
                        <h2>Blog Posts</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php
                foreach (Blogs::getRandomBlogs($db) as $blog) :
                ?>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
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
                                        <a href="#"><?= date('F j, Y', strtotime($blog->getCreatedAt())) ?></a>
                                    </span>
                                </div>
                                <div class="blog_desc">
                                    <p><?= substr($blog->getContent(), 0, 150) ?>...</p>
                                </div>
                                <footer class="readmore_button">
                                    <a href="index.php?page=blog_details&id=<?= $blog->getId() ?>">Read More</a>
                                </footer>
                            </figcaption>
                        </figure>
                    </article>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section><!--/Blog-->

    <!--shipping area start-->
    <section class="shipping_area">
        <div class="container">
            <div class=" row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-6">
                    <div class="single_shipping">
                        <div class="shipping_icone">
                            <img src="public/assets/front/img/about/shipping1.png" alt="">
                        </div>
                        <div class="shipping_content">
                            <h2>Free Shipping</h2>
                            <p>Free shipping on all US order</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-6">
                    <div class="single_shipping">
                        <div class="shipping_icone">
                            <img src="public/assets/front/img/about/shipping2.png" alt="">
                        </div>
                        <div class="shipping_content">
                            <h2>Support 24/7</h2>
                            <p>Contact us 24 hours a day</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-6">
                    <div class="single_shipping">
                        <div class="shipping_icone">
                            <img src="public/assets/front/img/about/shipping3.png" alt="">
                        </div>
                        <div class="shipping_content">
                            <h2>100% Money Back</h2>
                            <p>You have 30 days to Return</p>
                        </div>
                    </div>
                </div> 
                <div class="col-lg-3 col-md-6 col-sm-6 col-6">
                    <div class="single_shipping">
                        <div class="shipping_icone">
                            <img src="public/assets/front/img/about/shipping4.png" alt="">
                        </div>
                        <div class="shipping_content">
                            <h2>Payment Secure</h2>
                            <p>We ensure secure payment</p>
                        </div>
                    </div>
                </div>                          
            </div>
        </div>
    </section>
    <!--shipping area end-->
	

	