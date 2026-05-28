<header>
    <nav class="navbar">

        <div class="logo">
            Aurivah
        </div>

        <ul class="nav-links">

            <?php if(isset($_SESSION['user_id'])): ?>

                <li>
                    <a href="dashboard.php">Dashboard</a>
                </li>

                <li>
                    <a href="profiles.php">Search</a>
                </li>

                <li>
                    <a href="logout.php">Logout</a>
                </li>

                <?php else: ?>

                <li>
                    <a href="login.php">Login</a>
                </li>

                <li>
                    <a href="register.php">Register</a>
                </li>

            <?php endif; ?>

        </ul>

        <div class="menu-btn" onclick="toggleMenu()">
            ☰
        </div>

    </nav>
</header>