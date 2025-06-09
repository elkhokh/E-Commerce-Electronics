			<?php

            use App\Category;

            if (isset($_GET['action']) && isset($_GET['id'])) {
                $id = htmlspecialchars(trim($_GET['id']));
                $category =    Category::findById($db, $id);
            }
            ?>
			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
			    <!-- Content Header (Page header) -->
			    <section class="content-header">
			        <div class="container-fluid my-2">
			            <div class="row mb-2">
			                <div class="col-sm-6">
			                    <h1><?= isset($category) ? "Edit Category" : "Create Category" ?></h1>
			                </div>
			                <div class="col-sm-6 text-right">
			                    <a href="index.php?page=Categories" class="btn btn-primary">Back</a>
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
			                <form action="index.php?page=Categories_controller&action=<?= isset($category) ? "update" : "create" ?>" method="post">
			                    <div class="card-body">
			                        <div class="row">
			                            <div class="col-md-6">
			                                <div class="mb-3">
			                                    <label for="name">Name</label>
			                                    <input type="text" name="name" id="name" value="<?= isset($category) ? ucfirst($category->getName()) : '' ?>" class="form-control" placeholder="Name">
			                                </div>
			                            </div>
			                            <div class="col-md-6">
			                                <div class="mb-3">
			                                    <label for="status">Status</label>
			                                    <input type="number" min="0" max="1" name="status" id="status" value="<?= isset($category) ? $category->getStatus() : '' ?>" class="form-control" placeholder="status">
			                                </div>
                                            <?php  if (isset($category)) :?>
                                                <input type="hidden" name="id" value="<?=$category->getId()?>">
                                           <?php endif;
                                            ?>
			                            </div>
			                        </div>
			                    </div>
			            </div>
			            <div class="pb-5 pt-3">
			                <button class="btn <?= isset($category) ? "btn-warning" : "btn-primary" ?>"><?= isset($category) ? "Update Post" : "Create Post" ?></button>
			                <a href="index.php?page=Categories" class="btn btn-outline-dark ml-3">Cancel</a>
			            </div>
			        </div>
			        </form>
			        <!-- /.card -->
			    </section>
			    <!-- /.content -->
			</div>