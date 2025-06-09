				<?php

                use App\Blogs;

                if (isset($_GET['action']) && isset($_GET['id']) && $_GET['action'] == 'edit') {
                    $id = htmlspecialchars(trim($_GET['id']));
                    $blog = Blogs::findById($db, $id);
                } else {
                    $user_id = null;
                }
                ?>
				<!-- Content Wrapper. Contains page content -->
				<div class="content-wrapper">
				    <!-- Content Header (Page header) -->
				    <section class="content-header">
				        <div class="container-fluid my-2">
				            <div class="row mb-2">
				                <div class="col-sm-6">
				                    <h1><?= isset($blog) ? "Edit Blog" : "Create Blog" ?></h1>
				                </div>
				                <div class="col-sm-6 text-right">
				                    <a href="index.php?page=blogs" class="btn btn-primary">Back</a>
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
				                    <h3 class="card-title"><?= isset($blog) ? "Edit Blog" : "Create Blog" ?></h3>
				                </div>
				                <form action="index.php?page=blog_controller&action=<?= isset($blog) ? "update" : "create" ?>" method="post" enctype="multipart/form-data">
				                    <div class="card-body">
				                        <div class="row">
				                            <div class="col-md-6">
				                                <div class="mb-3">
				                                    <label for="title">Title</label>
				                                    <input type="text" name="title" value="<?= isset($blog) ? $blog->getTitle() : '' ?>" id="title" class="form-control" placeholder="Title" required>
				                                    <div class="invalid-feedback">Please enter a title.</div>
				                                </div>
				                            </div>
				                            <div class="col-md-6">
				                                <div class="mb-3">
				                                    <label for="content">Content</label>
				                                    <textarea name="content" id="content" class="form-control" placeholder="Content" rows="5"><?= isset($blog) ? $blog->getContent() : '' ?></textarea>
				                                </div>
				                            </div>
				                            <div class="card mb-3">
				                                <div class="card-body">
				                                    <h2 class="h4 mb-3">Blog Images</h2>
				                                    <div class="mb-3">
				                                        <label for="main_image">Main Image</label>
				                                        <input type="file" name="main_image" id="main_image" class="form-control" accept="image/*">
				                                        <?php if (isset($blog) && $blog->getImage()): ?>
				                                            <div class="mt-2">
				                                                <img src="<?= $blog->getImage() ?>" alt="Main Image" class="img-thumbnail" style="max-width:200px;">
				                                                <p class="text-muted mt-1">Current Image</p>
				                                            </div>
				                                        <?php endif; ?>
				                                    </div>
				                                </div>
				                            </div>
				                        </div>
				                    </div>
				                    <?php if (isset($blog)): ?>
				                        <input type="hidden" name="id" value="<?= $blog->getId() ?>">
				                    <?php endif; ?>
				                    <div class="pb-5 pt-3">
				                        <button class="btn <?= isset($blog) ? "btn-warning" : "btn-primary" ?>"><?= isset($blog) ? "Update Blog" : "Create Blog" ?></button>
				                        <a href="index.php?page=blogs" class="btn btn-outline-dark ml-3">Cancel</a>
				                    </div>
				                </form>
				            </div>
				            <!-- /.card -->
				    </section>
				    <!-- /.content -->
				</div>
				<!-- /.content-wrapper -->