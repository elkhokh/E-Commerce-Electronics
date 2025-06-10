<!--product details start-->
<?php

use App\Product;
use App\Offers;
use App\User;
use App\Category;
use App\Review;

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $product = Product::findById($db, $id);
    // var_dump($product->getDiscount($db));
    // exit;
}

$offer = Offers::findById($db, $product->getId());


?>
<div class="product_details mt-60 mb-60">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="product-details-tab">
                    <div id="img-1" class="zoomWrapper single-zoom">
                        <a href="#">
                            <img id="zoom1" src="<?= $product->getMainImage() ?>"
                                data-zoom-image="<?= $product->getMainImage() ?>"
                                alt="<?= $product->getName() ?>">
                        </a>
                    </div>
                    <div class="single-zoom-thumb">
                        <ul class="s-tab-zoom owl-carousel single-product-active" id="gallery_01">
                            <?php
                            $images = $product->getImages($db);
                            if (!empty($images)):
                                foreach ($images as $img):
                            ?>
                                    <li>
                                        <a href="#" class="elevatezoom-gallery"
                                            data-update=""
                                            data-image="<?= $img['image_path'] ?>"
                                            data-zoom-image="<?= $img['image_path'] ?>">
                                            <img src="<?= $img['image_path'] ?>"
                                                alt="<?= $product->getName() ?>" />
                                        </a>
                                    </li>
                            <?php
                                endforeach;
                            endif;
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="product_d_right">


                    <h1><?= $product->getName() ?></h1>
                    <div class=" product_ratting">
                        <ul>
                            <?php
                            $avg = (int)Review::getAverageRating($db, $product->getId());
                            for ($i = 0; $i < $avg; $i++) :
                            ?>
                                <li><a href="#"><i class="fa fa-star"></i></a></li>
                            <?php endfor; ?>
                            <li class="review"><a href="#"> (<?= count(Review::getProductReviews($db, $product->getId())) ?> reviews) </a></li>
                        </ul>

                    </div>
                    <div class="price_box">
                        <span class="current_price">$<?= $product->getFinalPrice($db) ?></span>
                        <?php if ($product->getDiscount($db) > 0): ?>
                            <span class="old_price">$<?= $product->getPrice() ?></span>
                        <?php endif; ?>

                    </div>
                    <div class="product_desc">
                        <ul>
                            <li>In Stock</li>
                            <li>Free delivery available*</li>
                            <?php if (isset($offer)): ?>
                                <li>Sale <?= (int)$offer->getDiscountPercentage() ?> % Off Use : 'ElectroWorld'</li>
                            <?php endif; ?>
                        </ul>
                        <p><?= $product->getDescription() ?> </p>
                    </div>
                    <div class="product_timing">
                        <div data-countdown="2023/12/15"></div>
                    </div>
                    <div class="product_variant color">
                        <h3>Available Options</h3>
                        <label>Colors</label>
                        <div class="product-colors">
                            <?php
                            $product_colors = $product->getColors($db);
                            if (!empty($product_colors)):
                                foreach ($product_colors as $color):
                            ?>
                                    <div class="color-option"
                                        style="background-color: <?= htmlspecialchars($color['code']) ?>;"
                                        title="<?= htmlspecialchars($color['name']) ?>">
                                    </div>
                                <?php
                                endforeach;
                            else:
                                ?>
                                <p>No colors available</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="product_variant quantity">
                        <label>quantity</label>
                        <form action="index.php?page=Cart_controller&action=add" method="post">
                            <input min="1" name="quantity" max="100" value="1" type="number">
                            <input type="hidden" name="id" value="<?= $product->getId() ?>">
                            <button class="button" type="submit">add to cart</button>
                        </form>
                    </div>
                    <div class=" product_d_action">
                        <ul>
                            <li><a href="index.php?page=wishlist_controller&action=add&id=<?= $product->getId() ?>" title="Add to wishlist">+ Add to Wishlist</a></li>
                        </ul>
                    </div>
                    <div class="product_meta">
                        <span>Category: <a href="#"><?= Category::findById($db, $product->getCategoryId())->getName() ?></a></span>
                    </div>


                    <div class="priduct_social">
                        <ul>
                            <li><a class="facebook" href="#" title="facebook"><i class="fa fa-facebook"></i> Like</a></li>
                            <li><a class="twitter" href="#" title="twitter"><i class="fa fa-twitter"></i> tweet</a></li>
                            <li><a class="pinterest" href="#" title="pinterest"><i class="fa fa-pinterest"></i> save</a></li>
                            <li><a class="google-plus" href="#" title="google +"><i class="fa fa-google-plus"></i> share</a></li>
                            <li><a class="linkedin" href="#" title="linkedin"><i class="fa fa-linkedin"></i> linked</a></li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!--product details end-->

