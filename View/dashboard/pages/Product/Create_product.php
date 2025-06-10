<?php
use App\Color;
use App\Category;
use App\Brand;
?>
<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">					
					<div class="container-fluid my-2">
						<div class="row mb-2">
							<div class="col-sm-6">
								<h1>Create Product</h1>
							</div>
							<div class="col-sm-6 text-right">
								<a href="index.php?page=products" class="btn btn-primary">Back</a>
							</div>
						</div>
					</div>
					<!-- /.container-fluid -->
				</section>
				<!-- Main content -->
				<section class="content">
					<!-- Default box -->
					<div class="container-fluid">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="card mb-3">
                                    <div class="card-body">								
                                        <div class="row">
                                            <form action="index.php?page=product_controller&action=add" method="post" enctype="multipart/form-data">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="title">Title</label>
                                                    <input type="text" name="title" id="title" class="form-control" placeholder="Title">	
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="description">Description</label>
                                                    <textarea name="description" id="description" cols="30" rows="10" class="summernote" placeholder="Description"></textarea>
                                                </div>
                                            </div>                                            
                                        </div>
                                    </div>	                                                                      
                                </div>
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h2 class="h4 mb-3">Pricing</h2>								
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="price">Price</label>
                                                    <input type="text" name="price" id="price" class="form-control" placeholder="Price">	
                                                </div>
                                            </div>                                            
                                        </div>
                                    </div>	                                                                      
                                </div>
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h2 class="h4 mb-3">Inventory</h2>								
                                        <div class="row">   
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="qty" > Quantity</label>
                                                    <input type="number" min="0" name="qty" id="qty" class="form-control" placeholder="Qty">
                                                </div>
                                            </div>                                         
                                        </div>
                                    </div>	                                                                      
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card mb-3">
                                    <div class="card-body">	
                                        <h2 class="h4 mb-3">Product status</h2>
                                        <div class="mb-3">
                                            <select name="status" id="status" class="form-control">
                                                <option value="1">Active</option>
                                                <option value="0">Block</option>
                                            </select>
                                        </div>
                                    </div>
                                </div> 
                                <div class="card">
                                    <div class="card-body">	
                                        <h2 class="h4  mb-3">Product category</h2>
                                        <div class="mb-3">
                                            <label for="category">Category</label>
                                            <select name="category" id="category" class="form-control">
                                             <?php 
                                            foreach (Category::getAll($db) as $category) :
                                            ?>
                                                <option value="<?=$category->getId()?>"><?=$category->getName()?></option>
                                                <?php endforeach ;?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="category"> Brands</label>
                                            <select name="sub_category" id="sub_category" class="form-control">
                                                 <?php 
                                                foreach (Brand::getAll($db) as $brand) :
                                                ?>
                                                <option value="<?=$brand->getId()?>"><?=$brand->getName()?></option>
                                                <?php endforeach ;?>
                                            </select>
                                        </div>
                                    </div>
                                </div>                          
                            </div>
                        </div>

						<div class="card mb-3">
							<div class="card-body">
								<h2 class="h4 mb-3">Product Colors</h2>
								<div class="row">
									<div class="col-md-12">
										<div class="mb-3">
											<div class="color-options">
												<div class="d-flex flex-wrap">
                                                    <?php
                                                    foreach (Color::getAllColors($db) as $color) :
                                                    ?>
													<div class="color-option me-2 mb-2">
														<input type="checkbox" name="colors[]" value="<?=$color->getId()?>" id="color-<?=$color->getName()?>" class="d-none">
														<label for="color-<?=$color->getName()?>" class="color-label" style="background-color: <?=$color->getCode()?>;"></label>
													</div>
                                                    <?php endforeach;?>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="card mb-3">
							<div class="card-body">
								<h2 class="h4 mb-3">Product Images</h2>
								<div class="mb-3">
									<label for="product_images">Main Image</label>
									<input type="file" name="main_image" id="product_images" class="form-control"  accept="image/*">
									<small class="form-text text-muted">You can select one image.</small>
								</div>
								<div class="mb-3">
									<label for="product_images">Upload Images</label>
									<input type="file" name="product_images[]" id="product_images" class="form-control" multiple accept="image/*">
									<small class="form-text text-muted">You can select multiple images.</small>
								</div>
							</div>
						</div>
					

						<div class="pb-5 pt-3">
							<button class="btn btn-primary">Create</button>
                            </form>
							<a href="products.html" class="btn btn-outline-dark ml-3">Cancel</a>
						</div>
					</div>
					<!-- /.card -->
				</section>
				<!-- /.content -->
			</div>
<style>

.color-label {
    display: inline-block;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    border: 2px solid #ccc;
    cursor: pointer;
}
input[type="checkbox"]:checked + .color-label {
    border: 2px solid #000;
}
</style>

