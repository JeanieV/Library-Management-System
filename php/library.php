<?php
session_start();
require './functions.php';


if (isset($_SESSION['fullname'])) {
    $fullname = $_SESSION['fullname'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librarian </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel='stylesheet' type='text/css' media='screen' href='../css/librarian.css'>
</head>

<body>

    <!-- Return Home Button -->
    <form method="POST">
        <button type="submit" name="returnHome" class="tranBack"><img class="homeButton mx-3 mt-3"
                src="../img/home1.gif" alt="Back to Home Page" title="Back to Home Page"
                attribution="https://www.flaticon.com/free-animated-icons/home"></button>
    </form>

    <div class="container d-flex justify-content-center align-items-center">
        <div class="mt-5 mb-5">

            <!-- This will make sure that the fullname is known throughout the website -->

            <form method="POST" name="bookView" class="bookView p-5">

                <?php $book1 = <<<DELIMETER
                <h1 class="mb-5"> Welcome, $fullname! </h1>   
                <p> Here you can manage Library Books! </p>
                DELIMETER;
                echo $book1;
                ?>

                <h2 class="mt-5 mb-2"> Add new Librarians to the system </h2>

                <!-- New Librarian Button -->
                <div class="container d-flex justify-content-center align-items-center">
                    <div class="mx-5 mt-1">
                        <button name="newEmployee" type="submit" class="logInButton p-2">Add</button>
                    </div>
                </div>

                <h2 class="mt-5 mb-2"> Make changes to the Books on the system </h2>

                <!-- Book Button -->
                <div class="container d-flex justify-content-center align-items-center">
                    <div class="mx-5 mt-1">
                        <button name="bookChangesButton" type="submit" class="logInButton p-2"> Change </button>
                    </div>
                </div>
            </form>

        </div>
    </div>

</body>

</html>