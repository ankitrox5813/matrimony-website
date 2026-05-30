<!-- <?php

// include("includes/db.php");

// echo "Database Connected Successfully";

?> -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aurivah - Trusted Matrimonial Service</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/home.css">
    <link rel="manifest" href="manifest.json">
</head>
<body>

    <!-- Navbar -->
    <header>
        <nav class="navbar">
            <div class="logo">Aurivah</div>

            <ul class="nav-links">
                <li><a href="#">Home</a></li>
                <li><a href="profiles.php">Search</a></li>
                <li><a href="membership.php">Membership</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>

            <div class="menu-btn" onclick="toggleMenu()">
                ☰
            </div>
        </nav>
    </header>


    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Find Your Perfect Life Partner</h1>
            <p>Trusted Matrimonial Service Since 2019</p>

            <a href="register.php" class="btn-primary">
                Register Free
            </a>
        </div>
    </section>


    <!-- Search Section -->
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

            <!-- <select>
                <option>Community</option>
                <option>Brahmin</option>
                <option>Rajput</option>
                <option>Kayastha</option>
            </select> -->

            <select>
                <option>State</option>
                <option>Bihar</option>
                <option>Jharkhand</option>
                <option>Delhi</option>
                <option>West Bengal</option>
                <option>Uttar Pradesh</option>
                <option>Odisha</option>
                <option>Chhatisgarh</option>
                <option>Madhya Pradesh</option>
                <option>Gujarat</option>
                <option>Haryana</option>
                <option>Rajasthan</option>
                <option>Punjab</option>
                <option>Maharashtra</option>
                <option>Uttarakhand</option>
                <option>Himachal Pradesh</option>
                <option>Jammu & Kashmir</option>
                <option>Laddakh</option>
                <option>Assam</option>
                <option>Sikkim</option>
                <option>Meghalaya</option>
                <option>Manipur</option>
                <option>Arunachal Pradesh</option>
                <option>Nagaland</option>
                <option>Mizoram</option>
                <option>Tripura</option>
                <option>Nagaland</option>
                <option>Telangana</option>
                <option>Goa</option>
                <option>Karnataka</option>
                <option>Andhra Pradesh</option>
                <option>Tamil Nadu</option>
                <option>Kerala</option>
                <option>Pondicherry</option>
                <option>Daman</option>
                <option>Diu</option>
                <option>Dadra & Nagar Haveli</option>
                <option>Lakshadweep</option>
                <option>Andaman & Nicobar Islands</option>
            </select>

            <!-- <button>Search</button> -->
             <button class="search-btn">Search</button>

        </div>

    </section>
    <!-- Why Choose Us -->
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
    <!-- Membership Plans -->
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


    <!-- Testimonials -->
    <section class="testimonials">

        <h2>Success Stories</h2>

        <div class="testimonial-box">
            <p>
                “Aurivah helped us find the perfect partner with genuine profiles.”
            </p>
            <h4>- Rahul & Sneha</h4>
        </div>

    </section>

    <!-- App Install Popup -->
    <div class="install-popup" id="installPopup">

        <p>Install Aurivah App for Better Experience</p>

        <button onclick="closePopup()">Close</button>

    </div>


    <!-- Footer -->
    <footer>

        <div class="footer-content">
            <h3>Aurivah</h3>
            <p>© 2019-2026. All Rights Reserved.</p>
        </div>
        <div class="footer-links">

<a href="usersagreement.php">User Agreement</a>
<a href="privacypolicy.php">Data Protection Policy</a>
<a href="safe-online.php">Online Safety Guide</a>
<a href="report.php">Report Concerns</a>

</div>

    </footer>


    <script src="js/app.js"></script>

</body>
</html>

