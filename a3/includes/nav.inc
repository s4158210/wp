<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$APP_BASE = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
if ($APP_BASE === '' || $APP_BASE === '\\') {
    $APP_BASE = '/';
}
?>
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #c75412;">

    <div class="container">

        <!-- Brand -->
        <a class="navbar-brand d-flex align-items-center fw-bold" href="<?= $APP_BASE ?>/index.php">
            <img src="<?= $APP_BASE ?>/assets/images/favicon.ico" alt="Logo" width="32" height="32" class="me-2">
            SkillSwap
        </a>

        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Nav Links -->
        <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="<?= $APP_BASE ?>/index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $APP_BASE ?>/skills.php">All Skills</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $APP_BASE ?>/gallery.php">Gallery</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $APP_BASE ?>/add.php">Add Skill</a>
                </li>
            </ul>

            <!-- Right Section: Search + Auth -->
            <div class="d-flex align-items-center gap-3">
                <form class="d-flex" role="search">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search"></i>
                        </span>
                        <input class="form-control border-start-0" type="search" placeholder="Search skills..." aria-label="Search">
                    </div>
                </form>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="navbar-text text-white ms-2">Hello, <?= htmlspecialchars($_SESSION['username']) ?></span>
                    <a href="<?= $APP_BASE ?>/logout.php" class="btn btn-outline-light btn-sm ms-1">Logout</a>
                <?php else: ?>
                    <a href="<?= $APP_BASE ?>/login.php" class="btn btn-outline-light btn-sm">Login</a>
                    <a href="<?= $APP_BASE ?>/register.php" class="btn btn-warning btn-sm text-dark fw-semibold">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>