<?php
if (!isset($_GET['email'])) {
    header('Location: index.php?page=register');
    exit;
}
$email = htmlspecialchars($_GET['email']);
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Verify Your Email</h3>
                </div>
                <div class="card-body">
                    <p class="text-center">Please enter the verification code sent to your email (<?php echo $email; ?>)</p>
                    
                    <form action="index.php?page=verify_email_controller" method="POST">
                        <input type="hidden" name="email" value="<?php echo $email; ?>">
                        
                        <div class="form-group mb-3">
                            <label for="verification_code">Verification Code</label>
                            <input type="text" class="form-control" id="verification_code" name="verification_code" 
                                   required placeholder="Enter 6-digit code">
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Verify Email</button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-3">
                        <p>Didn't receive the code? <a href="index.php?page=resend_code&email=<?php echo urlencode($email); ?>">Resend Code</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 