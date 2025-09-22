<!DOCTYPE html>
<html lang="en">
<?php include_once 'includes/header.php'; ?>
<?php include_once 'includes/nav.php'; ?>
<div class="container my-5">

    <div id="Carousel" class="carousel slide mt-4" data-bs-ride="carousel">
        <h1>Skill swap</h1>
        <h6>BROWSE THE LATEST SKILLS SHARED BY OUR COMMUNITY</h6>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="images/skills/1.png" class="d-block w-100" alt="images">
                <div class="carousel-caption d-none d-md-block carousel-title">
                    <h5>French Pastry Making</h5>
                </div>
            </div>

            <div class="carousel-item">
                <img src="images/skills/3.png" class="d-block w-100" alt="images">
                <div class="carousel-caption d-none d-md-block carousel-title">
                    <h5>Artisan Bread Baking</h5>
                </div>
            </div>

            <div class="carousel-item">
                <img src="images/skills/8.png" class="d-block w-100" alt="images">
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
                    <p class="card-text">Rate: $55.00/hr</p>
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

<?php include_once 'includes/footer.php'; ?>
</body>

</html>