<?php
$pageTitle = "Forgot Password";
include 'includes/header.php';
?>

<section class="form-section">

    <form class="auth-form">

        <h2>Forgot Password</h2>

        <p class="form-subtitle">
            Enter your registered email to reset your password.
        </p>

        <input
            type="email"
            placeholder="Registered Email Address"
            required
        >

        <button type="submit">
            Reset Password
        </button>

        <div class="signup-link">

            <p>
                Remember your password?
                <a href="login.php">
                    Login
                </a>
            </p>

        </div>

    </form>

</section>

<?php include 'includes/footer.php'; ?>