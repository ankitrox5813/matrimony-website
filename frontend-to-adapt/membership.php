<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Plans - Aurivah</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/pages.css">
</head>
<body>
 
<?php include("includes/header.php"); ?>
 
<!-- ── HERO ──────────────────────────────────────────────── -->
<section class="page-hero page-hero--membership">
    <div class="page-hero-inner">
        <div class="page-hero-badge">Membership Plans</div>
        <h1 class="page-hero-title">Choose Your<br><em>Membership Plan</em></h1>
        <p class="page-hero-sub">Unlock Premium Features and Connect Faster</p>
    </div>
    <div class="page-hero-shape"></div>
</section>
 
<!-- ── PLANS ─────────────────────────────────────────────── -->
<section class="plans-section">
    <div class="plans-inner">
 
        <!-- Free -->
        <div class="plan-box">
            <div class="plan-header">
                <div class="plan-icon">🆓</div>
                <h3>Free</h3>
                <div class="plan-price">₹0</div>
                <div class="plan-duration">Forever Free</div>
            </div>
            <ul class="plan-features">
                <li>✓ Create Profile</li>
                <li>✓ Upload Photos</li>
                <li>✓ Receive Match Suggestions</li>
                <li>✓ Limited Profile Viewing</li>
                <li>✓ Basic Search</li>
            </ul>
            <a href="register.php" class="plan-btn plan-btn--outline">Start Free</a>
        </div>
 
        <!-- Silver -->
        <div class="plan-box">
            <div class="plan-header">
                <div class="plan-icon">🥈</div>
                <h3>Silver</h3>
                <div class="plan-price">₹2,499</div>
                <div class="plan-duration">for 3 Months</div>
            </div>
            <ul class="plan-features">
                <li>✓ Everything in Free</li>
                <li>✓ View Contact Details <strong>(30 contacts)</strong></li>
                <li>✓ Unlimited Profile Views</li>
                <li>✓ Send Interest Requests</li>
                <li>✓ Priority Search Results</li>
            </ul>
            <a href="contact.php" class="plan-btn plan-btn--outline">Upgrade Now</a>
        </div>
 
        <!-- Gold — highlighted -->
        <div class="plan-box plan-box--gold">
            <div class="plan-popular-badge">Most Popular</div>
            <div class="plan-header">
                <div class="plan-icon">🥇</div>
                <h3>Gold</h3>
                <div class="plan-price">₹3,499</div>
                <div class="plan-duration">for 6 Months</div>
            </div>
            <ul class="plan-features">
                <li>✓ Everything in Silver</li>
                <li>✓ Unlimited Interests</li>
                <li>✓ View Contact Details <strong>(60 contacts)</strong></li>
                <li>✓ Profile Highlight</li>
                <li>✓ Advanced Search Filters</li>
                <li>✓ Priority Support</li>
            </ul>
            <a href="contact.php" class="plan-btn plan-btn--primary">Get Gold</a>
        </div>
 
        <!-- Platinum -->
        <div class="plan-box plan-box--platinum">
            <div class="plan-header">
                <div class="plan-icon">💎</div>
                <h3>Platinum</h3>
                <div class="plan-price">₹4,999</div>
                <div class="plan-duration">for 12 Months</div>
            </div>
            <ul class="plan-features">
                <li>✓ Everything in Gold</li>
                <li>✓ Dedicated Matchmaking Assistance</li>
                <li>✓ Verified Profile Badge</li>
                <li>✓ Top Search Placement</li>
                <li>✓ Relationship Consultation</li>
                <li>✓ View Contact Details <strong>(90 contacts)</strong></li>
            </ul>
            <a href="contact.php" class="plan-btn plan-btn--dark">Be Platinum</a>
        </div>
 
        <!-- Diamond -->
        <div class="plan-box plan-box--diamond">
            <div class="plan-header">
                <div class="plan-icon">👑</div>
                <h3>Diamond</h3>
                <div class="plan-price">₹6,999</div>
                <div class="plan-duration">for 18 Months</div>
            </div>
            <ul class="plan-features">
                <li>✓ Everything in Platinum</li>
                <li>✓ Top Priority Service</li>
                <li>✓ 1 to 1 Dedicated Support</li>
                <li>✓ Wedding Planning Assistance</li>
                <li>✓ View Contact Details <strong>(150 contacts)</strong></li>
            </ul>
            <a href="contact.php" class="plan-btn plan-btn--diamond">Be Diamond</a>
        </div>
 
    </div>
