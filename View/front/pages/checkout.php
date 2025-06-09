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
<!--Checkout page section-->
<div class="Checkout_section mt-60">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="user-actions">
                    <h3>
                        <i class="fa fa-file-o" aria-hidden="true"></i>
                        Get any promo code?
                        <a class="Returning" href="#" data-toggle="collapse" data-target="#checkout_coupon" aria-expanded="true" aria-controls="checkout_coupon">Click here to enter your code</a>

                    </h3>
                    <div id="checkout_coupon" class="collapse" data-parent="#accordionExample">
                        <div class="checkout_info">
                            <form action="index.php?page=discount_controller&action=apply_discount" method="POST">
                                <input placeholder="Coupon code" name="discount_code" type="text">
                                <button type="submit">Apply coupon</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="checkout_form">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <form action="index.php?page=Order_controller&action=create" method="post">
                        <h3>Billing Details</h3>
                        <div class="row">

                            <div class="col-lg-6 mb-20">
                                <label>First Name <span>*</span></label>
                                <input type="text" name="first_name">
                            </div>
                            <div class="col-lg-6 mb-20">
                                <label>Last Name <span>*</span></label>
                                <input type="text" name="last_name">
                            </div>
                            <div class="col-12 mb-20">
                                <label>Company Name</label>
                                <input type="text" name="company_name">
                            </div>
                            <div class="col-12 mb-20">
                                <label for="country">country <span>*</span></label>
                                <select class="niceselect_option" name="country" id="country">
                                    <option value="palestine">palestine</option>
                                    <option value="Egypt">Egypt</option>
                                    <option value="morocco">morocco</option>
                                    <option value="libya">libya</option>
                                    <option value="syria">syria</option>
                                    <option value="saudi_arabia">saudi arabia</option>

                                </select>
                            </div>

                            <div class="col-12 mb-20">
                                <label>Street address <span>*</span></label>
                                <input placeholder="House number and street name" name="address_street" type="text">
                            </div>
                            <div class="col-12 mb-20">
                                <input placeholder="Apartment, suite, unit etc. (optional)" name="address_apartment" type="text">
                            </div>
                            <div class="col-12 mb-20">
                                <label>Town / City <span>*</span></label>
                                <input name="city" type="text">
                            </div>
                            <div class="col-12 mb-20">
                                <label>State / County <span>*</span></label>
                                <input name="state" type="text">
                            </div>
                            <div class="col-lg-6 mb-20">
                                <label>Phone<span>*</span></label>
                                <input name="phone" type="text">

                            </div>
                            <div class="col-lg-6 mb-20">
                                <label> Email Address <span>*</span></label>
                                <input name="email" type="text">

                            </div>
                            <div class="col-12">
                                <div class="order-notes">
                                    <label for="order_note">Order Notes</label>
                                    <textarea id="order_note" rows="2" placeholder="Notes about your order, e.g. special notes for delivery."></textarea>
                                </div>
                            </div>
                        </div>
                    
                </div>
                <div class="col-lg-6 col-md-6">

                    <h3>Your order</h3>
                    <div class="order_table table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart->getItems() as $item): ?>
                                    <tr>
                                        <td> <?= $item->getProduct()->getName() ?> <strong> × <?= $item->getQuantity() ?></strong></td>
                                        <td> $<?= $item->getProduct()->getFinalPrice($db) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Cart Subtotal</th>
                                    <td>$<?= $cart->getFinalTotal() - (isset($_SESSION['discount_amount']) ? $_SESSION['discount_amount'] : 0) ?></td>
                                </tr>
                                <tr>
                                    <th>Shipping</th>
                                    <td><strong>$50</strong></td>
                                </tr>
                                <tr class="order_total">
                                    <th>Order Total</th>
                                    <td><strong>$<?= $cart->getFinalTotal() - (isset($_SESSION['discount_amount']) ? $_SESSION['discount_amount'] : 0) + 50 ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="payment_method">
                        <div class="panel-default">
                            <input id="payment" name="payment_method" type="radio" value="Direct bank transfer" data-target="createp_account" />
                            <label for="payment" data-toggle="collapse" data-target="#collapseThree" aria-controls="collapseThree">Direct bank transfer</label>

                            <div id="collapseThree" class="collapse" data-parent="#accordionExample">
                                <div class="card-body1">
                                    <p>Please send a check to Store Name, Store Street, Store Town, Store State / County, Store Postcode.</p>
                                </div>
                            </div>
                        </div>
                        <div class="panel-default">
                            <input id="payment_defult" name="payment_method" type="radio" value="PayPal " data-target="createp_account" />
                            <label for="payment_defult" data-toggle="collapse" data-target="#collapseFour" aria-controls="collapseFour">PayPal <img src="Public/assets/front/img/icon/papyel.png" alt=""></label>

                            <div id="collapseFour" class="collapse" data-parent="#accordionExample">
                                <div class="card-body1">
                                    <p>Pay via PayPal; you can pay with your credit card if you don't have a PayPal. <a href="#">What is Paypal?</a></p>
                                </div>
                            </div>
                        </div>
                        <div class="order_button">
                            <input type="hidden" name="Order_Total" value="<?= $cart->getFinalTotal() - (isset($_SESSION['discount_amount']) ? $_SESSION['discount_amount'] : 0) + 50 ?>">
                            <button type="submit">Proceed to buy</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Checkout page section end-->