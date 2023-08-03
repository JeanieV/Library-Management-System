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
        <button type="submit" name="returnBookChanges" class="tranBack"><img class="homeButton mx-3 mt-3"
                src="../img/home1.gif" alt="Back to Home Page" title="Back to Home Page"
                attribution="https://www.flaticon.com/free-animated-icons/home"></button>
    </form>



    <!-- Register New User -->
    <div class="container d-flex justify-content-center align-items-center">
        <div class="mt-5 mb-5 mx-5">

            <form method="POST" class="signUpForm p-5">
                <?php
                // Check if the Add Book button is clicked
                if (isset($_POST['addnewBook'])) {
                    addBook();
                }
                ?>

                <h1> Add a new book to the system: </h1>

                <div class="d-flex justify-content-center align-items-center my-4">
                    <table>
                        <!-- Title -->
                        <tr>
                            <td class="p-4"><label for="title" class="labelStyle"> Title: </label></td>
                            <td class="p-4"><input type="text" name="title" class="inputStyle" required></td>
                        </tr>

                        <!-- Description-->
                        <tr>
                            <td class="p-4"><label for="description" class="labelStyle"> Description: </label>
                            </td>
                            <td class="p-4"><input type="text" name="description" class="inputStyle" required></td>
                        </tr>

                        <!-- Thumbnail -->
                        <tr>
                            <td class="p-4"><label for="thumbnail" class="labelStyle"> Thumbnail (optional): </label></td>
                            <td class="p-4"><input type="text" name="thumbnail" class="inputStyle"></td>
                        </tr>

                        <!-- Author -->
                        <tr>
                            <td class="p-4"><label for="author" class="labelStyle"> Author: </label></td>
                            <td class="p-4"><input type="text" name="author" class="inputStyle" required></td>
                        </tr>

                        <!-- Genre -->
                        <tr>
                            <td class="p-4"><label for="genre" class="labelStyle"> Genre: </label></td>
                            <td class="p-4"><input type="text" name="genre" class="inputStyle" required></td>
                        </tr>

                        <!-- Return Date -->
                        <tr>
                            <td class="p-4"><label for="return_date" class="labelStyle"> Return Date: </label></td>
                            <td class="p-4"><input type="date" name="return_date" class="inputStyle" required></td>
                        </tr> 

                        <!-- Price -->
                        
                        <tr>
                            <td class="p-4"><label for="price" class="labelStyle"> Price: </label></td>
                            <td class="p-4"><input type="text" name="price" class="inputStyle" required></td>
                        </tr>
                    </table>
                </div>

                <!-- Add New Book Button -->
                <div class="container d-flex justify-content-center align-items-center">
                    <div class="mx-5 mt-3 mb-5">
                        <button name="addnewBook" type="submit" class="logInButton p-2"> Add Book</button>
                    </div>

                    
                </div>

            </form>
        </div>
    </div>
</body>

</html>