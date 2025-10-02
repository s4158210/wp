<?php /* /wp/a2/index.php */ ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'includes/header.inc'; ?>
</head>

<body>
    <?php include 'includes/nav.inc'; ?>

    <?php
    // DB connection
    include __DIR__ . '/includes/db_connect.inc';

    // base path for images
    $IMG_DIR  = '/wp/a2/assets/images/skills/';

    ?>

    <div class="container my-5">

        <!-- Carousel -->
        <div id="Carousel" class="carousel slide mt-4" data-bs-ride="carousel">
            <h1>Skill Swap</h1>
            <h6>BROWSE THE LATEST SKILLS SHARED BY OUR COMMUNITY</h6>

            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="" <?= $IMG_DIR . htmlspecialchars($row['image_path']) ?>" alt="<?= htmlspecialchars($row['title']) ?>" 1.png" class="d-block w-100" alt="Beginner Guitar Lessons">
                    <div class="carousel-caption d-none d-md-block carousel-title">
                        <h5>French Pastry Making</h5>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="" <?= $IMG_DIR . htmlspecialchars($row['image_path']) ?>" alt="<?= htmlspecialchars($row['title']) ?>" 3.png" class="d-block w-100" alt="Artisan Bread Baking">
                    <div class="carousel-caption d-none d-md-block carousel-title">
                        <h5>Artisan Bread Baking</h5>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="" <?= $IMG_DIR . htmlspecialchars($row['image_path']) ?>" alt="<?= htmlspecialchars($row['title']) ?>" 8.png" class="d-block w-100" alt="Intro to PHP & MySQL">
                    <div class="carousel-caption d-none d-md-block carousel-title">
                        <h5>Intro to PHP & MySQL</h5>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="" <?= $IMG_DIR . htmlspecialchars($row['image_path']) ?>" alt="<?= htmlspecialchars($row['title']) ?>" 2.png" class="d-block w-100" alt="Intermediate Fingerstyle">
                    <div class="carousel-caption d-none d-md-block carousel-title">
                        <h5>Intermediate Fingerstyle</h5>
                    </div>
                </div>
            </div>

            <button class="carousel-control-prev ms-3" type="button" data-bs-target="#Carousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next me-3" type="button" data-bs-target="#Carousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>

        <!-- Latest 4 Skills (Dynamic from DB) -->
        <div class="container my-5">
            <?php
            // Fetch latest 4 skills
            $sql = "SELECT skill_id, title, rate_per_hr, image_path 
            FROM skills 
            ORDER BY created_at DESC, skill_id DESC 
            LIMIT 4";
            $result = $conn->query($sql);
            ?>

            <div class="row">
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()):
                        $id    = (int)$row['skill_id'];
                        $title = htmlspecialchars($row['title']);
                        $rate  = htmlspecialchars($row['rate_per_hr']);
                        $img   = "/wp/a2/assets/images/skills/" . ltrim($row['image_path'], '/');
                    ?>
                        <div class="col-md-3 col-sm-6 mb-4">
                            <div class="text-center">
                                <!-- Skill Image -->
                                <a href="/wp/a2/details.php?id=<?= $id ?>">
                                </a>
                                <div class="card-body">
                                    <h5 class="card-title"><?= $title ?></h5>
                                    <p class="card-text">Rate: $<?= $rate ?>/hr</p>
                                    <a href="/wp/a2/details.php?id=<?= $id ?>" class="btn btn-primary">View Details</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No skills available yet.</p>
                <?php endif; ?>
                <?php $conn->close(); ?>
            </div>
        </div>

    </div><!-- /.container -->

    <?php include 'includes/footer.inc'; ?>
    <script src="<?= $JS_DIR ?>scripts.js"></script>
</body>

</html>