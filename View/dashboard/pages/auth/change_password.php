<!--breadcrumbs area start-->
   <!-- Plugins CSS -->
    <link rel="stylesheet" href="public/assets/front/css/plugins.css">
    <link rel="stylesheet" href="public/assets/front/css/style.css">
    <link rel="stylesheet" href="public/assets/front/css/custom.css">
<div class="breadcrumbs_area">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb_content">
                    <ul>
                        <li><a href="index.php?page=home">home</a></li>
                        <li>Forget password</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!--breadcrumbs area end-->

<section class="account">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="account-contents" style="background: url('assets/img/about/about1.jpg'); background-size: cover;">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="account-thumb">
                                <h2>Forgot password?</h2>
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quis consectetur similique deleniti pariatur enim cumque eum</p>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="account-content">
                                <form action="index.php?page=change_password" method="post">
                                    <div class="single-acc-field">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" id="email" placeholder="Enter your Email">
                                    </div>
                                    <div class="single-acc-field">
                                        <label for="new_password">New Password</label>
                                        <input type="password" name="new_password" id="new_password" placeholder="Enter your Email">
                                    </div>
                                    <div class="single-acc-field">
                                        <button type="submit">Reset Password</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>