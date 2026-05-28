<?php
$pageTitle = "Register";
include 'includes/header.php';
?>

<?php if(isset($_SESSION['error'])): ?>
    <div style="
        background:#ffe5e5;
        color:red;
        padding:10px;
        margin:20px auto;
        max-width:420px;
        border-radius:5px;
        text-align:center;
    ">
        <?= $_SESSION['error']; ?>
    </div>
<?php
unset($_SESSION['error']);
endif;
?>

<section class="form-section">

    <form
        class="auth-form"
        method="POST"
        action="api/register.php"
    >

        <h2>Create Your Account</h2>

        <p class="form-subtitle">
            Join Aurivah and begin your trusted matchmaking journey.
        </p>

        <input
            type="text"
            name="full_name"
            placeholder="Full Name"
            required
        >

        <input
            type="email"
            name="email"
            placeholder="Email Address"
            required
        >

        <input
            type="tel"
            name="mobile"
            placeholder="Mobile Number"
            required
        >

        <input
            type="password"
            name="password"
            placeholder="Create Password"
            required
        >

        <input
            type="password"
            name="confirm_password"
            placeholder="Confirm Password"
            required
        >

        <select name="gender" required>

            <option value="">
                Select Gender
            </option>

            <option value="Male">
                Male
            </option>

            <option value="Female">
                Female
            </option>

            <option value="Other">
                Other
            </option>

        </select>

        <input
            type="number"
            name="age"
            placeholder="Age"
            min="18"
            required
        >

        <div class="terms-box">

            <input
                type="checkbox"
                required
            >

            <label>
                I agree to Terms & Conditions and Privacy Policy.
            </label>

        </div>

        <button type="submit">
            Create Account
        </button>

        <div class="signup-link">

            <p>
                Already have an account?
                <a href="login.php">
                    Login
                </a>
            </p>

        </div>

    </form>

</section>

<?php include 'includes/footer.php'; ?>