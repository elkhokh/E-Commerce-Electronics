<?php
// Example: Fetch wishlist products for the logged-in user
use App\Product;
use App\Wishlist;

$user_id = $_SESSION['user']['id'] ?? null;

if (!$user_id) {
    header('Location: index.php?page=Login');
    exit;
}

// Get wishlist products (replace with your actual logic)
$wishlistProducts = Wishlist::getUserWishlist($db, $user_id);
?>

<div class="container mt-5">
    <h2>My Wishlist</h2>
    <?php if (empty($wishlistProducts)): ?>
        <div class="alert alert-info">Your wishlist is empty.</div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($wishlistProducts as $product): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="<?= htmlspecialchars($product->getMainImage()) ?>" class="card-img-top" alt="<?= htmlspecialchars($product->getName()) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($product->getName()) ?></h5>
                            <p class="card-text">$<?= htmlspecialchars($product->getPrice()) ?></p>
                            <a href="index.php?page=product_details&id=<?= $product->getId() ?>" class="btn btn-primary">View Details</a>
                            <a href="index.php?page=wishlist_controller&action=remove&id=<?= $product->getId() ?>" class="btn btn-danger">Remove</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
