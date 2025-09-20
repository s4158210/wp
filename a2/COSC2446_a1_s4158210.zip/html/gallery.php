<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://fonts.googleapis.com/css2?family=Ysabeau+SC&family=Libre+Baskerville&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Latest compiled and minified CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Latest compiled JavaScript -->
    
    <link rel="stylesheet" href="assets/styles.css">
    
    

</head>

<body>

    <!--Navigation bar content-->
    <nav class="navbar navbar-expand-lg">
        <div class="container">

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
                <ul class="navbar-nav align-items-start">
                    <a class="navbar-brand fw-bold"><a href="index.html">
                            <img src="images/favicon.ico" alts="Logo">
                        </a>
                        <li class="nav-item"><a class="nav-link"><a href="index.html">Home</a></li>
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

    <!--Gallery content for the 8 images-->
    <main class="container my-5">
        <h1 class="mb-4">Skill Gallery</h2>
        <div class="row g-4">
                <div class="col-6 col-md-3">
                    <img src="images/skills/1.png" 
                        class="img-fluid rounded gallery" 
                        data-bs-toggle="modal" 
                        data-bs-target="#imageModal" 
                        data-bs-image="images/skills/1.png" 
                        alt="Beginner Guitar Lessons">
                    <p class="text-center mt-2">Beginner Guitar Lessons</p>
                </div>
                <div class="col-6 col-md-3">
                    <img src="images/skills/2.png" 
                        class="img-fluid rounded gallery" 
                        data-bs-toggle="modal" 
                        data-bs-target="#imageModal" 
                        data-bs-image="images/skills/2.png" 
                        alt="Intermediate Fingerstyle">
                    <p class="text-center mt-2">Intermediate Fingerstyle</p>
                </div>
                <div class="col-6 col-md-3">
                    <img src="images/skills/3.png" 
                        class="img-fluid rounded gallery" 
                        data-bs-toggle="modal" 
                        data-bs-target="#imageModal" 
                        data-bs-image="images/skills/3.png" 
                        alt="Artisan Bread Making">
                    <p class="text-center mt-2">Artisan Bread Making</p>
                </div>
                <div class="col-6 col-md-3">
                    <img src="images/skills/4.png" 
                        class="img-fluid rounded gallery" 
                        data-bs-toggle="modal" 
                        data-bs-target="#imageModal" 
                        data-bs-image="images/skills/4.png" 
                        alt="French Pastry Making">
                    <p class="text-center mt-2">French Pastry Making</p>
                </div>
                <div class="col-6 col-md-3">
                    <img src="images/skills/5.png"
                         class="img-fluid rounded gallery" 
                        data-bs-toggle="modal" 
                        data-bs-target="#imageModal" 
                        data-bs-image="images/skills/5.png" 
                        alt="Watercolor basics">
                    <p class="text-center mt-2">Watercolor Basics</p>
                </div>
                <div class="col-6 col-md-3">
                    <img src="images/skills/6.png" 
                        class="img-fluid rounded gallery" 
                        data-bs-toggle="modal" 
                        data-bs-target="#imageModal" 
                        data-bs-image="images/skills/6.png" 
                        alt="Digital Illustration with Procreate">
                    <p class="text-center mt-2">Digital Illustration with Procreate</p>
                </div>
                <div class="col-6 col-md-3">
                    <img src="images/skills/7.png" 
                        class="img-fluid rounded gallery" 
                        data-bs-toggle="modal" 
                        data-bs-target="#imageModal" 
                        data-bs-image="images/skills/7.png" 
                        alt="Morning Vinyasa">
                    <p class="text-center mt-2">Morning Vinyasa</p>
                </div>
                <div class="col-6 col-md-3">
                    <img src="images/skills/8.png" 
                        class="img-fluid rounded gallery" 
                        data-bs-toggle="modal" 
                        data-bs-target="#imageModal" 
                        data-bs-image="images/skills/8.png" 
                        alt="Intro to PHP & MySQL">
                    <p class="text-center mt-2">Intro to PHP & MySQL</p>
                </div>
        </div>
    </main>

    <!--Code for image pop up function-->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-label ="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <img src="" id="modalImage" class="img-fluid rounded-top" alt="popup img">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <!--Footer content-->
    <footer class="container-fluid">&copy; 2025 SkillsSwap all rights reserved Tanath Medhanee</footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/scripts.js"></script>
    
    
    
</body>
</html>