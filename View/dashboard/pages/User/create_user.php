				<?php

                use App\User;

                if (isset($_GET['action']) && isset($_GET['user_id']) && $_GET['action'] == 'edit') {
                    $user_id = htmlspecialchars(trim($_GET['user_id']));
                    $users = User::find_by_id($db, $user_id);
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
				                    <h1><?= isset($users) ? "Edit User" : "Create User" ?></h1>
				                </div>
				                <div class="col-sm-6 text-right">
				                    <a href="index.php?page=user" class="btn btn-primary">Back</a>
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
				                <form action="index.php?page=user_controller&action=<?= isset($users) ? "update" : "create" ?>" method="post">
				                    <div class="card-body">
				                        <div class="row">
				                            <div class="col-md-6">
				                                <div class="mb-3">
				                                    <label for="name">Name</label>
				                                    <input type="text" name="name"  value="<?= isset($users) ? $users->get_name() : '' ?>" id="name" class="form-control" placeholder="Name">
				                                </div>
				                            </div>
				                            <div class="col-md-6">
				                                <div class="mb-3">
				                                    <label for="email">Email</label>
				                                    <input type="text" name="email" id="email"  value="<?= isset($users) ?$users->get_email() : '' ?>" class="form-control" placeholder="Email">
				                                </div>
				                            </div>
				                            <div class="col-md-6">
				                                <div class="mb-3">
				                                    <label for="password">Password</label>
				                                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" <?= isset($users) ? '' : 'required' ?>>
				                                </div>
				                            </div>
				                            <div class="col-md-4">
				                                <div class="card-body">
				                                    <h2 class="h4 mb-3">User status</h2>
				                                    <div class="mb-3">
				                                        <select name="status" id="status" class="form-control">
				                                            <option value="1" <?= (isset($users) && $users->get_status() == 1) ? 'selected' : '' ?>>Active</option>
				                                            <option value="0" <?= (isset($users) && $users->get_status() == 0) ? 'selected' : '' ?>>Block</option>
				                                        </select>
				                                    </div>
				                                </div>
				                                <div class="card-body">
				                                    <h2 class="h4 mb-3">User Role</h2>
				                                    <div class="mb-3">
				                                        <select name="role" id="role" class="form-control">
				                                            <option value="user" <?= (isset($users) && $users->get_role() == 'user') ? 'selected' : '' ?>>user</option>
				                                            <option value="admin" <?= (isset($users) && $users->get_role() == 'admin') ? 'selected' : '' ?>>admin</option>
				                                        </select>
				                                    </div>
				                                </div>
				                            </div>
				                        </div>
				                    </div>
				                    <?php if (isset($users)): ?>
				                        <input type="hidden" name="id" value="<?= $users->get_id() ?>">
				                    <?php endif; ?>
				                    <div class="pb-5 pt-3">
				                        <button class="btn <?= isset($users) ? "btn-warning" : "btn-primary" ?>"><?= isset($users) ? "Update User" : "Create User" ?></button>
				                        <a href="index.php?page=user" class="btn btn-outline-dark ml-3">Cancel</a>
				                    </div>
				                </form>
				            </div>
				            <!-- /.card -->
				    </section>
				    <!-- /.content -->
				</div>
				<!-- /.content-wrapper -->