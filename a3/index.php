<?php /* /wp/a3/index.php (works anywhere) */ ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include __DIR__ . '/includes/header.inc'; ?>
</head>


<body>
    <?php
    include __DIR__ . '/includes/nav.inc';
    // compute base
    $APP_BASE = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
    if ($APP_BASE === '' || $APP_BASE === '\\') {
        $APP_BASE = '/';
    }


    include __DIR__ . '/includes/db_connect.inc';



    $IMG_DIR  = $APP_BASE . '/assets/images/skills/';
    $BASE_URL = $APP_BASE . '/';

    // Latest 4 for carousel
    $stmt = $conn->prepare("SELECT skill_id, title, image_path FROM skills ORDER BY created_at DESC, skill_id DESC LIMIT 4");
    $stmt->execute();
    $carouselResult = $stmt->get_result();
    ?>

    <div class="container my-5">
        <div id="Carousel" class="carousel slide mt-4" data-bs-ride="carousel">
            <h1>Skill Swap</h1>
            <h6>BROWSE THE LATEST SKILLS SHARED BY OUR COMMUNITY</h6>

            <div class="carousel-inner">
                <?php $active = true;
                while ($row = $carouselResult->fetch_assoc()): ?>
                    <?php
                    $title = htmlspecialchars($row['title']);
                    $img   = $IMG_DIR . basename($row['image_path']);
                    ?>
                    <div class="carousel-item <?= $active ? 'active' : '' ?>">
                        <img src="<?= htmlspecialchars($img) ?>" class="d-block w-100" alt="<?= $title ?>">
                        <div class="carousel-caption d-none d-md-block carousel-title">
                            <h5><?= $title ?></h5>
                        </div>
                    </div>
                <?php $active = false;
                endwhile; ?>
            </div>

            <button class="carousel-control-prev ms-3" type="button" data-bs-target="#Carousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next me-3" type="button" data-bs-target="#Carousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>

        <!-- Latest 4 cards -->
        <div class="container my-5">
            <?php
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
                        $img   = $IMG_DIR . ltrim($row['image_path'], '/');
                    ?>
                        <div class="col-md-3 col-sm-6 mb-4">
                            <div class="text-center">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $title ?></h5>
                                    <p class="card-text">Rate: $<?= $rate ?>/hr</p>
                                    <a href="<?= $BASE_URL ?>details.php?id=<?= $id ?>" class="btn btn-primary">View Details</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No skills available yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div> <!-- /.container my-5 wrapper -->
    </div> <!-- /.container (outer) -->

    <?php include __DIR__ . '/includes/footer.inc'; ?>

    <?php
    // ✅ If you really want to close the connection, do it AFTER the footer:
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
    ?>
    <script src="<?= $APP_BASE ?>/assets/scripts.js"></script>
</body>

</html>