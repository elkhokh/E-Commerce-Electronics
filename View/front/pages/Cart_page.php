 <!--shopping cart area start -->
 <?php
 use App\Cart;
 if (isset($_SESSION['user']['id'])) {
 $cart=new Cart($_SESSION['user']['id']);
 $cart->load($db);
 }
 ?>
    <div class="shopping_cart_area mt-60">
        <div class="container">  
                <div class="row">
                    <div class="col-12">
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
                                <?php
                                // var_dump($cart->getItems()[0]->getProduct());
                                // exit;
                                foreach ($cart->getItems()as  $item) :
                                ?>
                                <tr>
                                    <td class="product_thumb"><a href="#"><img src="<?=$item->getProduct()->getMainImage()?>" alt="<?=$item->getProduct()->getMainImage()?>"></a></td>
                                    <td class="product_name"><a href="#"><?=$item->getProduct()->getName()?></a></td>
                                    <td class="product-price"><?=$item->getProduct()->getPrice()?></td>
                                    <td class="product_quantity">
                                        <label>Quantity</label>
                                        <form action="index.php?page=Cart_controller&action=chang" method="post" class="d-flex align-items-center"> 
                                            <input min="1" max="10" value="<?=$item->getQuantity()?>" name="quantity" type="number" class="form-control" style="width: 80px;">
                                            <input type="hidden" name="id" value="<?=$item->getProductId()?>">
                                            <button type="submit" class="btn btn-primary ml-2">Update</button>
                                        </form>
                                    </td>
                                    <td class="product_total"><?=$item->getTotalPrice()?></td>
									<td class="product_remove"><a href="index.php?page=Cart_controller&action=remove&id=<?=$item->getProductId()?>"><i class="ion-android-close"></i></a></td>
                                </tr>
                                <?php endforeach; ?>

                            </tbody>
                        </table>   
                            </div>  
    
                        </div>
                    </div>
                </div>
                <!--coupon code area start-->
                <div class="coupon_area">
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="coupon_code left">
                                <h3>Coupon</h3>
                                <div class="coupon_inner">   
                                    <p>Enter your coupon code if you have one.</p>                                
                                    <input placeholder="Coupon code" type="text">
                                    <button type="submit">Apply coupon</button>
                                </div>    
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="coupon_code right">
                                <h3>Cart Totals</h3>
                                <div class="coupon_inner">
                                <div class="cart_subtotal">
                                    <p>Subtotal</p>
                                    <p class="cart_amount"><?=$cart->getFinalTotal()?></p>
                                </div>
                                <div class="cart_subtotal ">
                                    <p>Shipping</p>
                                    <p class="cart_amount"><span>Flat Rate:</span> $255.00</p>
                                </div>
                                <a href="#">Calculate shipping</a>

                                <div class="cart_subtotal">
                                    <p>Total</p>
                                    <p class="cart_amount">$215.00</p>
                                </div>
                                <div class="checkout_btn">
                                    <a href="#">Proceed to Checkout</a>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--coupon code area end-->
             
        </div>     
    </div>
    <!--shopping cart area end -->