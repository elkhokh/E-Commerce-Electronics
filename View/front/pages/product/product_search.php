<?php
use App\Product;
use App\Massage;


$name = htmlspecialchars(trim($_POST['name']));

    if (!isset($name) || empty($name)) {
       Massage::set_Massages("error", "Please set Product Name");
      header('Location: index.php?page=home');
      exit;
       }

$Products = Product::find_by_name($db, $name);
?>

<div class="container mt-5">
    <h2> Product Search</h2>
    <?php if (empty($Products)): ?>
        <div class="alert alert-info"> Product is Not Find.</div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($Products as $product): ?>
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
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
