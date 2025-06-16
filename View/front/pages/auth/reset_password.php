<?php
if (!isset($_GET['email'])) {
    header('Location: index.php?page=forget_password');
    exit;
}
$email = htmlspecialchars($_GET['email']);
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Reset Password</h3>
                </div>
                <div class="card-body">
                    <p class="text-center">Please enter the verification code sent to your email (<?php echo $email; ?>) and your new password</p>
                    
                    <form action="index.php?page=change_password" method="POST">
                        <input type="hidden" name="email" value="<?= $email; ?>">
                        
                        <div class="form-group mb-3">
                            <label for="verification_code">Verification Code</label>
                            <input type="text" class="form-control" id="verification_code" name="verification_code" 
                                   required placeholder="Enter 6-digit code">
                        </div>

                        <div class="form-group mb-3">
                            <label for="new_password">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" 
                                   required placeholder="Enter new password">
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Reset Password</button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-3">
                        <p>Didn't receive the code? <a href="index.php?page=request_password_reset&email=<?php echo urlencode($email); ?>">Resend Code</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 