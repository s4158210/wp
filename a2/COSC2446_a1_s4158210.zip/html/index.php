<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skillswap</title>
    <link href="https://fonts.googleapis.com/css2?family=Ysabeau+SC&family=Libre+Baskerville&display=swap" rel="stylesheet">
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
                     <a class="navbar-brand fw-bold"><a href="index.html">
                         <img src="images/favicon.ico" alts="Logo">
                    </a>
                    <li class="nav-item"><a class="nav-link" ><a href="index.html">Home</a></li>
                    <li class="nav-item"><a class="nav-link" ><a href="skills.html">All Skills</a></li>
                    <li class="nav-item"><a class="nav-link" ><a href="gallery.html">Gallery</a></li>
                    <li class="nav-item"><a class="nav-link" ><a href="add.html">Add Skill</a></li>
                </ul>
                <form class="d-flex" role="search">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search"></i></span>
                        <input class="form-control border-start-0" type="search" placeholder="Search skills..." aria-label="Search">
                    </div>
                </form>
            </div>
        </div>
    </nav>
   <div class="container my-5">

        <div id="Carousel" class="carousel slide mt-4" data-bs-ride="carousel">
            <h1>Skill swap</h1>
            <h6>BROWSE THE LATEST SKILLS SHARED BY OUR COMMUNITY</h6>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="images/skills/1.png" class="d-block w-100"  alt="images">
                    <div class="carousel-caption d-none d-md-block carousel-title">
                    <h5>French Pastry Making</h5>
                    </div>
                </div>

                <div class="carousel-item">
                    <img src="images/skills/3.png" class="d-block w-100"  alt="images">
                    <div class="carousel-caption d-none d-md-block carousel-title">
                        <h5>Artisan Bread Baking</h5>
                    </div>
                </div>

                <div class="carousel-item">
                    <img src="images/skills/8.png" class="d-block w-100"  alt="images">
                    <div class="carousel-caption d-none d-md-block carousel-title">
                        <h5>Intro to PHP & MySQL</h5>
                    </div>
                </div>

                <div class="carousel-item">
                    <img src="images/skills/2.png" class="d-block w-100" alt="images">
                    <div class="carousel-caption d-none d-md-block carousel-title">
                        <h5>Intermediate Fingerstyle</h5>
                    </div>
                </div>
            </div>
        </div>

        <button class="carousel-control-prev ms-3" type="button" data-bs-target="#Carousel" data-bs-slide="prev">
             <span class="carousel-control-prev-icon"></span>
         </button>
        <button class="carousel-control-next me-3" type="button" data-bs-target="#Carousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
        
        <div class="container my-5">
            <div class="row text-center">
                <div class="col-md-3 mb-4 container-details">
                            <h5 class="card-title">Intro to PHP & MySQL</hp6>
                            <p class ="card-text">Rate: $55.00/hr</p>
                            <a href="#" class="btn btn-primary ">View Details</a>
                </div>
                <div class="col-md-3 mb-4 container-details">
                            <h5 class="card-title">Intermediate Fingerstyle</hp6>
                            <p class="card-text">Rate: $45.00/hr</p>
                            <a href="#" class="btn btn-primary">View Details</a>

                </div>
                <div class="col-md-3 mb-4 container-details">
                            <h5 class="card-title">Artisan Bread Baking</hp6>
                            <p class="card-text">Rate: $25.00/hr</p>
                            <a href="#" class="btn btn-primary">View Details</a>
                </div>
                <div class="col-md-3 mb-4 container-details">
                            <h5 class="card-title">French Pastry Making</hp6>
                            <p class="card-text">Rate: $35.00/hr</p>
                            <a href="#" class="btn btn-primary">View Details</a>
                </div>
            </div>
        </div>
    </div>
    
    <footer class = "container-fluid">&copy; 2025 SkillsSwap all rights reserved Tanath Medhanee</footer>
</body>
</html>