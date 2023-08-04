<?php
session_start();
require './functions.php';

if (isset($_SESSION['fullname'])) {
    $fullname = $_SESSION['fullname'];
}


?>

<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Delete Book Page</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>
    <link rel='stylesheet' type='text/css' media='screen' href='../css/librarian.css'>
</head>

<body>

    <!-- Return Home Button -->
    <form method="POST">
        <button type="submit" name="returnBookChanges" class="tranBack"><img class="homeButton mx-3 mt-3"
                src="../img/home1.gif" alt="Back to Home Page" title="Back to Home Page"
                attribution="https://www.flaticon.com/free-animated-icons/home"></button>
    </form>

    <!-- Register New User -->
    <div class="container d-flex justify-content-center align-items-center">
        <div class="mt-5 mb-5 mx-5">

        
            <form method="POST" class="bookView p-5">
            <?php
                // Check if the Sign Up button is clicked
                if (isset($_POST['deleteBookFinalButton'])) {
                    deleteFinalBooks();
                }
                ?>

                <?php echo "<h1 class='mb-5'> Delete books on the system <br> $fullname: </h1>"; ?>

                <?php viewBooksToDelete(); ?>

                <?php
                // Display error message if set
                if (isset($_SESSION['notDeleted'])) {
                    echo '<div class="container d-flex justify-content-center align-items-center">';
                    echo '<p class="text-danger">' . $_SESSION['notDeleted'] . '</p>';
                    echo '</div>';
                    unset($_SESSION['notDeleted']);
                }
                ?>
            </form>
        </div>
    </div>
</body>

</html>