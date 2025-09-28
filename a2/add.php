<!DOCTYPE html>
<html lang="en">

<?php include 'includes/header.inc'; ?>

<body>

    <?php include 'includes/nav.inc'; ?>

    <main class="container py-5">
        <h1 class="mb-4">Add New Skill</h1>
        <form id="skillForm" novalidate>

            <div class="mb-3">
                <label for="title" class="form-lable">Title *</label>
                <input type="text" class="form-control" id="title" placeholder="title" required />
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description *</label>
                <textarea class="form-control" id="description" rows="4" placeholder="description"></textarea>
            </div>

            <div class="mb-3">
                <label for="category" class="form-label">Category *</label>
                <input type="text" class="form-control" id="category" placeholder="category" required />
            </div>

            <div class="mb-3">
                <label for="rate" class="form-label">Rate per Hour ($) *</label>
                <input type="number" class="form-control" id="rate" placeholder="99.65" required />
            </div>

            <div class="mb-3">
                <label for="level" class="form-label">Level *</label>
                <select class="form-select" id="level" required>
                    <option value="">Please select</option>
                    <option>Beginner</option>
                    <option>Intermediate</option>
                    <option>Advanced</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="skillImage" class="form-label">Skill Image *</label>
                <input type="file" class="form-control" id="skillImage" accept=".jpg,.jpeg,.png,.gif,.webp" required />
                <div class="file-warning mt-2" id="fileHelp">
                    Only image files are allowed (JPG, PNG, GIF, WEBP).
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>

        </form>

    </main>

    <?php include 'includes/footer.inc'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/scripts.js"></script>
</body>

</html>