<!--product info start-->
<div class="product_d_info mb-60">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="product_d_inner">
                    <div class="product_info_button">
                        <ul class="nav" role="tablist">
                            <li>
                                <a class="active" data-toggle="tab" href="#info" role="tab" aria-controls="info" aria-selected="false">Description</a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#sheet" role="tab" aria-controls="sheet" aria-selected="false">Specification</a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#reviews" role="tab" aria-controls="reviews" aria-selected="false">Reviews (<?= count(Review::getProductReviews($db, $product->getId())) ?>)</a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="info" role="tabpanel">
                            <div class="product_info_content">
                                <p><?= $product->getDescription() ?></p>
                                <P></P>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="sheet" role="tabpanel">
                            <div class="product_d_table">
                                <form action="#">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td class="first_child">SubCategory</td>
                                                <td><?= $product->getSubcategory($db)['name'] ?></td>
                                            </tr>
                                            <tr>
                                                <td class="first_child">Category</td>
                                                <td><?= Category::findById($db, $product->getCategoryId())->getName() ?></td>
                                            </tr>
                                            <tr>
                                                <td class="first_child">Properties</td>
                                                <td><?= $product->getDescription() ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                            <div class="product_info_content">
                                <p>Fashion has been creating well-designed collections since 2010. The brand offers feminine designs delivering stylish separates and statement dresses which have since evolved into a full ready-to-wear collection in which every item is a vital part of a woman's wardrobe. The result? Cool, easy, chic looks with youthful elegance and unmistakable signature style. All the beautiful pieces are made in Italy and manufactured with the greatest attention. Now Fashion extends to a range of accessories including shoes, hats, belts and more!</p>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="reviews" role="tabpanel">
                            <div class="reviews_wrapper">
                                <h2><?= count(Review::getProductReviews($db, $product->getId())) ?> review for <?= $product->getName() ?></h2>
                                <?php
                                foreach (Review::getProductReviews($db, $product->getId()) as  $review) :
                                    $profile_image = User::get_profile_image($db, $review->getUserId());
                                    $user_review = User::find_by_id($db, $review->getUserId());

                                ?>
                                    <div class="reviews_comment_box">
                                        <div class="comment_thumb">
                                            <img src="<?= $profile_image ?> " alt="" class="rounded-circle">
                                        </div>
                                        <div class="comment_text">
                                            <div class="reviews_meta">
                                                <div class="star_rating">
                                                    <ul>
                                                        <?php
                                                        for ($i = 0; $i < $review->getRating(); $i++) :
                                                        ?>
                                                            <li><a href="#"><i class="ion-ios-star"></i></a></li>
                                                        <?php endfor; ?>
                                                    </ul>
                                                </div>
                                                <p><strong><?= $user_review->get_name() ?> </strong><?= date('F j, Y', strtotime($review->getCreatedAt())) ?></p>
                                                <span><?= $review->getComment() ?></span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                                <div class="product_ratting mb-10">
                                    <form action="index.php?page=Review_controller&action=add" method="POST">
                                        <input type="hidden" name="product_id" value="<?php echo $product->getId(); ?>">
                                        <h3>Your rating</h3>
                                        <div class="rating">
                                            <input type="radio" name="rating" value="5" id="5" required><label for="5">☆</label>
                                            <input type="radio" name="rating" value="4" id="4"><label for="4">☆</label>
                                            <input type="radio" name="rating" value="3" id="3"><label for="3">☆</label>
                                            <input type="radio" name="rating" value="2" id="2"><label for="2">☆</label>
                                            <input type="radio" name="rating" value="1" id="1"><label for="1">☆</label>
                                        </div>
                                        <div class="product_review_form">
                                            <div class="row">
                                                <div class="col-12">
                                                    <label for="review_comment">Your review</label>
                                                    <textarea name="comment" id="review_comment" required></textarea>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Submit Review</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--product info end-->

<!--  JavaScript   -->

<style>
    .rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
    }

    .rating input {
        display: none;
    }

    .rating label {
        cursor: pointer;
        font-size: 30px;
        color: #ddd;
        padding: 5px;
    }

    .rating input:checked~label {
        color: #ffd700;
    }

    .rating label:hover,
    .rating label:hover~label {
        color: #ffd700;
    }

    .product_review_form textarea {
        width: 100%;
        min-height: 100px;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .product_review_form button {
        padding: 10px 20px;
        background: #2c3e50;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .product_review_form button:hover {
        background: #34495e;
    }

    .product-colors {
        display: flex;
        gap: 10px;
        margin-top: 10px;
    }

    .color-option {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: 2px solid #ddd;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .color-option:hover {
        transform: scale(1.1);
        border-color: #333;
    }

    .color-option[title]:hover::after {
        content: attr(title);
        position: absolute;
        background: #333;
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 12px;
        margin-top: 5px;
    }
</style>