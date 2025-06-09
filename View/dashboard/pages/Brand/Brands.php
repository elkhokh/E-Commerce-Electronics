										<?php

                                        use App\Brand;
                                        use App\Category;

                                        $page_limit = 7;
                                        $page_num = $_GET['page_num'] ?? 1;
                                        $total = count(Brand::getAll($db));
                                        $totalPages = ceil($total / $page_limit);
                                        $offset = ((int)$page_num - 1) * $page_limit;

                                        if (isset($db)) {
                                            $brands = Brand::getAll($db, $page_limit, $offset);
                                        }
                                        ?>
										<!-- Content Wrapper. Contains page content -->
										<div class="content-wrapper">
										    <!-- Content Header (Page header) -->
										    <section class="content-header">
										        <div class="container-fluid my-2">
										            <div class="row mb-2">
										                <div class="col-sm-6">
										                    <h1>Brands</h1>
										                </div>
										                <div class="col-sm-6 text-right">
										                    <a href="index.php?page=create_brand" class="btn btn-primary">New Brand</a>
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
										                                <th width="60">ID</th>
										                                <th>Name</th>
										                                <th>Category Name</th>
										                                <th width="100">Action</th>
										                            </tr>
										                        </thead>
										                        <tbody>
										                            <?php
                                                                    foreach ($brands as  $brand) :
                                                                        $category_name=Category::findById($db,$brand->getCategoryId())->getName();
                                                                        // var_dump($Category);
                                                                        // exit;
                                                                    ?>
										                                <tr>
										                                    <td><?= $brand->getId() ?></td>
										                                    <td><?= ucfirst($brand->getName()) ?></td>
										                                    <td><?= $category_name ?></td>

										                                    <td>
										                                        <a href="index.php?page=create_brand&action=edit&id=<?= $brand->getId() ?>">
										                                            <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
										                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
										                                            </svg>
										                                        </a>
										                                        <a href="index.php?page=brand_controller&action=delete&id=<?= $brand->getId() ?>" class="text-danger w-4 h-4 mr-1">
										                                            <svg wire:loading.remove.delay="" wire:target="" class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
										                                                <path ath fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
										                                            </svg>
										                                        </a>
										                                    </td>
										                                </tr>
										                            <?php endforeach; ?>
										                        </tbody>
										                    </table>
										                </div>
										                <div class="card-footer clearfix">
										                    <ul class="pagination pagination m-0 float-right">
										                        <ul class="pagination pagination m-0 float-right">
										                            <?php
                                                                    $next = $page_num + 1;
                                                                    for ($i = 1; $i <= $totalPages; $i++) :
                                                                    ?>
										                                <li class="page-item">
										                                    <a class="page-link" href="index.php?page=Brands&page_num=<?= $i ?>"><?= $i ?></a>
										                                </li>
										                            <?php endfor; ?>
										                            <?php if ($total < $next) : ?>
										                                <li class="page-item"><a class="page-link" href="index.php?page=Brands&page_num=<?= $next ?>">next</a></li>
										                            <?php else : ?>
										                                <li class="page-item"><a class="page-link">next</a></li>
										                            <?php endif; ?>
										                            <li class="page-item"><a class="page-link"><i class="fa fa-angle-right"></i></a></li>
										                        </ul>
										                    </ul>
										                </div>
										            </div>
										        </div>
										        <!-- /.card -->
										    </section>
										    <!-- /.content -->
										</div>
										<!-- /.content-wrapper -->