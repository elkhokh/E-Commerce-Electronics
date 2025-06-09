			<?php

            use App\Brand;
            use App\Category;

            if (isset($_GET['action']) && isset($_GET['id'])) {
                $id = htmlspecialchars(trim($_GET['id']));
                $brand =    Brand::findById($db, $id);
                $category_id = Category::findById($db, $brand->getCategoryId())->getId();
            }
            ?>
			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
			    <!-- Content Header (Page header) -->
			    <section class="content-header">
			        <div class="container-fluid my-2">
			            <div class="row mb-2">
			                <div class="col-sm-6">
			                    <h1><?= isset($brand) ? "Edit Brand" : "Create Brand" ?></h1>
			                </div>
			                <div class="col-sm-6 text-right">
			                    <a href="index.php?page=Brands" class="btn btn-primary">Back</a>
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
			                <form action="index.php?page=brand_controller&action=<?= isset($brand) ? "update" : "create" ?>" method="post">
			                    <div class="card-body">
			                        <div class="row">
			                            <div class="col-md-6">
			                                <div class="mb-3">
			                                    <label for="name">Name</label>
			                                    <input type="text" name="name" id="name" value="<?= isset($brand) ? ucfirst($brand->getName()) : '' ?>" class="form-control" placeholder="Name">
			                                </div>
			                            </div>
			                        </div>
			                        <div class="row">
			                            <div class="col-md-6">
			                                <div class="mb-3">
			                                    <label for="category_id">Category ID</label>
			                                    <input type="number" name="category_id" id="category_id" value="<?= isset($brand) ? $category_id : '' ?>" class="form-control" placeholder="category id">
			                                </div>
			                            </div>
			                            <?php if (isset($brand)) : ?>
			                                <input type="hidden" name="id" value="<?= $brand->getId() ?>">
			                            <?php endif;
                                        ?>
			                        </div>
			                    </div>
			            </div>
			            <div class="pb-5 pt-3">
			                <button class="btn <?= isset($brand) ? "btn-warning" : "btn-primary" ?>"><?= isset($brand) ? "Update brand" : "Create Brand" ?></button>
			                <a href="index.php?page=Brands" class="btn btn-outline-dark ml-3">Cancel</a>
			            </div>
			        </div>
			        </form>
			        <!-- /.card -->
			    </section>
			    <!-- /.content -->
			</div>