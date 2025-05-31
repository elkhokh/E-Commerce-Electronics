<?php
use App\Order;
if (!isset($_SESSION['user'])) {
    header('Location: index.php?page=Login');
    exit;
}

$user_id = $_SESSION['user']['id'];
$orders = Order::getOrdersByUserId($db, $user_id);
?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">My Orders</h2>
            
            <?php if (empty($orders)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> You haven't placed any orders yet.
                    <a href="index.php" class="alert-link">Start Shopping</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Payment Method</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>#<?php echo $order->getId(); ?></td>
                                    <td><?php echo date('Y-m-d', strtotime($order->getCreatedAt())); ?></td>
                                    <td>$<?php echo number_format($order->getTotalAmount(), 2); ?></td>
                                    <td>
                                        <span class="badge bg-<?php 
                                            echo match($order->getStatus()) {
                                                'pending' => 'warning',
                                                'completed' => 'success',
                                                'cancelled' => 'danger',
                                                default => 'secondary'
                                            };
                                        ?>">
                                            <?php echo ucfirst($order->getStatus()); ?>
                                        </span>
                                    </td>
                                    <td><?php echo ucfirst($order->getPaymentMethod()); ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <?php if ($order->getStatus() === 'pending'): ?>
                                                <a href="index.php?page=Order_controller&action=cancel&id=<?php echo $order->getId(); ?>" 
                                                   class="btn btn-sm btn-danger"
                                                   onclick="return confirm('Are you sure you want to cancel this order?')">
                                                    <i class="fas fa-times"></i> Cancel
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.table {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

.table thead th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}

.badge {
    padding: 8px 12px;
    font-size: 0.9rem;
}

.btn-group {
    gap: 5px;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.alert {
    border-radius: 10px;
    padding: 1rem;
}

.alert-link {
    text-decoration: none;
    font-weight: 600;
}
</style>
