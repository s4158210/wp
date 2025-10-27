<nav class="navbar navbar-expand-lg">
    <div class="container">

        <!-- Brand -->
        <a class="navbar-brand fw-bold" href="index.php">
            <img src="assets/images/favicon.ico" alt="Logo">
        </a>

        <!-- Mobile toggle button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Collapsible nav links -->
        <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
            <ul class="navbar-nav align-items-start">
                <li class="nav-item mx-2">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item mx-2">
                    <a class="nav-link" href="skills.php">All Skills</a>
                </li>
                <li class="nav-item mx-2">
                    <a class="nav-link" href="gallery.php">Gallery</a>
                </li>
                <li class="nav-item mx-2">
                    <a class="nav-link" href="add.php">Add Skill</a>
                </li>
            </ul>

            <?php if (isset($_SESSION['user_id'])): ?>
                <span class="navbar-text me-2">
                    Hello, <?= htmlspecialchars($_SESSION['username']) ?>
                </span>
                <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-outline-light btn-sm me-2">Login</a>
                <a href="register.php" class="btn btn-light btn-sm">Register</a>
            <?php endif; ?>

            <!-- Search -->
            <form class="d-flex" role="search">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="fas fa-search"></i>
                    </span>
                    <input class="form-control border-start-0" type="search" placeholder="Search skills..." aria-label="Search">
                </div>
            </form>

            <!-- Login/Register -->


            <!-- Auth buttons -->

        </div>
    </div>
</nav>