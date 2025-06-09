			<?php

            use App\Product;
            use App\Offers;

            if (isset($_GET['action']) && isset($_GET['id'])) {
                $id = htmlspecialchars(trim($_GET['id']));
                $offer =    Offers::findById($db, $id);
            }
            ?>
			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
			    <!-- Content Header (Page header) -->
			    <section class="content-header">
			        <div class="container-fluid my-2">
			            <div class="row mb-2">
			                <div class="col-sm-6">
			                    <h1><?= isset($offer) ? "Edit offer" : "Create offer" ?></h1>
			                </div>
			                <div class="col-sm-6 text-right">
			                    <a href="index.php?page=offers" class="btn btn-primary">Back</a>
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
			                <form action="index.php?page=offers_controller&action=<?= isset($offer) ? "update" : "create" ?>" method="post">
			                    <div class="card-body">
			                        
			                            <div class="col-md-6">
			                                <div class="mb-3">
			                                    <label for="name">title</label>
			                                    <input type="text" name="title" id="name" value="<?= isset($offer) ? ucfirst($offer->getTitle()) : '' ?>" class="form-control" placeholder="Name">
			                                </div>
			                            </div>
			                            <div class="col-md-12">
			                                <div class="mb-3">
			                                    <label for="description">Description</label>
			                                    <textarea name="description" id="description"  value="" cols="30" rows="10" class="summernote" placeholder="Description"><?= isset($offer) ? $offer->getDescription() : '' ?></textarea>
			                                </div>
			                            </div>
			                            <div class="row">
			                                <div class="col-md-12">
			                                    <div class="mb-3">
			                                        <label for="price">Product ID</label>
			                                        <input type="text" name="product_id" value="<?= isset($offer) ? $offer->getProductId() : '' ?>" id="price" class="form-control" placeholder="Product ID">
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="row">
			                                <div class="col-md-12">
			                                    <div class="mb-3">
			                                        <label for="price">Discount Float </label>
			                                        <input type="text" name="discount_percentage" id="price" value="<?= isset($offer) ? $offer->getDiscountPercentage() : '' ?>" class="form-control" placeholder="Discount Float Number">
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-md-6">
			                                <div class="mb-3">
			                                    <label for="status">Status</label>
			                                    <input type="number" min="0" max="1" name="status" id="status" value="<?= isset($offer) ? $offer->getStatus() : '' ?>" class="form-control" placeholder="status">
			                                </div>
			                                <?php if (isset($offer)) : ?>
			                                    <input type="hidden" name="id" value="<?= $offer->getId() ?>">
			                                <?php endif;
                                            ?>
			                            </div>
			                            
			                                <div class="col-md-6">
			                                    <div class="mb-3">
			                                        <label for="end_date">End Date</label>
			                                        <input type="datetime-local" 
			                                               name="end_date" 
			                                               id="end_date" 
			                                               value="<?= isset($offer) ? date('Y-m-d\TH:i', strtotime($offer->getEndDate())) : date('Y-m-d\TH:i', strtotime('+30 days')) ?>" 
			                                               class="form-control" 
			                                               required>
			                                    </div>
			                                </div>
			                            
			                        </div>
			                    </div>
			            </div>
			            <div class="pb-5 pt-3">
			                <button class="btn <?= isset($offer) ? "btn-warning" : "btn-primary" ?>"><?= isset($offer) ? "Update offer" : "Create offer" ?></button>
			                <a href="index.php?page=Categories" class="btn btn-outline-dark ml-3">Cancel</a>
			            </div>
			        </div>
			        </form>
			        <!-- /.card -->
			    </section>
			    <!-- /.content -->
			</div>