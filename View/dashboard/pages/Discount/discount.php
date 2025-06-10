								<?php

                                use App\Discount;
                                if (isset($db)) {
                                    $discounts = Discount::getAll($db);
                                }
                                ?>
								<!-- Content Wrapper. Contains page content -->
								<div class="content-wrapper">
								    <!-- Content Header (Page header) -->
								    <section class="content-header">
								        <div class="container-fluid my-2">
								            <div class="row mb-2">
								                <div class="col-sm-6">
								                    <h1>Discount</h1>
								                </div>
								                <div class="col-sm-6 text-right">
								                    <a href="index.php?page=discount_create" class="btn btn-primary">New Discount</a>
								                </div>
								            </div>
								        </div>
								        <!-- /.container-fluid -->
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
								                                    <th>Code</th>
								                                    <th>Value</th>
								                                    <th width="100">Status</th>
								                                    <th width="100">Action</th>
								                                </tr>
								                            </thead>
								                            <tbody>
								                                <?php
                                                                foreach ($discounts as  $discount) :

                                                                ?>
								                                    <tr>
								                                        <td><?= $discount->getId() ?></td>
								                                        <td><?= $discount->getCode() ?></td>
								                                        <td><?= $discount->getValue() ?></td>
								                                        
								                                            <?php if ($discount->getStatus() == 1) : ?>
								                                                <td>
								                                                    <svg class="text-success-500 h-6 w-6 text-success" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
								                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
								                                                    </svg>
								                                                </td>
								                                            <?php else: ?>
								                                                <td>
								                                                    <svg class="text-danger h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
								                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
								                                                    </svg>
								                                                </td>
								                                            <?php endif; ?>
								                                        
								                                        <td>
								                                            <a href="index.php?page=discount_create&action=edit&id=<?= $discount->getId() ?>">
								                                                <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
								                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
								                                                </svg>
								                                            </a>
								                                            <a href="index.php?page=discount_controller&action=delete&id=<?= $discount->getId() ?>" class="text-danger w-4 h-4 mr-1">
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
								                </div>
								            </div>
								            <!-- /.card -->
								        </section>
								        <!-- /.content -->
								</div>
								<!-- /.content-wrapper -->