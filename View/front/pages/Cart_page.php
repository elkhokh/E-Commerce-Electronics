<!--shopping cart area start -->
<?php
use App\Cart;
use App\Massage;


if (!isset($_SESSION['user']['id'])) {
    Massage::set_Massages("warning", "Please login to view your cart");
    header('Location: index.php?page=Login');
    exit;
}

$cart = new Cart($_SESSION['user']['id']);
$cart->load($db);

$hasItems = $cart && $cart->getItemsCount() > 0;
?>

<div class="shopping_cart_area mt-60">
    <div class="container">  
        <div class="row">
            <div class="col-12">
                <?php if ($hasItems): ?>
                    <div class="table_desc">
                        <div class="cart_page table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th class="product_thumb">Image</th>
                                        <th class="product_name">Product</th>
                                        <th class="product-price">Price</th>
                                        <th class="product_quantity">Quantity</th>
                                        <th class="product_total">Total</th>
                                        <th class="product_remove">Remove</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cart->getItems() as $item): ?>
                                    <tr>
                                        <td class="product_thumb">
                                            <a href="#"><img src="<?=$item->getProduct()->getMainImage()?>" alt="<?=$item->getProduct()->getMainImage()?>"></a>
                                        </td>
                                        <td class="product_name">
                                            <a href="#"><?=$item->getProduct()->getName()?></a>
                                        </td>
                                        <td class="product-price">$<?=$item->getTotalPrice($db)?></td>
                                        <td class="product_quantity">
                                            <label>Quantity</label>
                                            <form action="index.php?page=Cart_controller&action=chang" method="post" class="d-flex align-items-center"> 
                                                <input min="1" max="10" value="<?=$item->getQuantity()?>" name="quantity" type="number" class="form-control" style="width: 80px;">
                                                <input type="hidden" name="id" value="<?=$item->getProductId()?>">
                                                <button type="submit" class="btn btn-primary ml-2">Update</button>
                                            </form>
                                        </td>
                                        <td class="product_total">$<?=$item->getTotalPrice($db)?></td>
                                        <td class="product_remove">
                                            <a href="index.php?page=Cart_controller&action=remove&id=<?=$item->getProductId()?>">
                                                <i class="ion-android-close"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>   
                        </div>  
                    </div>

                    <!--coupon code area start-->
                    <div class="coupon_area">
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="coupon_code left">
                                    <h3>Coupon</h3>
                                    <form action="index.php?page=discount_controller" method="post">
                                    <div class="coupon_inner">   
                                        <p>Enter your coupon code if you have one.</p>                                
                                        <input placeholder="Coupon code" name="discount_code" type="text">
                                        <button type="submit">Apply coupon</button>
                                    </div>    
                                    </form>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="coupon_code right">
                                    <h3>Cart Totals</h3>
                                    <div class="coupon_inner">
                                        <div class="cart_subtotal">
                                            <p>Subtotal</p>
                                            <p class="cart_amount">$<?=$cart->getFinalTotal()?></p>
                                        </div>
                                        <div class="cart_subtotal">
                                            <p>Shipping</p>
                                            <p class="cart_amount"><span>Flat Rate:</span> $<?=$cart->getFinalTotal()+50?></p>
                                        </div>
                                        <a href="#">Calculate shipping</a>

                                        <div class="cart_subtotal">
                                            <p>Total</p>
                                            <p class="cart_amount">$<?=$cart->getFinalTotal()+50 -(isset($_SESSION['discount_amount'])?$_SESSION['discount_amount']:0)?></p>
                                        </div>
                                        <div class="checkout_btn">
                                            <a href="index.php?page=checkout">Proceed to Checkout</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--coupon code area end-->
                <?php else: ?>
                    <div class="alert alert-info">Your cart is empty .</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<!--shopping cart area end -->