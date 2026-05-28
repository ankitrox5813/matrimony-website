<?php
$pageTitle = "Aurivah - Trusted Matrimonial Service";
include 'includes/header.php';
?>

<section class="hero">
    <div class="hero-content">
        <h1>Find Your Perfect Life Partner</h1>
        <p>Trusted Matrimonial Service Since 2019</p>

        <a href="register.php" class="btn-primary">
            Register Free
        </a>
    </div>
</section>

<section class="search-section">

    <h2>Catch Your Match</h2>

    <div class="search-box">

        <select id="religion-select">
            <option value="">Religion</option>
            <option value="Hindu">Hindu</option>
            <option value="Muslim">Muslim</option>
            <option value="Sikh">Sikh</option>
            <option value="Christian">Christian</option>
            <option value="Jain">Jain</option>
            <option value="Buddhist">Buddhist</option>
        </select>

        <select id="community-select">
            <option value="">Community</option>
        </select>

        <select>
            <option>State</option>
            <option>Andhra Pradesh</option>
            <option>Telangana</option>
            <option>Karnataka</option>
            <option>Tamil Nadu</option>
            <option>Kerala</option>
            <option>Maharashtra</option>
            <option>Delhi</option>
            <option>Gujarat</option>
            <option>Rajasthan</option>
        </select>

        <button class="search-btn">
            Search
        </button>

    </div>

</section>

<section class="why-us">

    <h2>Why Choose Aurivah?</h2>

    <div class="cards">

        <div class="card">
            <h3>Verified Profiles</h3>
            <p>Every profile goes through manual verification.</p>
        </div>

        <div class="card">
            <h3>Trusted Since 2019</h3>
            <p>Strong offline matrimonial experience.</p>
        </div>

        <div class="card">
            <h3>Smart Matchmaking</h3>
            <p>Community and preference based suggestions.</p>
        </div>

    </div>

</section>

<section class="membership">

    <h2>Membership Plans</h2>

    <div class="plan-container">

        <div class="plan-card">
            <h3>Free</h3>
            <p>Basic Access</p>
            <h4>₹0</h4>
        </div>

        <div class="plan-card premium">
            <h3>Premium</h3>
            <p>Unlimited Chat & Contact Access</p>
            <h4>₹1999</h4>
        </div>

        <div class="plan-card">
            <h3>Elite</h3>
            <p>Priority Matchmaking</p>
            <h4>₹4999</h4>
        </div>

    </div>

</section>

<section class="testimonials">

    <h2>Success Stories</h2>

    <div class="testimonial-box">
        <p>
            “Aurivah helped us find the perfect partner with genuine profiles.”
        </p>
        <h4>- Rahul & Sneha</h4>
    </div>

</section>

<div class="install-popup" id="installPopup">
    <p>Install Aurivah App for Better Experience</p>
    <button onclick="closePopup()">Close</button>
</div>

<?php include 'includes/footer.php'; ?>