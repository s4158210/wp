<!DOCTYPE html>
<html lang="en">

<?php include_once 'includes/header.php'; ?>


<body>
    <!--navphp placement here -->
    <?php include_once 'includes/nav.php'; ?>


    <!--Table content for the list of skills-->
    <div class="container mt-5 mb-5">
        <h1 class=>All Skills</h2>
            <div class="row mt-4">
                <div class="col-md-4">
                    <img src="images/skills/8.png" alt="skills img" class="img-fluid rounded skill-img">
                </div>
                <div class="col-md-8">
                    <table class="table table-striped text-center">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Level</th>
                                <th>Rate ($/hr)</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td><a href="#">Beginner Guitar Lessons</a></td>
                                <td>Music</td>
                                <td>Beginner</td>
                                <td>30.00</td>
                            </tr>
                            <tr>
                                <td><a href="#">Intermediate Fingerstyle</a></td>
                                <td>Music</td>
                                <td>Intermediate</td>
                                <td>45.00</td>
                            </tr>
                            <tr>
                                <td><a href="#">Artisan Bread Baking</a></td>
                                <td>Cooking</td>
                                <td>Beginner</td>
                                <td>25.00</td>
                            </tr>
                            <tr>
                                <td><a href="#">French Pastry Making</a></td>
                                <td>Cooking</td>
                                <td>Expert</td>
                                <td>50.00</td>
                            </tr>
                            <tr>
                                <td><a href="#">Watercolor Basics</a></td>
                                <td>Art</td>
                                <td>Beginner</td>
                                <td>20.00</td>
                            </tr>
                            <tr>
                                <td><a href="#">Digital Illustration with Procreate</a></td>
                                <td>Art</td>
                                <td>Intermediate</td>
                                <td>40.00</td>
                            </tr>
                            <tr>
                                <td><a href="#">Morning Vinyasa Flow</a></td>
                                <td>Wellness</td>
                                <td>Intermediate</td>
                                <td>35.00</td>
                            </tr>
                            <tr>
                                <td><a href="#">Intro to PHP & MySQL</a></td>
                                <td>Programming</td>
                                <td>Expert</td>
                                <td>55.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
    </div>

    <!--Footer content-->
    <?php include_once 'includes/footer.php'; ?>
</body>

</html>