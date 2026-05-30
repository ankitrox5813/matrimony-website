<?php
$pageTitle = "Login";
include 'includes/header.php';
?>

<section class="form-section">

    <form
        class="auth-form"
        method="POST"
        action="api/login.php"
    >

        <h2>Welcome Back</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="auth-alert success">
                <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
        <?php
            unset($_SESSION['success']);
        endif;
        ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="auth-alert error">
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
        <?php
            unset($_SESSION['error']);
        endif;
        ?>

        <p class="form-subtitle">
            Login to continue your matchmaking journey.
        </p>

        <input
            type="email"
            name="email"
            placeholder="Email Address"
            required
        >

        <input
            type="password"
            name="password"
            placeholder="Password"
            required
        >

        <button type="submit">
            Login
        </button>

        <div class="form-links">
            <a href="forgot-password.php">
                Forgot Password?
            </a>
        </div>

        <div class="signup-link">
            <p>
                Don't have an account?
                <a href="register.php">
                    Create Account
                </a>
            </p>
        </div>

    </form>

</section>

<?php include 'includes/footer.php'; ?>