</section>
 
<!-- ── COMPARISON TABLE ───────────────────────────────────── -->
<section class="compare-section">
    <div class="compare-inner">
        <div class="section-eyebrow">Feature Comparison</div>
        <h2 class="section-heading center">What's Included</h2>
 
        <div class="compare-wrap">
            <table class="compare-table">
                <thead>
                    <tr>
                        <th>Feature</th>
                        <th>Free</th>
                        <th>Silver</th>
                        <th class="highlight-col">Gold</th>
                        <th>Platinum</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Profile Creation</td>
                        <td class="yes">✓</td>
                        <td class="yes">✓</td>
                        <td class="yes highlight-col">✓</td>
                        <td class="yes">✓</td>
                    </tr>
                    <tr>
                        <td>Send Interest</td>
                        <td class="no">✗</td>
                        <td class="yes">✓</td>
                        <td class="yes highlight-col">✓</td>
                        <td class="yes">✓</td>
                    </tr>
                    <tr>
                        <td>Chat</td>
                        <td class="no">✗</td>
                        <td class="no">✗</td>
                        <td class="yes highlight-col">✓</td>
                        <td class="yes">✓</td>
                    </tr>
                    <tr>
                        <td>Contact Details</td>
                        <td class="no">✗</td>
                        <td class="yes">✓</td>
                        <td class="yes highlight-col">✓</td>
                        <td class="yes">✓</td>
                    </tr>
                    <tr>
                        <td>Profile Highlight</td>
                        <td class="no">✗</td>
                        <td class="no">✗</td>
                        <td class="yes highlight-col">✓</td>
                        <td class="yes">✓</td>
                    </tr>
                    <tr>
                        <td>Dedicated Assistance</td>
                        <td class="no">✗</td>
                        <td class="no">✗</td>
                        <td class="no highlight-col">✗</td>
                        <td class="yes">✓</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>
 
<!-- ── FAQS ───────────────────────────────────────────────── -->
<section class="faq-section">
    <div class="faq-inner">
        <div class="section-eyebrow">FAQs</div>
        <h2 class="section-heading center">Common Questions</h2>
 
        <div class="faq-list">
            <div class="faq-item">
                <div class="faq-q" onclick="toggleFaq(this)">
                    Can I upgrade my plan later?
                    <span class="faq-arrow">▾</span>
                </div>
                <div class="faq-a">
                    Yes, you can upgrade at any time. Contact our support team and we'll help you transition smoothly.
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-q" onclick="toggleFaq(this)">
                    Is payment secure?
                    <span class="faq-arrow">▾</span>
                </div>
                <div class="faq-a">
                    All payments are processed through secure, encrypted payment gateways. Your financial information is never stored on our servers.
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-q" onclick="toggleFaq(this)">
                    Can I cancel my plan?
                    <span class="faq-arrow">▾</span>
                </div>
                <div class="faq-a">
                    Yes, cancellation is possible according to our refund policy. Please contact support within the eligible refund window.
                </div>
            </div>
        </div>
    </div>
</section>
 
<!-- ── CTA ────────────────────────────────────────────────── -->
<section class="cta-section">
    <div class="cta-inner">
        <h2>Ready to Find Your Life Partner?</h2>
        <p>Join Aurivah Today — Free, Trusted, Verified.</p>
        <a href="register.php" class="btn-primary btn-lg">Create Free Profile</a>
    </div>
</section>
 
<footer>
    <div class="footer-content">
        <div>
            <h3>Aurivah</h3>
            <p>© 2019-2026. All Rights Reserved.</p>
        </div>
    </div>
    <div class="footer-links">
        <a href="usersagreement.php">User Agreement</a>
        <a href="privacypolicy.php">Privacy Policy</a>
        <a href="safe-online.php">Online Safety</a>
        <a href="report.php">Report Concerns</a>
    </div>
</footer>
 
<script>
function toggleFaq(el){
    const item = el.parentElement;
    const isOpen = item.classList.contains('open');
    document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('open'));
    if(!isOpen) item.classList.add('open');
}
</script>
 
</body>
</html>