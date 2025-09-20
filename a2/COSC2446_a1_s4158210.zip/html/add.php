<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skillswap</title>
    <link href="https://fonts.googleapis.com/css2?family=Ysabeau+SC&family=Libre+Baskerville&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">


    <!-- Latest compiled and minified CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="assets/styles.css">

</head>

<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
                <ul class="navbar-nav align-items-start">
                    <a class="navbar-brand fw-bold"><a href="home.html">
                            <img src="images/favicon.ico" alts="Logo">
                        </a>
                        <li class="nav-item"><a class="nav-link"><a href="home.html">Home</a></li>
                        <li class="nav-item"><a class="nav-link"><a href="skills.html">All Skills</a></li>
                        <li class="nav-item"><a class="nav-link"><a href="gallery.html">Gallery</a></li>
                        <li class="nav-item"><a class="nav-link"><a href="add.html">Add Skill</a></li>
                </ul>
                <form class="d-flex" role="search">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search"></i></span>
                        <input class="form-control border-start-0" type="search" placeholder="Search skills..."
                            aria-label="Search">
                    </div>
                </form>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        <h1 class="mb-4">Add New Skill</h1>
        <form id="skillForm" novalidate>

            <div class="mb-3">
                <label for="title" class="form-lable">Title *</label>
                <input type="text" class="form-control" id="title" placeholder="title" required/>
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

    <footer class="container-fluid">&copy; 2025 SkillsSwap all rights reserved Tanath Medhanee</footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/scripts.js"></script>
</body>
</html>