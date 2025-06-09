			<?php

            use App\Discount;

            if (isset($_GET['action']) && isset($_GET['id'])) {
                $id = htmlspecialchars(trim($_GET['id']));
                $discount = Discount::findById($db,(int) $id);
            }
            ?>
			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
			    <!-- Content Header (Page header) -->
			    <section class="content-header">
			        <div class="container-fluid my-2">
			            <div class="row mb-2">
			                <div class="col-sm-6">
			                    <h1><?= isset($discount) ? "Edit Category" : "Create Category" ?></h1>
			                </div>
			                <div class="col-sm-6 text-right">
			                    <a href="index.php?page=discount" class="btn btn-primary">Back</a>
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
			                <form action="index.php?page=discount_controller&action=<?= isset($discount) ? "update" : "create" ?>" method="post">
			                    <div class="card-body">
			                        <div class="row">
			                            <div class="col-md-6">
			                                <div class="mb-3">
			                                    <label for="value">Value</label>
			                                    <input type="number" name="value" id="name" value="<?= isset($discount) ? $discount->getValue() : '' ?>" class="form-control" placeholder="Value Discount">
			                                </div>
			                            </div>
			                            <div class="col-md-6">
			                                <div class="mb-3">
			                                    <label for="status">Status</label>
			                                    <input type="number" min="0" max="1" name="status" id="status" value="<?= isset($discount) ? $discount->getStatus() : '' ?>" class="form-control" placeholder="status">
			                                </div>
                                            <?php  if (isset($discount)) :?>
                                                <input type="hidden" name="id" value="<?=$discount->getId()?>">
                                           <?php endif;
                                            ?>
			                            </div>
			                        </div>
			                    </div>
			            </div>
			            <div class="pb-5 pt-3">
			                <button class="btn <?= isset($discount) ? "btn-warning" : "btn-primary" ?>"><?= isset($discount) ? "Update Post" : "Create Post" ?></button>
			                <a href="index.php?page=discount" class="btn btn-outline-dark ml-3">Cancel</a>
			            </div>
			        </div>
			        </form>
			        <!-- /.card -->
			    </section>
			    <!-- /.content -->
			</div>