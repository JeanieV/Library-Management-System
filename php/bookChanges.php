<?php
session_start();
require './functions.php';

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Book Changes Page</title>
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
        <button type="submit" name="returnLibrary" class="tranBack"><img class="homeButton mx-3 mt-3"
                src="../img/home1.gif" alt="Back to Home Page" title="Back to Home Page"
                attribution="https://www.flaticon.com/free-animated-icons/home"></button>
    </form>



    <!-- Register New User -->
    <div class="container d-flex justify-content-center align-items-center">
        <div class="mt-5 mb-5 mx-5">

            <form method="POST" name="signUpForm" class="signUpForm p-5">

                <h1 class="my-5"> Choose what CRUD operation to perform: </h1>

                <h2 class="mt-5 mb-3"> Add new Books to the system: </h2>

                <!-- Add Book Button -->
                <div class="container d-flex justify-content-center align-items-center">
                    <div class="mx-5 mt-3 mb-3">
                        <button name="addBookNew" type="submit" class="logInButton p-2">Add Book</button>
                    </div>
                </div>

                <h2 class="mt-3 mb-3"> Update Existing Books on the system: </h2>

                <!-- Update Book Button -->
                <div class="container d-flex justify-content-center align-items-center">
                    <div class="mx-5 mt-3 mb-3">
                        <button name="updateBook" type="submit" class="logInButton p-2">Update Book</button>
                    </div>
                </div>

                <h2 class="mt-3 mb-3"> View Current Books on the system: </h2>

                <!-- Read Book Button -->
                <div class="container d-flex justify-content-center align-items-center">
                    <div class="mx-5 mt-3 mb-3">
                        <button name="readBook" type="submit" class="logInButton p-2">View Books</button>
                    </div>
                </div>

                <h2 class="mt-3 mb-3"> Delete Books on the system: </h2>

                <!-- Delete Book Button -->
                <div class="container d-flex justify-content-center align-items-center">
                    <div class="mx-5 mt-3 mb-3">
                        <button name="deleteBook" type="submit" class="logInButton p-2">Delete Books</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</body>

</html>