<?php
use App\Order;
use App\Product;
if (!isset($_GET['id'])) {
    header('Location: index.php?page=checkout');
    exit;
}

$order_id = (int)$_GET['id'];
$order = Order::findById($db, $order_id);

if (!$order || $order->getUserId() !== $_SESSION['user']['id']) {
    header('Location: index.php?page=checkout');
    exit;
}

$order_items = $order->getItems($db);
?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <!-- Thank you message -->
            <div class="text-center mb-5">
                <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                <h2 class="mt-3">Thank You for Your Order!</h2>
                <p class="text-muted">Your order has been received and will be processed shortly</p>
            </div>

            <!-- Order details -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Order Details #<?= $order->getId(); ?></h5>
                </div>
                <div class="card-body">
                    <!-- Order information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Order Information</h6>
                            <p><strong>Order Number:</strong> #<?= $order->getId(); ?></p>
                            <p><strong>Order Date:</strong> <?= date('Y-m-d', strtotime($order->getCreatedAt())); ?></p>
                            <p><strong>Order Status:</strong> 
                                <span class="badge bg-<?= $order->getStatus() === 'pending' ? 'warning' : 'success'; ?>">
                                    <?= ucfirst($order->getStatus()); ?>
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Shipping Information</h6>
                            <p><strong>Shipping Address:</strong> <?= $order->getShippingAddress(); ?></p>
                            <p><strong>Payment Method:</strong> <?= $order->getPaymentMethod(); ?></p>
                            <p><strong>Phone:</strong> <?= $order->getPhone(); ?></p>
                        </div>
                    </div>

                    <!-- Products -->
                    <h6 class="text-muted mb-3">Ordered Products</h6>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order_items as $item):
                                $product=Product::findById($db,$item->getProductId());
                                    // var_dump($item->getQuantity());
                                    // exit; ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="<?= $product->getMainImage(); ?>" 
                                                 alt="<?= $product->getName(); ?>" 
                                                 class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                            <span class="ms-2"><?= $product->getName(); ?></span>
                                        </div>
                                    </td>
                                    <td>$<?= number_format($item->getPrice(), 2); ?></td>
                                    <td><?= $item->getQuantity(); ?></td>
                                    <td>$<?= number_format($item->getPrice() * $item->getQuantity(), 2); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total Amount:</strong></td>
                                    <td><strong>$<?= number_format($order->getTotalAmount(), 2); ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Control buttons -->
            <div class="text-center mt-4">
                <a href="index.php?page=all_orders" class="btn btn-secondary me-2">
                    <i class="fas fa-arrow-right"></i> Go to All Orders
                </a>
                <a href="index.php?page=home" class="btn btn-primary">
                    <i class="fas fa-home"></i> Back to Home
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 10px;
    border: none;
}
.card-header {
    border-radius: 10px 10px 0 0 !important;
}
.table th {
    font-weight: 600;
    background-color: #f8f9fa;
}
.img-thumbnail {
    border-radius: 5px;
}
.badge {
    padding: 8px 12px;
    font-size: 0.9rem;
}
</style>
