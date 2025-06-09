<?php

use App\order;
use App\User;
use App\Product;
use App\Orderitem;


$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$order = Order::findById($db, $id);
$order_items = Orderitem::getItemsByOrderId($db, $order->getId());
$user = User::find_by_id($db, $order->getUserId());
if (!$order) {
    header("Location: index.php?page=orders");
    exit;
}
?>
<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">					
					<div class="container-fluid my-2">
						<div class="row mb-2">
							<div class="col-sm-6">
								<h1>Order: #5F<?= $order->getId() ?></h1>
							</div>
							<div class="col-sm-6 text-right">
                                <a href="index.php?page=orders" class="btn btn-primary">Back</a>
							</div>
						</div>
					</div>
					<!-- /.container-fluid -->
				</section>
				<!-- Main content -->
				<section class="content">
					<!-- Default box -->
					<div class="container-fluid">
						<div class="row">
                            <div class="col-md-9">
                                <div class="card">
                                    <div class="card-header pt-3">
                                        <div class="row invoice-info">
                                            <div class="col-sm-4 invoice-col">
                                            <h1 class="h5 mb-3">Shipping Address</h1>
                                            <address>
                                                <strong><?= $user->get_name() ?></strong><br>
                                                <?= $order->getShippingAddress() ?><br>
                                                Phone: <?= $order->getPhone() ?><br>
                                                Email: <?= $user->get_email() ?>
                                            </address>
                                            </div>
                                            
                                            
                                            
                                            <div class="col-sm-4 invoice-col">
                                                <b>Invoice #007<?= $order->getId() ?></b><br>
                                                <br>
                                                <b>Order ID:</b> 5F<?= $order->getId() ?><br>
                                                <b>Total:</b> $<?= $order->getTotalAmount() ?><br>
                                                <b>Status:</b> <span class="text-success"><?= $order->getStatus() ?></span>
                                                <br>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body table-responsive p-3">								
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Product</th>
                                                    <th width="100">Price</th>
                                                    <th width="100">Qty</th>                                        
                                                    <th width="100">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $Subtotal=0.0;
                                                foreach ($order_items as  $item) :
                                                $product=Product::findById($db,$item->getProductId());
                                               $Subtotal  +=$item->getTotalPrice();
                                                ?>
                                                <tr>
                                                    <td><?= $product->getName() ?></td>
                                                    <td>$<?= $product->getPrice() ?></td>                                        
                                                    <td><?= $item->getQuantity() ?></td>
                                                    <td>$<?= $item->getTotalPrice() ?></td>
                                                </tr>
                                                <?php endforeach;?>
                                                <tr>
                                                    <th colspan="3" class="text-right">Subtotal:</th>
                                                    <td>$<?=$Subtotal?></td>
                                                </tr>
                                                
                                                <tr>
                                                    <th colspan="3" class="text-right">Shipping:</th>
                                                    <td>$50.00</td>
                                                </tr>
                                                <tr>
                                                    <th colspan="3" class="text-right">Grand Total:</th>
                                                    <td>$<?=$Subtotal+50?></td>
                                                </tr>
                                            </tbody>
                                        </table>								
                                    </div>                            
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                    <form action="index.php?page=Order_controller&action=update_status" method="post">
                                        <h2 class="h4 mb-3">Order Status</h2>
                                        <div class="mb-3">
                                            <select name="status" id="status" class="form-control">
                                                <option value="pending">pending</option>
                                                <option value="processing">processing</option>
                                                <option value="completed">completed</option>
                                                <option value="cancelled">cancelled</option>
                                            </select>
                                        </div>
                                        <input type="hidden" name="order_id" value="<?=$order->getId()?>">
                                        <div class="mb-3">
                                            <button class="btn btn-primary">Update</button>
                                        </div>
                                    </form>
                                    </div>
                                </div>
                            </div>
                        </div>
					</div>
					<!-- /.card -->
				</section>
				<!-- /.content -->
			</div>
			<!-- /.content-wrapper -->