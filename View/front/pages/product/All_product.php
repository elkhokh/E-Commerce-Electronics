<?php


use App\Product;



$isLoggedIn = isset($_SESSION['user']);
?>
<!--Other product-->
<section class="pt-60 pb-30">
        <div class="container">
            <div class="row">
                <div class="col text-center">
                    <div class="section-title">
                        <h2>All Product</h2>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <?php
                if ($isLoggedIn) {
                    foreach (Product::getAll($db) as $product) :
                    ?>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                        <div class="single-tranding">
                            <a href="index.php?page=product_details&id=<?= $product->getId() ?>">
                                <div class="tranding-pro-img">
                                    <img src="<?= $product->getMainImage() ?>" alt="<?= $product->getName() ?>">
                                </div>
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