<?php
session_start();
require './functions.php';

if (isset($_POST['deleteBookFinalButton'])) {
    $bookId = $_POST['book_id'];
    $librarian = new Librarian(db_connect());
    $result = $librarian->deleteBook($bookId);

    if ($result) {
        // Book deleted successfully, redirect back to the deleteBooks.php
        header("Location: ./deleteBooks.php");
        exit();
    } else {
        // Failed to delete book, handle the error or redirect to an error page
        header("Location: ./error.php");
        exit();
    }
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

                <h1> View all books on the system: </h1>

                <?php deleteBooks(); ?>

            </form>
        </div>
    </div>
</body>

</html>