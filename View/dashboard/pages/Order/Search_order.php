	<?php

    use App\Order;
    use App\User;

   $search = isset($_POST['search']) ? htmlspecialchars(trim($_POST['search'])) : '';
$product = [];

if (!empty($search)) {
    $user = User::findByName($db, $search);
    $user_id=$user->get_id();
    $orders=Order::getOrdersByUserId($db,$user_id);
    // var_dump($product);
    // exit;
}

    ?>
	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
	    <!-- Content Header (Page header) -->
	    <section class="content-header">
	        <div class="container-fluid my-2">
	            <div class="row mb-2">
	                <div class="col-sm-6">
	                    <h1>Orders</h1>
	                </div>
	                <div class="col-sm-6 text-right">
	                </div>
	            </div>
	        </div>
	        <!-- /.container-fluid -->
	    </section>
	    <!-- Main content -->
	    <section class="content">
	        <!-- Default box -->
	        <div class="container-fluid">
	            <div class="card">
	                <div class="card-body table-responsive p-0">
	                    <table class="table table-hover text-nowrap">
	                        <thead>
	                            <tr>
	                                <th>Orders #</th>
	                                <th>Customer</th>
	                                <th>Email</th>
	                                <th>Phone</th>
	                                <th>Status</th>
	                                <th>Total</th>
	                                <th>Date Purchased</th>
	                            </tr>
	                        </thead>
	                        <tbody>
	                            <?php
                                foreach ($orders as  $order) :
                                    $user = User::find_by_id($db, $order->getUserId());
                                ?>
	                                <tr>
	                                    <td><a href="index.php?page=order_detail&id=<?= $order->getId() ?>">#5F<?= $order->getId() ?></a></td>
	                                    <td><?= $user->get_name() ?></td>
	                                    <td><?= $user->get_email() ?></td>
	                                    <td><?= $order->getPhone() ?></td>
	                                    <td>
	                                        <span class="badge bg-success"><?= $order->getStatus() ?></span>
	                                    </td>
	                                    <td>$<?= $order->getTotalAmount() ?></td>
	                                    <td><?=date('F j, Y', strtotime($order->getCreatedAt()))  ?></td>
	                                </tr>
	                            <?php endforeach; ?>
	                        </tbody>
	                    </table>
	                </div>
	            </div>
	        </div>
	        <!-- /.card -->
	    </section>
	    <!-- /.content -->
	</div>
	<!-- /.content-wrapper -->