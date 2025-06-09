	<?php

    use App\Order;
    use App\User;

    $page_limit = 7;
    $page_num = $_GET['page_num'] ?? 1;
    $total = Order::getCount($db);
    $totalPages = ceil($total / $page_limit);
    $offset = ((int)$page_num - 1) * $page_limit;

    if (isset($db)) {
        $orders = Order::getAll($db, $page_limit, $offset);
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
	                <div class="card-header">
	                    <div class="card-tools">
                            <form action="index.php?page=Search_order" method="post">
	                        <div class="input-group input-group" style="width: 250px;">
	                            <input type="text" name="search" class="form-control float-right" placeholder="Customer Name">

	                            <div class="input-group-append">
	                                <button type="submit" class="btn btn-default">
	                                    <i class="fas fa-search"></i>
	                                </button>
	                            </div>
	                        </div>
                            </form>
	                    </div>
	                </div>
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
	                <div class="card-footer clearfix">
	                    <ul class="pagination pagination m-0 float-right">
	                        <?php
                            $next = $page_num + 1;
                            for ($i = 1; $i <= $totalPages; $i++) :
                            ?>
	                            <li class="page-item">
	                                <a class="page-link" href="index.php?page=orders&page_num=<?= $i ?>"><?= $i ?></a>
	                            </li>
	                        <?php endfor; ?>
	                        <?php if ($total < $next) : ?>
	                            <li class="page-item"><a class="page-link" href="index.php?page=orders&page_num=<?= $next ?>">next</a></li>
	                        <?php else : ?>
	                            <li class="page-item"><a class="page-link">next</a></li>
	                        <?php endif; ?>
	                        <li class="page-item"><a class="page-link"><i class="fa fa-angle-right"></i></a></li>
	                    </ul>
	                </div>
	            </div>
	        </div>
	        <!-- /.card -->
	    </section>
	    <!-- /.content -->
	</div>
	<!-- /.content-wrapper -->