<?php
use App\User;
use App\Massage;


if (!isset($_SESSION['user']['id'])) {
    Massage::set_Massages("warning", "Please login to view your cart");
    header('Location: index.php?page=Login');
    exit;
}

$user_id = $_SESSION['user']['id'];
$profile_image = User::get_profile_image($db, $user_id);

?>
<!-- My Account Page -->
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card p-4">
                <h2 class="mb-4 text-center">My Account</h2>
                <div class="text-center mb-3">
                    <!-- User Image -->
                    <img src="<?= $profile_image ?>" alt="User Image" id="userImage" class="rounded-circle">
                </div>
                <form action="index.php?page=update_profile" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="userImageUpload" class="form-label">Change Profile Picture</label>
                        <input class="form-control" type="file" id="userImageUpload" name="userImage" accept="image/*">
                        <input type="hidden" name="user_id" value="<?=$user_id?>">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Upload New Image</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    #userImage {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #007bff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
</style>
