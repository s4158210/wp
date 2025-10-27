<!DOCTYPE html>
<html lang="en">

<?php include 'includes/header.inc'; ?>

<body>
    <!-- Navigation -->
    <?php include 'includes/nav.inc'; ?>

    <?php
    // DB connection
    include __DIR__ . '/includes/db_connect.inc';

    // Base path for images
    $IMG_DIR = '/wp/a2/assets/images/skills/';

    if (strpos($_SERVER['HTTP_HOST'], 'csit.rmit.edu.au') !== false) {
        $IMG_DIR = '/~s4158210/wp/a2/assets/images/skills/'; // Titan server
    }

    // Fetch skills from DB
    $sql = "SELECT skill_id, title, description, category, level, rate_per_hr, image_path 
                FROM skills ORDER BY created_at DESC";
    $result = $conn->query($sql);
    ?>

    <!-- Table content for the list of skills -->
    <div class="container mt-5 mb-5">
        <h1 class="mb-4">All Skills</h1>
        <div class="row mt-4 align-items-start">

            <!-- Left column: static skill image -->
            <div class="col-md-4 text-center mb-3 mb-md-0">
                <img src="<?= $IMG_DIR ?>1.png"
                    alt="Skills"
                    class="img-fluid rounded shadow-sm">
            </div>

            <!-- Right column: table -->
            <div class="col-md-8">
                <table class="table table-striped text-center align-middle shadow-sm">
                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Level</th>
                            <th>Rate ($/hr)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php
                            $result->data_seek(0);
                            while ($row = $result->fetch_assoc()):
                                $title = htmlspecialchars($row['title']);
                                $cat   = htmlspecialchars($row['category']);
                                $lvl   = htmlspecialchars($row['level']);
                                $rate  = htmlspecialchars($row['rate_per_hr']);
                            ?>
                                <tr>
                                    <td><a href="details.php?id=<?= $row['skill_id'] ?>"><?= $title ?></a></td>
                                    <td><?= $cat ?></td>
                                    <td><?= $lvl ?></td>
                                    <td><?= $rate ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">No skills available.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal for image pop-up -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <img src="/wp/a2/assets/images/skills/default.png" id="modalImage" class="img-fluid rounded-top" alt="popup img">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'includes/footer.inc'; ?>
    <script src="/wp/a2/assets/scripts.js"></script>

</body>

</html>